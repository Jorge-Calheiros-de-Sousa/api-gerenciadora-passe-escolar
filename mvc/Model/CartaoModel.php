<?php

namespace Jorge\ReabastecimentoDoCartao\Model;



class CartaoModel extends BaseModel
{
    private ?int $id;
    private string $partida;
    private string $destino;
    private float $credito;
    public const NAME_TABLE = 'cartoes';


    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setPartida(string $partida)
    {
        $this->partida = $partida;
        return $this;
    }

    public function getPartida()
    {
        return $this->partida;
    }

    public function setDestino(string $destino)
    {
        $this->destino = $destino;
        return $this;
    }

    public function getDestino()
    {
        return $this->destino;
    }

    public function setCredito(float $credito)
    {
        $this->credito = $credito;
        return $this;
    }

    public function getCredito()
    {
        return $this->credito;
    }


    /**
     * Executa a query para listar os cartões do banco 
     */
    public function list()
    {
        $pdo = $this->returnConnection();
        $tableName = self::NAME_TABLE;
        $where = $this->id ? " where $tableName.id=" . $this->id : '';

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
        $sql = $pdo->prepare("INSERT INTO $nameTable (`partida`, `destino`, `credito`) VALUES (:partida,:destino,:credito)");
        $sql->bindParam(':partida', $this->partida);
        $sql->bindParam(':destino', $this->destino);
        $sql->bindParam(':credito', $this->credito);

        return ($sql->execute() ? $sql : false);
    }

    /**
     * Executa a query para editar uma registro especifico do banco
     */
    public function update()
    {
        $pdo = $this->returnConnection();
        $nameTable = self::NAME_TABLE;
        $sql = $pdo->prepare("UPDATE $nameTable SET `partida`=:partida,`destino`=:destino ,`credito`=:credito WHERE id=:id");
        $sql->bindParam(':id', $this->id);
        $sql->bindParam(':partida', $this->partida);
        $sql->bindParam(':destino', $this->destino);
        $sql->bindParam(':credito', $this->credito);
        return ($sql->execute() ? $sql : false);
    }

    /**
     * Executa a query para editar somente o campo do credito
     */

    public function updateOnlyCredito()
    {
        $pdo = $this->returnConnection();
        $nameTable = self::NAME_TABLE;
        $sql = $pdo->prepare("UPDATE $nameTable SET credito='$this->credito' WHERE id=$this->id");
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
