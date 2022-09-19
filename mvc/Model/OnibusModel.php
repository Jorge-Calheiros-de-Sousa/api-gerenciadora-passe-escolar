<?php


namespace Jorge\ReabastecimentoDoCartao\Model;



class OnibusModel extends BaseModel
{
    private ?int $id;
    private string $nome;
    private float $conducao;
    public const NAME_TABLE = 'onibus';


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

    /**
     * Executa a query para listar os onibus do banco 
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
        $sql = $pdo->prepare("INSERT INTO `$nameTable`(`nome`, `conducao`) VALUES (:nome,:conducao)");
        $sql->bindParam(':nome', $this->nome);
        $sql->bindParam(':conducao', $this->conducao);
        return ($sql->execute() ? $sql : false);
    }

    /**
     * Executa a query para editar uma registro especifico do banco
     */
    public function update()
    {
        $pdo = $this->returnConnection();
        $nameTable = self::NAME_TABLE;
        $sql = $pdo->prepare("UPDATE $nameTable SET `nome`=:nome,`conducao`=:conducao WHERE id = :id");
        $sql->bindParam(':id', $this->id);
        $sql->bindParam(':nome', $this->nome);
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
