<?php

namespace SlimJsend;

use Slim\Slim;

abstract class Config {
    const Cors = 'cors';
}

class Middleware extends \Slim\Middleware
{
    /**
     * @param array $config
     */
    function __construct($config = null)
    {
        $app = Slim::getInstance();

        // set the custom view
        $app->view(new View());

        // handle configuration
        $defaultConfig = array(
            Config::Cors => false,
        );
        if (\is_array($config)) {
            $config = array_merge($defaultConfig, $config);
        } else {
            $config = $defaultConfig;
        }
        $app->config($config);

        // handle errors
        $app->error(function (\Exception $e) use ($app) {
            $return = array(
                'error' =>
                    isset($func)
                    ? \call_user_func($func, $e)
                    : ($e->getCode() ? '' : '(#' . $e->getCode() . ') ') . $e->getMessage()
            );

            $app->render(500, $return);
        });

        // handle 404s
        $app->notFound(function() use ($app) {
            $return = array(
                'error' =>
                    isset($func)
                    ? \call_user_func($func, $app->request())
                    : '“' . $app->request()->getPath() . '” is not found.'
            );
            $app->render(404, $return);
        });

        $app->hook('slim.after.router', function () use ($app) {
            // will allow download request to flow
            if ($app->response()->header('Content-Type') === 'application/octet-stream') {
                return;
            }

            $cors = $app->config(Config::Cors);
            if ($cors) {
                if (\is_callable($cors)) {
                    $allowOrigin = \call_user_func($cors, $app->request()->headers->get('Origin'));
                } else {
                    if (!\is_string($cors)) {
                        $allowOrigin = '*';
                    } else {
                        $allowOrigin = $cors;
                    }
                }
                if ($allowOrigin) {
                    $app->response()->header('Access-Control-Allow-Origin', $allowOrigin);
                }
            }
        });
    }

    public function call()
    {
        $this->next->call();
    }

    static public function inject()
    {
        $args = \func_get_args();
        $app  = Slim::getInstance();

        $config = null;
        foreach ($args as $arg) {
            if ($arg instanceof Slim) {
                $app = $arg;
            }
            if (\is_array($arg)) {
                $config = $arg;
            }
        }

        $app->add(new \SlimJsend\Middleware($config));
    }
}