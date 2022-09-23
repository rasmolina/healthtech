<?php
require_once "database.php";
try {
    $conexao = Conexao::getConexao();

    $idPedido = $_GET["idPedido"];

    $stmt = $conexao->prepare("DELETE FROM examespedido WHERE idPedido =?");
    $stmt->execute([$idPedido]);    

    if ($stmt->rowCount() > 0)
        echo json_encode (array("examesPedidoDeletados" => true));

} catch (Exception $th) {
    echo json_encode(array("msg" => $th->getMessage()));
    exit;
}