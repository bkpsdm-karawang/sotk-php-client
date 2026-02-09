<?php

namespace SotkClient\Laravel;

class RouteRegistrar
{
    /**
     * The router implementation.
     *
     * @var \Illuminate\Contracts\Routing\Registrar
     */
    protected $router;

    /**
     * Create a new route registrar instance.
     *
     * @param  \Illuminate\Contracts\Routing\Registrar  $router
     * @return void
     */
    public function __construct($router)
    {
        $this->router = $router;
    }

    /**
     * Register routes for transient tokens, clients, and personal access tokens.
     * @return void
     */
    public function all()
    {
        $this->moduleLokasi();
        $this->modulePendidikan();
        $this->moduleSkpd();
        $this->moduleJabatan();
    }

    /**
     * create group route
     */
    protected function createGroup($prefix, $middleware = null): array
    {
        $group = ['prefix' => $prefix];

        if (!is_null($middleware)) {
            $group['middleware'] = $middleware;
        }

        return $group;
    }

    /**
     * Register the routes for module lokasi.
     *
     * @param mixed $middleware string|array
     * @return void
     */
    public function moduleLokasi($middleware = null, $name = 'sotk')
    {
        $this->router->group($this->createGroup('lokasi', $middleware), function ($router) use ($name) {
            $router->get('/alamat', ['uses' => 'Lokasi\AlamatController@getList', 'as' => $name . '.lokasi.alamat.list']);
            $router->get('/alamat/{id}', ['uses' => 'Lokasi\AlamatController@getDetail', 'as' => $name . '.lokasi.alamat.detail']);
            $router->get('/provinsi', ['uses' => 'Lokasi\ProvinsiController@getList', 'as' => $name . '.lokasi.provinsi.list']);
            $router->get('/provinsi/{id}', ['uses' => 'Lokasi\ProvinsiController@getDetail', 'as' => $name . '.lokasi.provinsi.detail']);
            $router->get('/kabupaten', ['uses' => 'Lokasi\KabupatenController@getList', 'as' => $name . '.lokasi.kabupaten.list']);
            $router->get('/kabupaten/{id}', ['uses' => 'Lokasi\KabupatenController@getDetail', 'as' => $name . '.lokasi.kabupaten.detail']);
            $router->get('/kecamatan', ['uses' => 'Lokasi\KecamatanController@getList', 'as' => $name . '.lokasi.kecamatan.list']);
            $router->get('/kecamatan/{id}', ['uses' => 'Lokasi\KecamatanController@getDetail', 'as' => $name . '.lokasi.kecamatan.detail']);
            $router->get('/desa', ['uses' => 'Lokasi\DesaController@getList', 'as' => $name . '.lokasi.desa.list']);
            $router->get('/desa/{id}', ['uses' => 'Lokasi\DesaController@getDetail', 'as' => $name . '.lokasi.desa.detail']);
        });
    }

    /**
     * Register the routes for module pendidikan.
     *
     * @param mixed $middleware string|array
     * @return void
     */
    public function modulePendidikan($middleware = null, $name = 'sotk')
    {
        $this->router->group($this->createGroup('pendidikan', $middleware), function ($router) use ($name) {
            $router->get('/tingkat', ['uses' => 'Pendidikan\TingkatController@getList', 'as' => $name . '.pendidikan.tingkat.list']);
            $router->get('/tingkat/{id}', ['uses' => 'Pendidikan\TingkatController@getDetail', 'as' => $name . '.pendidikan.tingkat.detail']);
            $router->get('/satuan', ['uses' => 'Pendidikan\SatuanController@getList', 'as' => $name . '.pendidikan.satuan.list']);
            $router->get('/satuan/{id}', ['uses' => 'Pendidikan\SatuanController@getDetail', 'as' => $name . '.pendidikan.satuan.detail']);
            $router->get('/jurusan', ['uses' => 'Pendidikan\JurusanController@getList', 'as' => $name . '.pendidikan.jurusan.list']);
            $router->get('/jurusan/{id}', ['uses' => 'Pendidikan\JurusanController@getDetail', 'as' => $name . '.pendidikan.jurusan.detail']);
            $router->get('/lembaga', ['uses' => 'Pendidikan\LembagaController@getList', 'as' => $name . '.pendidikan.lembaga.list']);
            $router->get('/lembaga/{id}', ['uses' => 'Pendidikan\LembagaController@getDetail', 'as' => $name . '.pendidikan.lembaga.detail']);
            $router->get('/sekolah', ['uses' => 'Pendidikan\SekolahController@getList', 'as' => $name . '.pendidikan.sekolah.list']);
            $router->get('/sekolah/{id}', ['uses' => 'Pendidikan\SekolahController@getDetail', 'as' => $name . '.pendidikan.sekolah.detail']);
            $router->get('/perguruan-tinggi', ['uses' => 'Pendidikan\PerguruanTinggiController@getList', 'as' => $name . '.pendidikan.perguruan-tinggi.list']);
            $router->get('/perguruan-tinggi/{id}', ['uses' => 'Pendidikan\PerguruanTinggiController@getDetail', 'as' => $name . '.pendidikan.perguruan-tinggi.detail']);
        });
    }

