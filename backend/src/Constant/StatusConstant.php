<?php

namespace App\Constant;

abstract class StatusConstant extends ResponseConstant
{
    static $ACTIVE = "active";

	static $INACTIVE = "inactive";
	
    static $ORDERS_FINISHED = "orders finished";
	
    static $DATE_FINISHED = "date finished";
	
    static $UNACCEPT = "unaccept";
}