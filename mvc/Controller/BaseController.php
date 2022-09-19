<?php

namespace Jorge\ReabastecimentoDoCartao\Controller;


class BaseController
{
    protected const STATUS_ERROR = 500;
    protected const STATUS_CREATED = 202;
    protected const STATUS_UPDATED = 201;
    /**
     * Define os metodos para cada verbo HTTP
     */
    public static function init($class)
    {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $methods = [
            'GET' => 'index',
            'POST' => 'create',
            'PUT' => 'update',
            'DELETE' => 'destroy'
        ];
        $method = $methods[$requestMethod];
        if ($method) {
            return (new $class)->$method();
        }
    }

    /**
     * Transforma a resposta em json
     */
    protected function jsonResponse($data = null, int $status = 200): void
    {
        if ($data != null) {
            header('Content-Type: application/json');
            echo json_encode($data);
        }
        http_response_code($status);
    }

    /**
     * Pega os valores vindo do verbo GET
     */
    protected function get(string $key)
    {
        return isset($_GET[$key]) ? $_GET[$key] : null;
    }
}
