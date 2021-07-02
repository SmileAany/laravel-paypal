<?php

namespace Smbear\Paypal\Services;

use Illuminate\Support\Facades\Log;
use Smbear\Paypal\Traits\PaypalClient;
use Smbear\Paypal\Exceptions\ApiException;
use PayPalCheckoutSdk\Orders\OrdersGetRequest;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;

class PaypalService
{
    use PaypalClient;

    /**
     * @var array $config 配置文件
     */
    public $config;

    /**
     * @var array $parameters 参数
     */
    public $parameters;

    /**
     * @Notes:构建请求的参数
     *
     * @return array
     * @Author: smile
     * @Date: 2021/6/30
     * @Time: 20:10
     */
    public function build() : array
    {
        return [
            "intent" => "CAPTURE",
            "purchase_units" => [[
                "reference_id" => $this->parameters['referenceId'],
                "amount" => [
                    "value"         => (string) $this->parameters['amount']['amount'],
                    "currency_code" => $this->parameters['amount']['currencyCode']
                ]
            ]],
            "application_context" => [
                "cancel_url" => $this->config['cancel_url'],
                "return_url" => $this->config['return_url']
            ]
        ];
    }

    /**
     * @Notes:初始化
     *
     * @param array $config
     * @param array $parameters
     * @return array
     * @Author: smile
     * @Date: 2021/6/30
     * @Time: 20:39
     */
    public function init(array $config,array $parameters) : array
    {
        $this->config      = $config;
        $this->parameters  = $parameters;

        $request = new OrdersCreateRequest();
        $request->prefer('return=representation');

        $request->body = $this->build();

        try{
            $response = $this->client($this->config,config('paypal.environment'))
                ->execute($request);

            if ($response->statusCode == 201) {
                if (isset($response->result->links,$response->result->id) && !empty($response->result->links) && !empty($response->result->id)){
                    $result = array_filter($response->result->links,function ($item){
                        return strtolower($item->rel) == 'approve';
                    });

                    if (!empty($result = current($result))){
                        return paypal_return_success('success',[
                            'url'    => $result->href,
                            'method' => $result->method
                        ]);
                    }
                }
            }

            Log::channel(config('paypal.channel') ?: 'local')
                ->info('初始化 response 数据异常:'.json_encode($response->result, JSON_PRETTY_PRINT));

            return paypal_return_error('error');
        }catch (\Exception $exception){
            report($exception);

            return paypal_return_error(ApiException::handle($exception));
        }
    }

    /**
     * @Notes:获取到支付的状态
     *
     * @param array $config
     * @param string $token
     * @return array
     * @Author: smile
     * @Date: 2021/6/30
     * @Time: 20:45
     */
    public function status(array $config,string $token) : array
    {
        $this->config = $config;

        $request = new OrdersGetRequest($token);

        try{
            $response = $this->client($this->config,config('paypal.environment'))
                ->execute($request);

            if ($response->statusCode == 200){
                if ($response->result->status == 'COMPLETED'){
                    return paypal_return_success('success');
                } else if ($response->result->status == 'APPROVED'){
                    return $this->captureOrder($token);
                }
            }

            Log::channel(config('paypal.channel') ?: 'local')
                ->info('状态 response 数据异常:'.json_encode($response->result, JSON_PRETTY_PRINT));

            return paypal_return_error('error');
        }catch (\Exception $exception){
            report($exception);

            return paypal_return_error(ApiException::handle($exception));
        }
    }

    /**
     * @Notes:支付审核
     *
     * @param string $token
     * @return array
     * @Author: smile
     * @Date: 2021/6/30
     * @Time: 21:10
     */
    public function captureOrder(string $token) : array
    {
        $request = new OrdersCaptureRequest($token);
        $request->prefer('return=minimal');

        try{
            $response = $this->client($this->config,config('paypal.environment'))
                ->execute($request);

            if ($response->statusCode == 201 && $response->result->status == 'COMPLETED'){
                return paypal_return_success('success');
            }

            Log::channel(config('paypal.channel') ?: 'local')
                ->info('捕获 response 数据异常:'.json_encode($response->result, JSON_PRETTY_PRINT));

            return paypal_return_error('error');
        }catch (\Exception $exception){
            report($exception);

            return paypal_return_error(ApiException::handle($exception));
        }
    }
}