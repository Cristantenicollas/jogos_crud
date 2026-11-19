<?php
require_once '../repositories/JogoRepository.php';

if (!isset($_GET['id']) || !Jogo::validarId($_GET['id'])) {
    echo 'ID inválido. <a href="index.php">Voltar</a>';
    exit;
}

try {
    (new JogoRepository())->excluir($_GET['id']);
    header("Location: index.php");
    exit;
} catch (PDOException $e) {
    echo 'Não foi possível excluir o jogo. <a href="index.php">Voltar</a>';
}
