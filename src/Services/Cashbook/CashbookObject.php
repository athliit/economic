<?php

namespace Athliit\Economic\Services\Cashbook;

use Athliit\Economic\Services\Object;

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
