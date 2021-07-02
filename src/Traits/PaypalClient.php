<?php

namespace Smbear\Paypal\Traits;

use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Core\ProductionEnvironment;

trait PaypalClient
{
    /**
     * @var $client object 客户端
     */
    public $client;

    /**
     * @Notes:获取到客户端
     *
     * @param array $config
     * @param string $environment
     * @return object
     * @Author: smile
     * @Date: 2021/6/30
     * @Time: 19:45
     */
    public function client(array $config,string $environment) : object
    {
        if (is_null($this->client)){
            return new PayPalHttpClient($this->createEnvironment($config,$environment));
        }

        return $this->client;
    }

    /**
     * @Notes:创建环境数据
     *
     * @param array $config
     * @param string $environment
     * @return SandboxEnvironment|ProductionEnvironment
     * @Author: smile
     * @Date: 2021/6/30
     * @Time: 19:45
     */
    public function createEnvironment(array $config,string $environment)
    {
        if ($environment == 'sandbox'){
            return new SandboxEnvironment($config['client_id'],$config['client_secret']);
        } else {
            return new ProductionEnvironment($config['client_id'],$config['client_secret']);
        }
    }
}