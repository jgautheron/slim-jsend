<?php

namespace SlimJsend;

use Slim\Slim;
use JSend\JSendResponse;

class View extends \Slim\View
{
    /**
    * @param int|string $status
    * @param array|null $data
    * @return void
    */
    public function render($status, $data = null)
    {
        $app = Slim::getInstance();
        $response = $this->all();
        $status = \intval($status);
        $app->response()->status($status);

        if (isset($response['flash']) && \is_object($response['flash'])) {
            $flash = $this->data->flash->getMessages();
            if (count($flash)) {
                $response['flash'] = $flash;
            } else {
                unset($response['flash']);
            }
        }

        switch($status) {
            case 200:
                $responseType = 'success';
                break;
            case 500:
                $responseType = 'fail';
                break;
            default:
                $responseType = 'error';
                $response = $response['error'];
        }

        $app->response()->header('Content-Type', 'application/json');
        $app->response()->body(JSendResponse::$responseType($response));
        //echo JSendResponse::$responseType($response);
    }
}