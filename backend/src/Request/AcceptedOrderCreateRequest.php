<?php

namespace App\Request;

class AcceptedOrderCreateRequest
{
    private $orderID;
    private $captainID;
    private $acceptedOrderDate;
    private $state;

    /**
     * @param mixed $orderID
     */
    public function setOrderID($orderID): void
    {
        $this->orderID = $orderID;
    }

    /**
     * @return mixed
     */
    public function getOrderID()
    {
        return $this->orderID;
    }

    /**
     * @param mixed $captainID
     */
    public function setCaptainID($captainID): void
    {
        $this->captainID = $captainID;
    }

    /**
     * @return mixed
     */
    public function getCaptainID()
    {
        return $this->captainID;
    }
    
    /**
     * @return mixed
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param mixed $state
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }
}
