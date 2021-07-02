<?php

namespace Smbear\Paypal\Traits;

use Smbear\Paypal\Exceptions\ParamsException;
use Smbear\Paypal\Exceptions\MethodException;

trait PaypalOrder
{
    /**
     * @var array $amount 订单金额
     */
    public $amount;

    /**
     * @var string $referenceId 订单编号
     */
    public $referenceId;

    /**
     * @Notes:返回所有的参数数据
     *
     * @Author: smile
     * @Date: 2021/6/30
     * @Time: 20:20
     */
    public function getParameters(): array
    {
        return [
            'amount'      => $this->amount,
            'referenceId' => $this->referenceId
        ];
    }

    /**
     * @Notes:核实方法是否被调用
     *
     * @param array $parameters
     * @throws MethodException
     * @Author: smile
     * @Date: 2021/6/30
     * @Time: 18:28
     */
    public function checkMethod(array $parameters = [])
    {
        foreach ($parameters as $method => $attribute){
            if (is_null($this->$attribute)){
                throw new MethodException($method .'method is not call');
            }
        }
    }

    /**
     * @Notes:设置金额
     *
     * @param int $amount
     * @param string $currencyCode
     * @return self
     * @throws ParamsException
     * @Author: smile
     * @Date: 2021/5/13
     * @Time: 15:40
     */
    public function setAmount(int $amount,string $currencyCode) : self
    {
        if (empty($amount) || empty($currencyCode)) throw new ParamsException(__METHOD__.'参数异常');

        $this->amount = [
            'amount'       => $amount,
            'currencyCode' => $currencyCode
        ];

        return $this;
    }

    /**
     * @Notes:设置编号
     *
     * @param string $referenceId
     * @return self
     * @throws ParamsException
     * @Author: smile
     * @Date: 2021/5/13
     * @Time: 15:40
     */
    public function setReferenceId(string $referenceId) : self
    {
        if (empty($referenceId)) throw new ParamsException(__METHOD__.'参数异常');

        $this->referenceId = $referenceId;

        return $this;
    }
}