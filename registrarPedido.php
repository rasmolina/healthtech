<?php
require_once "database.php";
require_once "session.php";

if (isset($_GET["idPaciente"]) and !empty($_GET["idPaciente"]) and
    isset($_GET["descricaoHipotese"]) and !empty($_GET["descricaoHipotese"])) {

        try {
            $conexao = Conexao::getConexao();

            $hipotese = $_GET["descricaoHipotese"];
            $data = (new DateTime())->format("Y-m-d H:i:s");
            $idMedico = $_SESSION['idUser'];
            $idPaciente = $_GET["idPaciente"];
                     
            $stmt = $conexao->prepare('INSERT INTO pedido(hipotese, dataSolicitacao, idMedico, idPaciente)
            values (:p1,:p2,:p3,:p4)');
            $stmt->execute([
                "p1" => $hipotese,
                "p2" => $data,
                "p3" => $idMedico,
                "p4" => $idPaciente
            ]);
            $idGerado = $conexao->lastInsertId();

            $stmt = $conexao->prepare("SELECT pac.nomeCompleto, ped.hipotese 
            FROM pedido ped, paciente pac
            WHERE ped.id=? and ped.idPaciente = pac.id");
            $stmt->execute([$idGerado]);
            $aux = $stmt->fetch(); 

            echo json_encode(array("nomePaciente" => $aux["nomeCompleto"], "hipoteseCadastrada" => $aux["hipotese"], "idPedido" => $idGerado));
    } catch (Exception $th) {
        echo json_encode(array("msg" => $th->getMessage()));
        exit;
    }
}