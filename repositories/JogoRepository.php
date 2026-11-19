<?php

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/jogo.php';
require_once __DIR__ . '/../interfaces/RepositoryInterface.php';

class JogoRepository implements RepositoryInterface{

    private $conn;

    public function __construct()
    {
        $this->conn = Database::conectar();
    }

    #[Override]
    public function criar($objeto)
    {
        $sql = "INSERT INTO jogos (titulo, genero, plataforma, ano) VALUES (:t, :g, :p, :a)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':t' => $objeto->getTitulo(),
            ':g' => $objeto->getGenero(),
            ':p' => $objeto->getPlataforma(),
            ':a' => $objeto->getAno(),
        ]);
    }

    #[Override]
    public function listar(){
        $stmt = $this->conn->query("SELECT * FROM jogos ORDER BY id");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    #[Override]
    public function buscarPorID($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM jogos WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    #[Override]
    public function atualizar($id, $objeto)
    {
        $sql = "UPDATE jogos SET titulo=:t, genero=:g, plataforma=:p, ano=:a WHERE id=:id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':t' => $objeto->getTitulo(),
            ':g' => $objeto->getGenero(),
            ':p' => $objeto->getPlataforma(),
            ':a' => $objeto->getAno(),
            ':id' => $id
        ]);
    }

    #[Override]
    public function excluir($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM jogos WHERE id = :id");
        $stmt->execute([':id' => $id]);
    }
}
?>
