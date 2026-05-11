# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Overview

`bkpsdm-karawang/sotk-php-client` is a PHP/Laravel package (PHP ^8.2, Laravel 11/12) that wraps the SOTK (Struktur Organisasi dan Tata Kerja) REST API for BKPSDM Karawang. It exposes the upstream API as Eloquent models and ships ready-to-mount Laravel routes/controllers that proxy the API for frontend consumption.

## Commands

```bash
composer install                    # install dependencies
vendor/bin/php-cs-fixer fix         # run linter (php-cs-fixer is a dev dep; no config file is committed, so it uses defaults)
```

There is no test suite in the repository. `composer.json` declares the `SotkClient\Tests\` autoload prefix and pulls in `laravel/framework` for dev, but no `tests/` directory or `phpunit.xml` exists — do not assume tests run.

## Architecture

### Two namespaces, one package

- `SotkClient\` → `src/` — framework-agnostic core (Guzzle, modules, models).
- `SotkClient\Laravel\` → `Laravel/` — Laravel integration (ServiceProvider, routes, controllers, facades, validation rules, config).

The Laravel side is optional sugar; the core is usable with any Guzzle client.

### ClientManager + Registrar traits (driver pattern)

`SotkClient\ClientManager` (`src/ClientManager.php`) extends `Illuminate\Support\Manager`. It composes four `Registrar` traits (`Skpd`, `Lokasi`, `Pendidikan`, `Jabatan`) under `src/Registrar/`, each contributing `createXxxDriver()` factory methods. Callers do:

```php
SotkClient::module('jabatan.pelaksana');  // → createJabatanPelaksanaDriver()
SotkClient::module('skpd.unit-kerja');    // → createSkpdUnitKerjaDriver()
```

The string passed to `module()` is studly-cased by Laravel's `Manager` to find the matching `create<Name>Driver` method. When adding a new endpoint:

1. Add a Module class under `src/Modules/<Group>/` extending `ModuleAbstract`, set `$endpoint` and `$model`.
2. Add a Model class under `src/Models/<Group>/` extending `Models\Base`.
3. Register a `create<Group><Name>Driver()` factory in the matching `src/Registrar/<Group>.php` trait.

### Module → Response → Model flow

`ModuleAbstract::getList()` / `getDetail()` (`src/Modules/ModuleAbstract.php`) call the upstream API via Guzzle, hand the PSR-7 response to `SotkClient\Response`, which unwraps the `data` envelope and hydrates the module's `$model` class. On non-2xx, it throws `Symfony\Component\HttpKernel\Exception\HttpException` with the upstream message — unless `$transform = false`, in which case the raw Guzzle response is returned untouched. The Laravel controllers always pass `$transform = false` so that the raw upstream JSON/status flows back to the HTTP client.

Mutating modules opt into `WriteTrait` (`src/Modules/WriteTrait.php`) for `create/update/delete`. Currently only `Pendidikan\{Sekolah, PerguruanTinggi, Jurusan}` are writable; the rest are read-only.

### Models are Eloquent models

`SotkClient\Models\Base` extends `Illuminate\Database\Eloquent\Model` and implements `Castable` + `Refreshable`. Models have no database table — they're hydrated from API JSON. Two custom casts in `src/Casts/` (`Model`, `Collection`) let one model embed another via `protected $casts = ['skpd' => Skpd::class]`; the cast JSON-encodes/decodes on the way in/out. `ReferensiJabatan` is a polymorphic cast used by `Models\Jabatan\Jabatan`. `fetchNew()` on any model re-fetches itself from the API via `getModule()->getDetail($this->id)`.

### Laravel integration

`Laravel/ServiceProvider.php` registers two singletons:
- `sotk.client` → `ClientManager` (facade: `SotkClient`)
- `sotk.route` → `Router` (facade: `SotkClientRoute`)

It also publishes `Laravel/config.php` to `config/sotk.php` and extends the Validator with rules `id_skpd`, `id_unit_kerja`, `id_jabatan`, `id_golongan`, `id_eselon` (each rule resolves by hitting the API's detail endpoint and catching exceptions — see `Laravel/Rules/*.php`).

Mount the proxy routes from a host app with `SotkClientRoute::routes()`, which delegates to `RouteRegistrar` and registers GET endpoints under the configured `prefix` (default `sotk`) and `middleware` (default `['auth']`). Each route maps to a controller under `Laravel/Http/Controllers/<Group>/` whose constructor resolves the right module from the facade and whose base `Controller::getList/getDetail` proxy through with `$transform = false`. `JabatanController` adds extra detail routes (`/kualifikasi`, `/kompetensi`, `/jabatan_atasan`, `/jabatan_bawahan`, `/ancestors`, `/descendants`) that call the Guzzle client directly.

### Configuration (env)

- `SOTK_CLIENT_URI` (default `https://api.sotk.bkpsdm.karawangkab.go.id`)
- `SOTK_CLIENT_ID`, `SOTK_CLIENT_SECRET` — sent as Guzzle HTTP Basic auth
- `sotk.prefix`, `sotk.middleware` — route group settings

Guzzle is constructed with `verify => false` (TLS verification disabled) in `ServiceProvider::createGuzzleClient()`.
