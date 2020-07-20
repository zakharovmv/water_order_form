<?php
namespace Lib;

use \Lib\Order;

/**
 * Simple controller for handle requests,
 * get lists data, set headers and output json
 */
class Controller
{
    private $route;
    private $requestMethod;
    private $dataDir = 'data/';

    /**
     * Create instance
     * @param $route route equal list name, one of ['regions', 'waterbases', 'order']
     * @param $requestMethod request method, one of ['POST', 'GET']
     */
    public function __construct($route, $requestMethod) 
    {
        $this->route = $route;
        $this->requestMethod = $requestMethod;
    }

    /**
     * Request handler
     */
    public function request() 
    {
        switch ($this->requestMethod) 
        {
            case 'GET':
                $response = $this->getData($this->route);
                break;
            case 'POST':
                $response = $this->createOrder();
                break;
            case 'OPTIONS':
                $response['status_code_header'] = 'HTTP/1.1 200 OK';
                $response['content'] = null;
                break;
            default:
                $response = $this->notFoundResponse();
                break;
        }

        header($response['status_code_header']);

        if ($response['content']) {
            echo $response['content'];
        }
    }

    /**
     * Get data by json files
     * @param string $route route equal list name, one of ['regions', 'waterbases', 'order']
     * @return string
     */
    private function getData($route) {
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['content'] = file_get_contents($this->dataDir . $route . '.json');
        return $response;        
    }

    /**
     * Create new water order
     */
    private function createOrder() 
    {
        $requestData = (array) json_decode(file_get_contents('php://input'), TRUE);

        $order = new Order($requestData);

        if(!$order->validate()) {
            $error = 'invalid order data';
            return $this->unprocessableEntityResponse($error);
        }

        $res = $order->save();

        if($res['status'] !== 'ok') {
            $error = $res['error'] || 'save order error';
            return $this->unprocessableEntityResponse($error);
        }

        $response['status_code_header'] = 'HTTP/1.1 201 Created';
        $response['content'] = json_encode([
            'status' => 'ok'
        ]);
        return $response;
    }

    /**
     * Set 404 status header if nothing to do
     */
    private function unprocessableEntityResponse($error)
    {
        $response['status_code_header'] = 'HTTP/1.1 422 Unprocessable Entity';
        $response['content'] = json_encode([
            'status' => 'error',
            'error' => $error
        ]);
        return $response;
    }

    /**
     * Set 404 status header if nothing to do
     */
    private function notFoundResponse()
    {
        $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
        $response['content'] = null;
        return $response;
    }
}