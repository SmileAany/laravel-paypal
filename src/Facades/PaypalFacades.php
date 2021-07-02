<?php

namespace Smbear\Paypal\Facades;

use Illuminate\Support\Facades\Facade;

class PaypalFacades extends Facade
{
    protected static function getFacadeAccessor():string
    {
        return 'paypal';
    }
}