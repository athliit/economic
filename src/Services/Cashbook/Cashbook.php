<?php


namespace Athliit\Economic\Services\Cashbook;

use Athliit\Economic\Services\Object;
use Athliit\Economic\Contracts\ClientInterface as Client;


class Cashbook extends Object
{
    protected $id1;
    protected $id2;

    protected $handle;

    /**
     * Client
     */
    protected $client;

    /**
     * \Athliit\Economic\Economic
     */
    protected $manager;

    /**
     * Order handle for creating or editing lines
     *
     * @var object
     */
    protected $orderHandle;

    /**
     * Constructor
     *
     * @param Client $client
     * @param mixed $orderHandle
     */
    public function __construct(Client $client)
    {
        $this->client = $client->getClient('soap');
        $this->manager = $client;
    }

    /**
     * @param mixed $handle
     */
    public function setHandle($handle)
    {
        $this->handle = $handle;
    }

    public function createCashBookEntry(array $parameters)
    {
        $response = $this->client->CashBookEntry_CreateFromDataArray($parameters)
            ->CashBookEntry_CreateFromDataArrayResult
            ->CashBookEntryHandle;

        return $response;
    }

    public function getCashbookEntry($number)
    {
        $response = $this->client->CashBookEntry_GetData([
            'entityHandle' => [
                'Id1' => 1,
                'Id2' => $number,
            ]
        ])
        ->CashBookEntry_GetDataResult;

        return $response;
    }

    public function update($entry)
    {
        $response = $this->client->CashBookEntry_UpdateFromData([
            'data' => $entry
        ])
        ->CashBookEntry_UpdateFromDataResult;

        return $response;
    }

    public function getAll()
    {
        $response = $this->client->CashBook_GetAll()->CashBook_GetAllResult;

        return $response;
    }
}
