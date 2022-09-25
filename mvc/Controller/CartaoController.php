<?php

namespace Jorge\ReabastecimentoDoCartao\Controller;

use Exception;
use Jorge\ReabastecimentoDoCartao\Model\CartaoModel;
use Jorge\ReabastecimentoDoCartao\Model\LogModel;
use Jorge\ReabastecimentoDoCartao\Model\OnibusModel;

class CartaoController extends BaseController
{
    private $model;
    private $modelOnibus;

    function __construct()
    {
        $this->model = new CartaoModel;
        $this->modelOnibus = new OnibusModel;
    }

    /**
     * Lista os cartões
     */
    public function index()
    {
        try {
            $listCartoes = [];
            $this->model->setId($this->get('ID'));

            if ($list = $this->model->list()) {

                foreach ($list->fetchAll(\PDO::FETCH_ASSOC) as $item) {
                    $id = $item["id"];
                    $listOnibus = $this->modelOnibus->setId(null)->setCartao($id)->list()->fetchAll(\PDO::FETCH_ASSOC);
                    $item["onibus"] = [];
                    foreach ($listOnibus as $oni) {
                        $item['onibus'][] = $oni;
                    }
                    $listCartoes[] = $item;
                }
                return $this->jsonResponse($listCartoes);
            }
        } catch (\Throwable $th) {
            $this->jsonResponse($th->getMessage(), self::STATUS_ERROR);
        }
    }

    /**
     * Cria um novo cartão
     */
    public function create()
    {
        try {
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);
            $this->model
                ->setPartida($data['partida'])
                ->setDestino($data['destino'])
                ->setCredito($data['credito']);

            if ($created = $this->model->insert()) {
                return  $this->jsonResponse($created->fetchAll(\PDO::FETCH_ASSOC), self::STATUS_CREATED);
            }
        } catch (\Throwable $th) {
            $this->jsonResponse($th->getMessage(), self::STATUS_ERROR);
        }
    }

    /**
     * Edita um cartão existente
     */
    public function update()
    {
        try {
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);
            $id = $this->get('ID');
            if ($id) {
                $this->model
                    ->setId($id)
                    ->setPartida($data['partida'])
                    ->setDestino($data['destino'])
                    ->setCredito($data['credito']);

                if ($updated = $this->model->update()) {
                    return $this->jsonResponse($updated->fetchAll(\PDO::FETCH_ASSOC), self::STATUS_UPDATED);
                }
            } else {
                throw new Exception("parametro ID obrigátorio");
            }
        } catch (\Throwable $th) {
            $this->jsonResponse($th->getMessage(), self::STATUS_ERROR);
        }
    }

    /**
     * Deleta um cartão existente
     */
    public function destroy()
    {
        try {
            $id = $this->get('ID');
            if ($id) {
                $this->model->setId($id);
                if ($deleted = $this->model->delete()) {
                    return $this->jsonResponse($deleted->fetchAll(\PDO::FETCH_ASSOC));
                }
            } else {
                throw new Exception("paramentro ID obrigátorio");
            }
        } catch (\Throwable $th) {
            return $this->jsonResponse($th->getMessage(), self::STATUS_ERROR);
        }
    }
}


BaseController::init(CartaoController::class);
