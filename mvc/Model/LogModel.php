<?php

namespace Jorge\ReabastecimentoDoCartao\Model;

class LogModel extends BaseModel
{
    private ?int $id;
    private ?int $recarga;
    private ?int $viagem;
    private float $creditoAtual;
    private float $credito;
    private string $data;
    public const NAME_TABLE = 'logs';

    public function setId(int $id)
    {
        $this->id = $id;
        return $this;
    }

    public function getId()
    {
        return $this->id;
    }


    public function setRecarga(?int $recarga)
    {
        $this->recarga = $recarga;
        return $this;
    }

    public function getRecarga()
    {
        return $this->recarga;
    }


    public function setViagem(?int $viagem)
    {
        $this->viagem = $viagem;
        return $this;
    }

    public function getViagem()
    {
        return $this->viagem;
    }


    public function setCreditoAtual($creditoAtual)
    {
        $this->creditoAtual = $creditoAtual;
        return $this;
    }

    public function getCreditoAtual()
    {
        return $this->creditoAtual;
    }

    public function setCredito($credito)
    {
        $this->credito = $credito;
        return $this;
    }

    public function getCredito()
    {
        return $this->credito;
    }

    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    public function getData()
    {
        return $this->data;
    }

    /**
     * Executa a query para listar os logs do banco 
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
        $recarga = $this->recarga ? $this->recarga : null;
        $viagem = $this->viagem ? $this->viagem : null;
        $sql = $pdo->prepare("INSERT INTO $nameTable (`recarga`, `viagem`, `creditoAtual`, `credito`, `data`) VALUES (:recarga, :viagem, :creditoAtual, :credito, :d);");
        $sql->bindParam(':recarga', $recarga);
        $sql->bindParam(':viagem', $viagem);
        $sql->bindParam(':creditoAtual', $this->creditoAtual);
        $sql->bindParam(':credito', $this->credito);
        $sql->bindParam(':d', $this->data);
        return ($sql->execute() ? $sql : false);
    }

    /**
     * Executa a query para editar uma registro especifico do banco
     */
    public function update()
    {
        $pdo = $this->returnConnection();
        $nameTable = self::NAME_TABLE;
        $recarga = $this->recarga ? $this->recarga : null;
        $viagem = $this->viagem ? $this->viagem : null;
        $sql = $pdo->prepare("UPDATE $nameTable SET `recarga`=:recarga,`viagem`=:viagem,`creditoAtual`=:creditoAtual, `credito`=:credito, `data`=:d WHERE id=:id");
        $sql->bindParam(':id', $this->id);
        $sql->bindParam(':recarga', $recarga);
        $sql->bindParam(':viagem', $viagem);
        $sql->bindParam(':creditoAtual', $this->creditoAtual);
        $sql->bindParam(':credito', $this->credito);
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
