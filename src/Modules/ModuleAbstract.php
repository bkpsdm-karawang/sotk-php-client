<?php

namespace SotkClient\Modules;

use Exception;
use GuzzleHttp\ClientInterface;
use InvalidArgumentException;
use Illuminate\Support\Collection;
use SotkClient\Models\Model;
use SotkClient\Response;

abstract class ModuleAbstract implements ModuleContract
{
    /**
     * base endpoint of module
     *
     * @var string
     */
    protected $endpoint;

    /**
     * model of module
     *
     * @var \SotkClient\Models\Model
     */
    protected $model;

    /**
     * response response
     *
     * @var \SotkClient\Response
     */
    protected $response;

    /**
     * query params
     *
     * @var array
     */
    protected $query = [];

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
        $this->response = new Response(new $this->model());
    }

    /**
     * with query string
     * @param string $relation
     * @return ModuleAbstract
     */
    public function with(string $relation)
    {
        if (isset($this->query['with'])) {
            $this->query['with'] .= ','.$relation;
        } else {
            $this->query['with'] = $relation;
        }

        return $this;
    }

    /**
     * get listing data.
     *
     * @param array $query
     * @return \Illuminate\Support\Collection
     * @throws Exception
     */
    public function getList(array $query = []) : Collection
    {
        try {
            $response = $this->client->get($this->endpoint, ['query' => $this->buildQuery($query)]);

            if ($response->getStatusCode() === 200) {
                return $this->response->generateListing($response);
            }

            throw new Exception('Server not response with status code 200');
        } catch (Exception $error) {
            throw new Exception($error->getMessage());
        }
    }

    /**
     * get detail data.
     *
     * @param mixed $identifier
     * @return \SotkClient\Models\Model
     */
    public function getDetail($identifier) : Model
    {
        try {
            $response = $this->client->get("{$this->endpoint}/{$identifier}", ['query' => $this->buildQuery()]);

            if ($response->getStatusCode() === 200) {
                return $this->response->generateDetail($response);
            }

            throw new Exception('Server not response with status code 200');
        } catch (Exception $error) {
            throw new Exception($error->getMessage());
        }
    }

    /**
     * build query params.
     *
     * @param array $query
     * @return array
     */
    protected function buildQuery(array $query = []) : array
    {
        return array_merge_recursive($this->query, $query);
    }

    /**
     * proxy to client.
     *
     * @param array $arguments
     * @return ReponseContract
     */
    public function __call($name, array $arguments)
    {
        if (method_exists($this->client, $name)) {
            return call_user_func_array([$this->client, $name], $arguments);
        }

        throw new InvalidArgumentException("{$name} is not defined method");
    }
}