<?php

namespace Athliit\Economic\Services;

abstract class RestService
{
    public function get()
    {
        $result = call_user_func_array([$this->client, 'get'], func_get_args());

        return $this->fetchResults($result);
    }

    public function post()
    {
        $result = call_user_func_array([$this->client, 'post'], func_get_args());

        return $this->fetchResults($result);
    }

    public function findBy($value, $finder)
    {
        $method = 'findBy' . ucfirst($finder);

        if (! method_exists($this, $method)) {
            throw new Exception('Method does not exists');
        }

        return $this->{$method}($value);
    }

    public function getCollection($result)
    {
        return $result->collection;
    }

    protected function fetchResults($result)
    {
        $result = json_decode($result->getBody());

        if (property_exists($result, 'collection')) {
            return $this->getCollection($result);
        }

        return $result;
    }

    public function first($result)
    {
        return $result[0];
    }

    public function last($result)
    {
        return end($result);
    }

    public function exists($result)
    {
        if (is_array($result) && ! count($result)) {
            return false;
        }

        return true;
    }
}
