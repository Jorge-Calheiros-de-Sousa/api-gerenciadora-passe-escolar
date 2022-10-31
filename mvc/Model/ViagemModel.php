<?php

namespace Jorge\ReabastecimentoDoCartao\Model;



class ViagemModel extends BaseModel
{
    private ?int $id;
    private ?int $cartao;
    private int $onibus;
    private string $data;
    public const NAME_TABLE = 'viagens';
    public const NAME_TABLE_ASSOSIATE = 'onibus';
    public const NAME_TABLE_ASSOSIATE2 = 'cartoes';

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setCartao($cartao)
    {
        $this->cartao = $cartao;
        return $this;
    }

    public function getCartao()
    {
        return $this->cartao;
    }

    public function setOnibus(int $onibus)
    {
        $this->onibus = $onibus;
        return $this;
    }

    public function getOnibus()
    {
        return $this->onibus;
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
        $nameTable = self::NAME_TABLE;
        $nameTableAssociate = self::NAME_TABLE_ASSOSIATE;
        $nameTableAssociate2 = self::NAME_TABLE_ASSOSIATE2;

        $attributes = "$nameTable.id, $nameTableAssociate.nome, $nameTableAssociate2.partida, $nameTableAssociate2.destino, $nameTableAssociate.conducao, $nameTable.data";
        $where = $this->id ? ' where id=' . $this->id : '';
        $whereCartao = $this->cartao ? ($where ? 'AND ' : '') . " WHERE $nameTableAssociate2.id = $this->cartao" : "";

        $sql = $pdo->prepare("SELECT $attributes FROM $nameTable
        INNER JOIN $nameTableAssociate ON $nameTable.onibus = $nameTableAssociate.id
        INNER JOIN $nameTableAssociate2 ON $nameTable.cartao = $nameTableAssociate2.id
        "
            . $where . $whereCartao);

        return ($sql->execute() ? $sql : false);
    }

    /**
     * Executa a query para inserir informações no banco
     */
    public function insert()
    {
        $pdo = $this->returnConnection();
        $nameTable = self::NAME_TABLE;
        $sql = $pdo->prepare("INSERT INTO $nameTable(`cartao`,`onibus`, `data`) VALUES (:cartao,:onibus, :d)");
        $sql->bindParam(':cartao', $this->cartao);
        $sql->bindParam(':onibus', $this->onibus);
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
        $sql = $pdo->prepare("UPDATE $nameTable SET `id`='$this->id',`cartao`='$this->cartao', `onibus`='$this->onibus', `data`='$this->data' WHERE id=$this->id");
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
