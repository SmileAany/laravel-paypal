<?php

namespace Smbear\Paypal;

use Smbear\Paypal\Traits\PaypalOrder;
use Smbear\Paypal\Traits\PaypalConfig;
use Smbear\Paypal\Services\PaypalService;

class Paypal
{
    use PaypalOrder ,PaypalConfig;

    public $paypalService;

    public function __construct()
    {
        $this->paypalService = new PaypalService();
    }

    /**
     * @Notes:初始化
     *
     * @throws Exceptions\ConfigException|Exceptions\MethodException
     * @return array
     * @Author: smile
     * @Date: 2021/6/30
     * @Time: 18:41
     */
    public function init(): array
    {
        $this->getConfig([
            'client_id',
            'client_secret',
            'return_url',
            'cancel_url'
        ]);

        $this->checkMethod([
            'setAmount'      => 'amount',
            'setReferenceId' => 'referenceId',
        ]);

        return $this->paypalService->init($this->config,$this->getParameters());
    }

    /**
     * @Notes:获取到订单的状态
     *
     * @param string $token
     * @return array
     * @throws Exceptions\ConfigException
     * @Author: smile
     * @Date: 2021/6/30
     * @Time: 20:41
     */
    public function status(string $token) : array
    {
        $this->getConfig([
            'client_id',
            'client_secret'
        ]);

        return $this->paypalService->status($this->config,$token);
    }
}