<?php


namespace Jorge\ReabastecimentoDoCartao\Model;



class OnibusModel extends BaseModel
{
    private ?int $id;
    private string $nome;
    private ?int $cartao;
    private float $conducao;
    public const NAME_TABLE = 'onibus';
    public const NAME_TABLE_ASSOCIATE1 = 'cartoes';


    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setNome(string $nome)
    {
        $this->nome = $nome;
        return $this;
    }

    public function getNome()
    {
        return $this->nome;
    }

    public function setConducao(float $conducao)
    {
        $this->conducao = $conducao;
        return $this;
    }

    public function getConducao()
    {
        return $this->conducao;
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

    /**
     * Executa a query para listar os onibus do banco 
     */
    public function list()
    {
        $pdo = $this->returnConnection();

        $nameTableAssociate = self::NAME_TABLE_ASSOCIATE1;
        $nameTable = self::NAME_TABLE;
        $where = $this->id ? " where $nameTable.id=" . $this->id : "";
        $whereCartao = $this->cartao ? ($where ? 'and' : '' . " where $nameTable.cartao = $this->cartao") : '';
        $attributes = "$nameTable.id,cartao, $nameTable.nome, $nameTableAssociate.partida, $nameTableAssociate.destino, $nameTable.conducao";

        $sql = $pdo->prepare("SELECT $attributes FROM " . $nameTable . " INNER JOIN $nameTableAssociate ON $nameTable.cartao = $nameTableAssociate.id" . $where . $whereCartao);

        return ($sql->execute() ? $sql : false);
    }

    /**
     * Executa a query para inserir informações no banco
     */
    public function insert()
    {
        $pdo = $this->returnConnection();
        $nameTable = self::NAME_TABLE;
        $sql = $pdo->prepare("INSERT INTO `$nameTable`(`nome`,`cartao` ,`conducao`) VALUES (:nome,:cartao,:conducao)");
        $sql->bindParam(':nome', $this->nome);
        $sql->bindParam(':conducao', $this->conducao);
        $sql->bindParam(':cartao', $this->cartao);
        return ($sql->execute() ? $sql : false);
    }

    /**
     * Executa a query para editar uma registro especifico do banco
     */
    public function update()
    {
        $pdo = $this->returnConnection();
        $nameTable = self::NAME_TABLE;
        $sql = $pdo->prepare("UPDATE $nameTable SET `nome`=:nome,`cartao`=:cartao,`conducao`=:conducao WHERE `id`=:id");
        $sql->bindParam(':id', $this->id);
        $sql->bindParam(':nome', $this->nome);
        $sql->bindParam(':cartao', $this->cartao);
        $sql->bindParam(':conducao', $this->conducao);
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
