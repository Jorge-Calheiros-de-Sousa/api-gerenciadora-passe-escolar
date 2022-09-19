<?php

namespace Jorge\ReabastecimentoDoCartao\Controller;

use Exception;
use Jorge\ReabastecimentoDoCartao\Model\CartaoModel;
use Jorge\ReabastecimentoDoCartao\Model\LogModel;
use Jorge\ReabastecimentoDoCartao\Model\OnibusModel;
use Jorge\ReabastecimentoDoCartao\Model\ViagemModel;

class ViagemController extends BaseController
{
    private $model;
    private $modelLog;
    private $modelCartao;
    private $modelOnibus;

    public function __construct()
    {
        $this->model = new ViagemModel;
        $this->modelCartao = new CartaoModel;
        $this->modelOnibus = new  OnibusModel;
        $this->modelLog = new LogModel;
    }

    /**
     * Lista os viagens
     */
    public function index()
    {
        try {
            $this->model->setId($this->get('id'));
            if ($list = $this->model->list()) {
                $this->jsonResponse($list->fetchAll(\PDO::FETCH_ASSOC));
            }
        } catch (\Throwable $th) {
            $this->jsonResponse($th->getMessage(), self::STATUS_ERROR);
        }
    }

    /**
     * Registrar uma nova viagem
     */
    public function create()
    {
        try {
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $creditoAtual = $this->modelCartao->setId($data['cartao'])->list()->fetch(\PDO::FETCH_ASSOC)["credito"];
            $conducao = $this->modelOnibus->setId($data['onibus'])->list()->fetch(\PDO::FETCH_ASSOC)["conducao"];
            $novoCredito = $creditoAtual - ($conducao / 2);

            $this->modelCartao->setId($data['cartao'])->setCredito($novoCredito);

            if ($this->modelCartao->updateOnlyCredito()) {
                $this->model
                    ->setCartao($data['cartao'])
                    ->setOnibus($data['onibus'])
                    ->setData($data['data']);

                if ($idViagem = $this->model->insert()) {
                    $this->modelLog
                        ->setRecarga(null)
                        ->setViagem($idViagem)
                        ->setCredito($creditoAtual)
                        ->setCreditoAtual($novoCredito)
                        ->setData($data['data']);
                    if ($created = $this->modelLog->insert()) {
                        return $this->jsonResponse($created->fetchAll(\PDO::FETCH_ASSOC));
                    }
                }
            }
        } catch (\Throwable $th) {
            $this->jsonResponse($th->getMessage(), self::STATUS_ERROR);
        }
    }

    /**
     * Edita um registro de uma viagem especifica
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
                    ->setCartao($data['cartao'])
                    ->setOnibus($data['onibus'])
                    ->setData($data['data']);

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
     * Deleta o registro de uma viagem existente
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


BaseController::init(ViagemController::class);
