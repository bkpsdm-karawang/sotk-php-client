<?php

namespace SotkClient\Models\Jabatan;

use Illuminate\Contracts\Database\Eloquent\Castable;
use SotkClient\Cast\Jabatan\JabatanPelaksanaCasting;
use SotkClient\Models\Model;

class JabatanPelaksana Extends Model implements Castable, ReferensiJabatanContract
{
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'jenis_jabatan_pelaksana' => JabatanPelaksanaJenis::class
    ];

    /**
     * Get the name of the caster class to use when casting from / to this cast target.
     *
     * @param  array  $arguments
     * @return string
     * @return string|\Illuminate\Contracts\Database\Eloquent\CastsAttributes|\Illuminate\Contracts\Database\Eloquent\CastsInboundAttributes
     */
    public static function castUsing(array $arguments)
    {
        return JabatanPelaksanaCasting::class;
    }
}
