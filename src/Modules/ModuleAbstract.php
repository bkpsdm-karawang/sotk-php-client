<?php

namespace SotkClient\Modules;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use InvalidArgumentException;
use SotkClient\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

abstract class ModuleAbstract implements ModuleContract
{
    /**
     * http client
     *
     * @var \GuzzleHttp\Client
     */
    public $client;

    /**
     * base endpoint of module
     *
     * @var string
     */
    protected $endpoint;

    /**
     * model of module
     *
     * @var \SotkClient\Models\Base
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
     * @param Client $client
     *
     * @return void
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->response = new Response($this->model);
    }

    /**
     * get client
     *
     * @return \GuzzleHttp\Client
     */
    public function getClient()
    {
        return $this->client;
    }
    /**
     * set endpoint
     * @param string $endpoint
     * @return ModuleAbstract
     */
    public function setEndpoint(string $endpoint): ModuleContract
    {
        $this->endpoint = $endpoint;

        return $this;
    }

    /**
     * with query string
     * @param string|array $relation
     * @return ModuleAbstract
     */
    public function with($relation = null): ModuleContract
    {
        if (empty($relation)) {
            return $this;
        }

        if (is_array($relation)) {
            $relation = implode(',', $relation);
        }

        if (isset($this->query['with'])) {
            $this->query['with'] .= ','.$relation;
        } else {
            $this->query['with'] = $relation;
        }

        return $this;
    }

    /**
     * search query string
     * @param string $keyword
     * @return ModuleAbstract
     */
    public function search(string $keyword)
    {
        $this->query['search'] = $keyword;

        return $this;
    }

    /**
     * get listing data.
     *
     * @param array $query
     * @param bool $transform
     * @return mixed
     */
    public function getList(array $query = [], bool $transform = true)
    {
        $response = null;

        try {
            $response = $this->client->get($this->endpoint, ['query' => $this->buildQuery($query)]);
        } catch (ClientException $error) {
            $response = $error->getResponse();
        }

        if (! is_null($response)) {
            if ($response->getStatusCode() === 200) {
                if ($transform) {
                    return $this->response->generateListing($response);
                }
            } else if ($transform) {
                $body = $response->getBody();
                $content = $body->getContents();
                $data = json_decode($content, true);
                $message = $data['message'] ?? '';
                throw new HttpException($response->getStatusCode(), 'Error SOTK : '.$message);
            }

            return $response;
        }

        throw new Exception('Request has no response');
    }

    /**
     * get detail data.
     *
     * @param mixed $identifier
     * @param array $query
     * @param bool $transform
     * @return mixed
     */
    public function getDetail($identifier, array $query = [], bool $transform = true)
    {
        $response = null;

        try {
            $response = $this->client->get("{$this->endpoint}/{$identifier}", ['query' => $this->buildQuery($query)]);
        } catch (ClientException $error) {
            $response = $error->getResponse();
        }

        if (! is_null($response)) {
            if ($response->getStatusCode() === 200) {
                if ($transform) {
                    return $this->response->generateDetail($response);
                }
            } else if ($transform) {
                $body = $response->getBody();
                $content = $body->getContents();
                $data = json_decode($content, true);
                $message = $data['message'] ?? '';
                throw new HttpException($response->getStatusCode(), 'Error SOTK : '.$message);
            }

            return $response;
        }

        throw new Exception('Request has no response');
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
