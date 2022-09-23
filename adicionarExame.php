<?php
require_once "database.php";
require_once "session.php";

try {
    $conexao = Conexao::getConexao();

    $idPedido = $_GET["idPedido"];
    $idExame = $_GET["idExame"];

    echo json_encode(array("idPedido" => $idPedido, "idExame" => $idExame));

} catch (Exception $th) {
        echo json_encode(array("msg" => $th->getMessage()));
        exit;
}