<?php

namespace Smbear\Paypal\Traits;

use Illuminate\Support\Facades\Log;
use Smbear\Paypal\Exceptions\ConfigException;

trait PaypalConfig
{
    /**
     * @var array 模型下的配置文件
     */
    public $config;

    /**
     * @var string 是生产模型还是沙盒模型
     */
    public $environment = 'sandbox';

    /**
     * @Notes:设置 environment
     *
     * @param string $environment
     * @Author: smile
     * @Date: 2021/6/8
     * @Time: 18:53
     */
    public function setEnvironment(string $environment = '')
    {
        $this->environment = $environment ?: config('payeezy.environment');
    }

    /**
     * @Notes:获取到指定模型的配置数据
     *
     * @param array $dependencies
     * @Author: smile
     * @Date: 2021/6/30
     * @Time: 17:58
     * @throws ConfigException
     */
    public function getConfig(array $dependencies)
    {
        if (is_null($this->config)){
            $environment = $this->environment;

            array_map(function ($item) use ($environment) {
                if (empty(config('paypal.'.$environment.'.'.$item))){
                    throw new ConfigException('paypal '. $environment .'.'.$item.' 参数为空');
                }
            }, $dependencies);

            $this->config = config('paypal.'.$environment);
        }
    }
}