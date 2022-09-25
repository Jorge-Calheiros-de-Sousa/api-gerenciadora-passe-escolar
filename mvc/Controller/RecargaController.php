<?php

namespace Jorge\ReabastecimentoDoCartao\Controller;

use Exception;
use Jorge\ReabastecimentoDoCartao\Model\CartaoModel;
use Jorge\ReabastecimentoDoCartao\Model\LogModel;
use Jorge\ReabastecimentoDoCartao\Model\RecargaModel;

class RecargaController extends BaseController
{
    private $model;
    private $modelCartao;
    private $modelLog;

    public function __construct()
    {
        $this->model = new RecargaModel;
        $this->modelCartao = new CartaoModel;
        $this->modelLog = new LogModel;
    }

    /**
     * Lista os registros das recargas
     */
    public function index()
    {
        try {
            $this->model->setId($this->get('ID'))
                ->setCartao($this->get('ID_CARTAO'));

            if ($list = $this->model->list()) {
                $this->jsonResponse($list->fetchAll(\PDO::FETCH_ASSOC));
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


            $creditoAtual = $this->modelCartao->setId($data['cartao'])->list()->fetch(\PDO::FETCH_ASSOC)["credito"];
            $novoCredito = $creditoAtual + $data['total'];

            $this->model
                ->setCartao($data['cartao'])
                ->setTotal($data["total"])
                ->setData($data['data']);

            if ($idRecarga = $this->model->insert()) {
                if ($this->modelCartao->setId($data["cartao"])->setCredito($novoCredito)->updateOnlyCredito()) {
                    $this->modelLog
                        ->setRecarga($idRecarga)
                        ->setViagem(null)
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
                    ->setCartao($data['cartao'])
                    ->setTotal($data["total"])
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

BaseController::init(RecargaController::class);
