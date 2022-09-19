<?php

namespace Jorge\ReabastecimentoDoCartao\Controller;

use Jorge\ReabastecimentoDoCartao\Model\CartaoModel;

class InfoController extends BaseController
{
    private $model;

    public function __construct()
    {
        $this->model = new CartaoModel;
    }

    public function index()
    {
        try {
            $this->model->setId($this->get('ID'));

            if ($cartao = $this->model->list()) {
                $cart = $cartao->fetchAll(\PDO::FETCH_ASSOC)[0];
                $info = [
                    "cartão" => $cart,
                ];

                return $this->jsonResponse($info);
                //return $this->jsonResponse($cartao->fetchAll(\PDO::FETCH_ASSOC));
            }
        } catch (\Throwable $th) {
            return $this->jsonResponse($th->getMessage(), self::STATUS_ERROR);
        }
    }
}


BaseController::init(InfoController::class);
