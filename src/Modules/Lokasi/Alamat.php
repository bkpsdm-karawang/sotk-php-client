<?php

namespace SotkClient\Modules\Lokasi;

use SotkClient\Modules\ModuleAbstract;

class Alamat extends ModuleAbstract
{
    /**
     * base endpoint of module
     *
     * @var string
     */
    protected $endpoint = '/alamat';

    /**
     * model of module
     *
     * @var \SotkClient\Models\Base
     */
    protected $model = \SotkClient\Models\Lokasi\Alamat::class;
}
