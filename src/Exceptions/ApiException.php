<?php

namespace Smbear\Paypal\Exceptions;

class ApiException extends BaseException
{
    static public function handle(\Exception $exception): string
    {
        if (json_decode($exception->getMessage(),true) == null){
            return $exception->getMessage();
        }

        $message = json_decode($exception->getMessage(),true);

        if (isset($message['error_description']) && isset($message['error'])){
            return $message['error_description'];
        }

        if (isset($message['debug_id']) && isset($message['message'])){
            return $message['message'];
        }

        return $exception->getMessage();
    }
}