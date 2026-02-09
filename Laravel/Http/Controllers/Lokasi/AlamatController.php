<?php

namespace SotkClient\Laravel\Http\Controllers\Lokasi;

use SotkClient\Laravel\Facades\SotkClient;
use SotkClient\Laravel\Http\Controllers\Controller;

class AlamatController extends Controller
{
    /**
     * constructor
     *
     */
    public function __construct(SotkClient $manager)
    {
        $this->client = $manager::module('lokasi-alamat');
    }
}
