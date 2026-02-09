<?php

namespace SotkClient\Models\Lokasi;

use SotkClient\Models\Base;

class Alamat Extends Base
{
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'provinsi' => Provinsi::class,
        'kabupaten' => Kabupaten::class,
        'kecamatan' => Kecamatan::class,
        'desa' => Desa::class,
    ];
}
