<?php

namespace SotkClient;

use GuzzleHttp\ClientInterface;
use Illuminate\Support\Manager;
use InvalidArgumentException;
use SotkClient\Registrar\Jabatan;
use SotkClient\Registrar\Lokasi;
use SotkClient\Registrar\Pendidikan;
use SotkClient\Registrar\Skpd;

class ClientManager extends Manager
{
    use Lokasi;
    use Pendidikan;
    use Skpd;
    use Jabatan;

    /**
     * guzzle client interface
     *
     * @var ClientInterface
     */
    protected $client;

    /**
     * constructor
     *
     * @param ClientInterface $client
     *
     * @return void
     */
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * ceate module
     *
     * @param string $name
     * @return \SotkClient\Modules\ModuleContract
     */
    public function module(string $name)
    {
        return $this->driver($name);
    }

    /**
     * Get the default driver name.
     *
     * @return string
     *
     * @throws \InvalidArgumentException
     */
    public function getDefaultDriver()
    {
        throw new InvalidArgumentException('No sotk client driver was specified.');
    }
}
