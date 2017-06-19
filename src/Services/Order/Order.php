<?php

namespace Deseco\Economic\Services\Order;

use Closure;
use Exception;
use Deseco\Economic\Contracts\ClientInterface as Client;

class Order
{
    /**
     * Client
     */
    protected $client;

    /**
     * \Deseco\Economic\Economic
     */
    protected $manager;

    /**
     * Constructor
     *
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client->getClient('soap');
        $this->manager = $client;
    }

    /**
     * Get Order handle by number
     *
     * @param  integer $number
     *
     * @return object
     */
    public function getHandle($number)
    {
        $result = $this->client->Order_FindByNumber([
            'number' => $number
        ])->Order_FindByNumberResult;

        if (! $result) {
            return false;
        }

        return $result;
    }

    /**
     * Get Orders from handles
     *
     * @param  object $handels
     *
     * @return object
     */
    public function getArrayFromHandles($handles)
    {
        return $this->client->Order_GetDataArray([
            'entityHandles' =>[
                'OrderHandle'=>$handles
            ]
        ])
        ->Order_GetDataArrayResult
        ->OrderData;
    }

    /**
     * Get all Orders
     *
     * @return array
     */
    public function all()
    {
        $handles = $this->client
            ->Order_GetAll()
            ->Order_GetAllResult
            ->OrderHandle;

        return $this->getArrayFromHandles($handles);
    }

    /**
     * Create new Order
     *
     * @param  integer  $debtorNumber
     * @param  Closure  $callback
     *
     * @return object
     */
    public function create($debtorNumber, Closure $callback, array $options = null)
    {
        $debtorHandle = $this->client->Debtor_FindByNumber([
            'number' => $debtorNumber
        ])->Debtor_FindByNumberResult;

        $orderHandle = $this->client->Order_Create([
            'debtorHandle' => $debtorHandle
        ])->Order_CreateResult;


        if (! $orderHandle->Id) {
            throw new Exception("Error: creating Invoice.");
        }

        if ($options) {
            $this->setOptions($orderHandle, $options);
        }

        $this->lines = new Line($this->manager, $orderHandle);

        call_user_func($callback, $this->lines);

        return $this->client->Order_GetDataArray([
            'entityHandles' => [
                'OrderHandle' => $orderHandle
            ]
        ])->Order_GetDataArrayResult;
    }

    /**
     * Set Order Option
     *
     * @param mixed $handle
     * @param array $options
     */
    public function setOptions($handle, array $options)
    {
        foreach ($options as $option => $value) {
            switch (strtolower($option)) {
                case 'vat':
                    $this->client
                        ->Order_SetIsVatIncluded(array(
                                'orderHandle' => $handle,
                                'value'       => $value
                        ));
                    break;
                case 'text1':
                    $this->client
                        ->Order_SetTextLine1(array(
                            'orderHandle' => $handle,
                            'value'       => $value
                        ));
                    break;
                case 'termsofdelivery':
                    $this->client
                        ->Order_SetTermsOfDelivery(array(
                            'orderHandle' => $handle,
                            'value'       => $value
                        ));
                    break;
                case 'deliveryaddress':
                    $this->client
                        ->Order_SetDeliveryAddress(array(
                            'orderHandle' => $handle,
                            'value'       => $value
                        ));
                    break;
                case 'deliverycity':
                    $this->client
                        ->Order_SetDeliveryCity(array(
                            'orderHandle' => $handle,
                            'value'       => $value
                        ));
                    break;
                case 'deliverycountry':
                    $this->client
                        ->Order_SetDeliveryCountry(array(
                            'orderHandle' => $handle,
                            'value'       => $value
                        ));
                    break;
                case 'deliverypostalcode':
                    $this->client
                        ->Order_SetDeliveryPostalCode(array(
                            'orderHandle' => $handle,
                            'value'       => $value
                        ));
                    break;
                case 'otherreference':
                    $this->client
                        ->Order_SetOtherReference(array(
                            'orderHandle' => $handle,
                            'value'       => $value
                        ));
                    break;
                case 'date':
                    $this->client
                        ->Order_SetDate(array(
                            'orderHandle' => $handle,
                            'value'       => $value
                        ));
                    break;
                case 'layout':
                    $this->client
                        ->Order_SetLayout(array(
                            'orderHandle' => $handle,
                            'value'       => $value
                        ));
                    break;
            }
        }
    }

    /**
     * Get lines of a specific Order
     * @param  integer $no
     * @return array
     */
    public function lines($no)
    {
        $handle = $this->getHandle($no);

        $lineHandles = $this->client
            ->Order_GetLines([
                'orderHandle' => $handle
            ])
            ->Order_GetLinesResult
            ->OrderLineHandle;

        $line = new Line($this->manager);

        return $line->getArrayFromHandles(is_array($lineHandles) ? $lineHandles : [$lineHandles]);
    }
}
