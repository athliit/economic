<?php

namespace Deseco\Economic\Contracts;

interface ServiceInterface
{
    public function all();
    public function findBy($value, $finder);
}
