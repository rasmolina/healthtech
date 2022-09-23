<?php
require_once "database.php";
try {
    $conexao = Conexao::getConexao();

    $idPedido = $_GET["idPedido"];
    $idExame = $_GET["idExame"];

    $stmt = $conexao->prepare("DELETE FROM examespedido WHERE idPedido=? AND idExame=?");
    $stmt->execute([$idPedido, $idExame]);    

    if ($stmt->rowCount() > 0) {
        $stmt = $conexao->prepare("SELECT COUNT(idExame) as contador FROM examespedido 
        WHERE idPedido = ?");
        $stmt->execute([$idPedido]);
        $registroPedidos = $stmt->fetch();
        $aux = $registroPedidos["contador"];
        echo json_encode (array("examesPedidoDeletados" => true, "contador" => $aux));
    }

} catch (Exception $th) {
    echo json_encode(array("msg" => $th->getMessage()));
    exit;
}