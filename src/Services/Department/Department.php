<?php

namespace Athliit\Economic\Services\Department;

use Athliit\Economic\Contracts\ClientInterface as Client;
use Athliit\Economic\Contracts\ServiceInterface;
use Athliit\Economic\Services\RestService;

/**
 * Class Department
 * @package Athliit\Economic\Services\Department
 */
class Department extends RestService implements ServiceInterface
{
    /**
     * @var
     */
    protected $client;

    /**
     * @var \Athliit\Economic\Contracts\ClientInterface
     */
    protected $manager;

    /**
     * Department constructor.
     *
     * @param \Athliit\Economic\Contracts\ClientInterface $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client->getClient('rest');
        $this->manager = $client;
    }

    /**
     * @param int $limit
     *
     * @return bool|mixed
     */
    public function all($limit = 1000)
    {
        $result = $this->get('departments?pageSize=' . $limit);

        if (! $this->exists($result)) {
            return false;
        }

        return $result;
    }

    /**
     * @param $name
     *
     * @return bool|mixed
     */
    public function findByName($name)
    {
        $result = $this->get('departments?filter=name$like:' . preg_replace('/[()*$,\[\]]/', '', $name));

        if (! $this->exists($result)) {
            return false;
        }

        return $this->first($result);
    }
}
