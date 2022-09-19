<?php

namespace Jorge\ReabastecimentoDoCartao\Model;


class RecargaModel extends BaseModel
{
    private ?int $id;
    private int $cartao;
    private float $total;
    private string $data;
    public const NAME_TABLE = 'recargas';

    public function setTotal(float $total)
    {
        $this->total = $total;
        return $this;
    }

    public function getTotal()
    {
        return $this->total;
    }


    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setCartao(int $cartao)
    {
        $this->cartao = $cartao;
        return $this;
    }

    public function getCartao()
    {
        return $this->cartao;
    }

    public function setData(string $data)
    {
        $this->data = $data;
        return $this;
    }

    public function getData()
    {
        return $this->data;
    }

    /**
     * Executa a query para listar os cartões do banco 
     */
    public function list()
    {
        $pdo = $this->returnConnection();

        $where = $this->id ? ' where id=' . $this->id : '';

        $sql = $pdo->prepare("SELECT * FROM " . self::NAME_TABLE . $where);

        return ($sql->execute() ? $sql : false);
    }

    /**
     * Executa a query para inserir informações no banco
     */
    public function insert()
    {
        $pdo = $this->returnConnection();
        $nameTable = self::NAME_TABLE;
        $sql = $pdo->prepare("INSERT INTO $nameTable(`cartao`, `total`, `data`) VALUES (:cartao,:total,:d)");
        $sql->bindParam(':cartao', $this->cartao);
        $sql->bindParam(':total', $this->total);
        $sql->bindParam(':d', $this->data);

        return ($sql->execute() ? $pdo->lastInsertId() : false);
    }

    /**
     * Executa a query para editar uma registro especifico do banco
     */
    public function update()
    {
        $pdo = $this->returnConnection();
        $nameTable = self::NAME_TABLE;
        $sql = $pdo->prepare("UPDATE $nameTable SET `cartao`=:cartao,`total`=:total,`data`=:d WHERE id=:id");
        $sql->bindParam(':id', $this->id);
        $sql->bindParam(':cartao', $this->cartao);
        $sql->bindParam(':total', $this->total);
        $sql->bindParam(':d', $this->data);

        return ($sql->execute() ? $sql : false);
    }

    /**
     * Execute a query de deletar um registro especifico do banco
     */
    public function delete()
    {
        $pdo = $this->returnConnection();
        $nameTable = self::NAME_TABLE;
        $sql = $pdo->prepare("DELETE FROM $nameTable WHERE id=$this->id");
        return ($sql->execute() ? $sql : false);
    }
}
