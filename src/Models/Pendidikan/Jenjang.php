<?php

namespace SotkClient\Models\Pendidikan;

use SotkClient\Cast\Pendidikan\JenjangCasting;
use SotkClient\Models\Model;

class Jenjang Extends Model
{
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'jurusan' => Jurusan::class.':children',
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
        return JenjangCasting::class;
    }
}
