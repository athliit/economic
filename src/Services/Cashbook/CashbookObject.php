<?php

namespace Deseco\Economic\Services\Cashbook;

use Deseco\Economic\Services\Object;

class CashbookObject extends Object
{
    protected $id1;
    protected $id2;
    protected $handle;

    /**
     * @param mixed $handle
     */
    public function setHandle($handle)
    {
        $this->handle = $handle;
    }
}