    /**
     * Register the routes for module skpd.
     *
     * @param mixed $middleware string|array
     * @return void
     */
    public function moduleSkpd($middleware = null, $name = 'sotk')
    {
        $this->router->group($this->createGroup('skpd', $middleware), function ($router) use ($name) {
            $router->get('/unit-kerja', ['uses' => 'Skpd\UnitKerjaController@getList', 'as' => $name . '.skpd.unit-kerja.list']);
            $router->get('/unit-kerja/{id}', ['uses' => 'Skpd\UnitKerjaController@getDetail', 'as' => $name . '.skpd.unit-kerja.detail']);
            $router->get('/kantor-skpd', ['uses' => 'Skpd\KantorSkpdController@getList', 'as' => $name . '.skpd.kantor-skpd.list']);
            $router->get('/kantor-skpd/{id}', ['uses' => 'Skpd\KantorSkpdController@getDetail', 'as' => $name . '.skpd.kantor-skpd.detail']);
            $router->get('/', ['uses' => 'Skpd\SkpdController@getList', 'as' => $name . '.skpd.list']);
            $router->get('/urusan', ['uses' => 'Skpd\UrusanController@getList', 'as' => $name . '.skpd.urusan']);
            $router->get('/{id}', ['uses' => 'Skpd\SkpdController@getDetail', 'as' => $name . '.skpd.detail']);
        });
    }
    /**
     * Register the routes for module jabatan.
     *
     * @param mixed $middleware string|array
     * @return void
     */
    public function moduleJabatan($middleware = null, $name = 'sotk')
    {
        $this->router->group($this->createGroup('jabatan', $middleware), function ($router) use ($name) {
            $router->get('/politik', ['uses' => 'Jabatan\JabatanPolitikController@getList', 'as' => $name . '.jabatan.politik.list']);
            $router->get('/politik/{id}', ['uses' => 'Jabatan\JabatanPolitikController@getDetail', 'as' => $name . '.jabatan.politik.detail']);
            $router->get('/struktural', ['uses' => 'Jabatan\JabatanStrukturalController@getList', 'as' => $name . '.jabatan.struktural.list']);
            $router->get('/struktural/{id}', ['uses' => 'Jabatan\JabatanStrukturalController@getDetail', 'as' => $name . '.jabatan.struktural.detail']);

            $router->group(['prefix' => 'fungsional'], function ($router) use ($name) {
                $router->get('/jenis', ['uses' => 'Jabatan\JabatanFungsionalJenisController@getList', 'as' => $name . '.jabatan.jenis.fungsional.list']);
                $router->get('/jenis/{id}', ['uses' => 'Jabatan\JabatanFungsionalJenisController@getDetail', 'as' => $name . '.jabatan.jenis.fungsional.detail']);
                $router->get('/', ['uses' => 'Jabatan\JabatanFungsionalController@getList', 'as' => $name . '.jabatan.fungsional.list']);
                $router->get('/{id}', ['uses' => 'Jabatan\JabatanFungsionalController@getDetail', 'as' => $name . '.jabatan.fungsional.detail']);
            });

            $router->group(['prefix' => 'pelaksana'], function ($router) use ($name) {
                $router->get('/jenis', ['uses' => 'Jabatan\JabatanPelaksanaJenisController@getList', 'as' => $name . '.jabatan.jenis.pelaksana.list']);
                $router->get('/jenis/{id}', ['uses' => 'Jabatan\JabatanPelaksanaJenisController@getDetail', 'as' => $name . '.jabatan.jenis.pelaksana.detail']);
                $router->get('/', ['uses' => 'Jabatan\JabatanPelaksanaController@getList', 'as' => $name . '.jabatan.pelaksana.list']);
                $router->get('/{id}', ['uses' => 'Jabatan\JabatanPelaksanaController@getDetail', 'as' => $name . '.jabatan.pelaksana.detail']);
            });

            $router->get('/tugas-tambahan', ['uses' => 'Jabatan\JabatanTugasTambahanController@getList', 'as' => $name . '.jabatan.tugas-tambahan.list']);
            $router->get('/tugas-tambahan/{id}', ['uses' => 'Jabatan\JabatanTugasTambahanController@getDetail', 'as' => $name . '.jabatan.tugas-tambahan.detail']);

            $router->get('/tingkat', ['uses' => 'Jabatan\TingkatController@getList', 'as' => $name . '.jabatan.tingkat.list']);
            $router->get('/tingkat/{id}', ['uses' => 'Jabatan\TingkatController@getDetail', 'as' => $name . '.jabatan.tingkat.detail']);

            $router->get('/eselon', ['uses' => 'Jabatan\EselonController@getList', 'as' => $name . '.jabatan.eselon.list']);
            $router->get('/eselon/{id}', ['uses' => 'Jabatan\EselonController@getDetail', 'as' => $name . '.jabatan.eselon.detail']);

            $router->get('/golongan', ['uses' => 'Jabatan\GolonganController@getList', 'as' => $name . '.jabatan.golongan.list']);
            $router->get('/golongan/{id}', ['uses' => 'Jabatan\GolonganController@getDetail', 'as' => $name . '.jabatan.golongan.detail']);

            $router->get('/kelas', ['uses' => 'Jabatan\KelasController@getList', 'as' => $name . '.jabatan.kelas.list']);
            $router->get('/kelas/{id}', ['uses' => 'Jabatan\KelasController@getDetail', 'as' => $name . '.jabatan.kelas.detail']);

            $router->get('/kompetensi', ['uses' => 'Jabatan\KompetensiController@getList', 'as' => $name . '.jabatan.kompetensi.list']);
            $router->get('/kompetensi/{id}', ['uses' => 'Jabatan\KompetensiController@getDetail', 'as' => $name . '.jabatan.kompetensi.detail']);
            $router->get('/kompetensi-group', ['uses' => 'Jabatan\KompetensiGroupController@getList', 'as' => $name . '.jabatan.kompetensi-group.list']);
            $router->get('/kompetensi-group/{id}', ['uses' => 'Jabatan\KompetensiGroupController@getDetail', 'as' => $name . '.jabatan.kompetensi-group.detail']);

            $router->get('/', ['uses' => 'Jabatan\JabatanController@getList', 'as' => $name . '.jabatan.list']);
            $router->get('/{id}', ['uses' => 'Jabatan\JabatanController@getDetail', 'as' => $name . '.jabatan.detail']);
            $router->get('/{id}/kualifikasi', ['uses' => 'Jabatan\JabatanController@getKualifikasi', 'as' => $name . '.jabatan.detail.kualifikasi']);
            $router->get('/{id}/kompetensi', ['uses' => 'Jabatan\JabatanController@getKompetensi', 'as' => $name . '.jabatan.detail.kompetensi']);
            $router->get('/{id}/jabatan_atasan', ['uses' => 'Jabatan\JabatanController@getJabatanAtasan', 'as' => $name . '.jabatan.detail.jabatan_atasan']);
            $router->get('/{id}/jabatan_bawahan', ['uses' => 'Jabatan\JabatanController@getJabatanBawahan', 'as' => $name . '.jabatan.detail.jabatan_bawahan']);
            $router->get('/{id}/jabatan_bawahan_group', ['uses' => 'Jabatan\JabatanController@getJabatanBawahanGroup', 'as' => $name . '.jabatan.detail.jabatan_bawahan_group']);
            $router->get('/{id}/ancestors', ['uses' => 'Jabatan\JabatanController@getAncestors', 'as' => $name . '.jabatan.detail.ancestors']);
            $router->get('/{id}/descendants', ['uses' => 'Jabatan\JabatanController@getDescendants', 'as' => $name . '.jabatan.detail.descendants']);
        });
    }
}
