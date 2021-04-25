<?php

namespace App\Request;

class DeliveryCompanyPackageUpdateStateRequest
{
    private $id;

    private $status;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }
}
