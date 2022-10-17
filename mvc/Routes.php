<?php

namespace Jorge\ReabastecimentoDoCartao;


class Routes
{
    private const ROUTE = [
        '/api/cartao' => 'Jorge\\ReabastecimentoDoCartao\\Controller\\CartaoController',
        '/api/viagem' => 'Jorge\\ReabastecimentoDoCartao\\Controller\\ViagemController',
        '/api/recarga' => 'Jorge\\ReabastecimentoDoCartao\\Controller\\RecargaController',
        '/api/onibus' => 'Jorge\\ReabastecimentoDoCartao\\Controller\\OnibusController',
        //'/' => '/mvc/View/index.html'
        //'/api/info' => 'Jorge\\ReabastecimentoDoCartao\\Controller\\InfoController'
    ];

    public static function isRequestApi($uri)
    {
        return strpos($uri, '/api') === 0;
    }

    public static function init()
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        $controller = static::ROUTE[$uri];

        if ((!static::isRequestApi($uri) || !class_exists($controller))) {
            header('Location: /');
            die;
        }

        if (!in_array($uri, ['/'])) {
            return new $controller();
        }
    }
}
