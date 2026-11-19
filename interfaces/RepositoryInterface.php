<?php
interface RepositoryInterface{
    public function criar($objeto);
    public function listar();
    public function buscarPorID($id);
    public function atualizar($id, $objeto);
    public function excluir($id);
}
?>
