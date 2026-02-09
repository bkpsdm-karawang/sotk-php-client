<?php

namespace SotkClient\Registrar;

use SotkClient\Modules\Lokasi\Alamat;
use SotkClient\Modules\Lokasi\Desa;
use SotkClient\Modules\Lokasi\Kabupaten;
use SotkClient\Modules\Lokasi\Kecamatan;
use SotkClient\Modules\Lokasi\Provinsi;

trait Lokasi
{
    /**
     * Create an instance of the specified driver.
     *
     * @return \SotkClient\Modules\AbstractModule
     */
    protected function createLokasiAlamatDriver()
    {
        return new Alamat($this->client);
    }

    /**
     * Create an instance of the specified driver.
     *
     * @return \SotkClient\Modules\AbstractModule
     */
    protected function createLokasiProvinsiDriver()
    {
        return new Provinsi($this->client);
    }

    /**
     * Create an instance of the specified driver.
     *
     * @return \SotkClient\Modules\AbstractModule
     */
    protected function createLokasiDesaDriver()
    {
        return new Desa($this->client);
    }

    /**
     * Create an instance of the specified driver.
     *
     * @return \SotkClient\Modules\AbstractModule
     */
    protected function createLokasiKecamatanDriver()
    {
        return new Kecamatan($this->client);
    }

    /**
     * Create an instance of the specified driver.
     *
     * @return \SotkClient\Modules\AbstractModule
     */
    protected function createLokasiKabupatenDriver()
    {
        return new Kabupaten($this->client);
    }
}
