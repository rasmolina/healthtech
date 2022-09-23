<?php
include 'session.php';
require_once "database.php";

try {
    $conexao = Conexao::getConexao();

    //1ª validação - verificar se o médico tem permissão para requisitar exames de alto custo
    $idMedico = $_SESSION['idUser'];
    $idPedido = $_GET["idPedido"];
    $idExame = $_GET["idExame"];

    $stmt = $conexao->prepare("SELECT permite_req FROM medico WHERE id = ?");
    $stmt->execute([$idMedico]);
    $permissaoMedico = $stmt->fetch();

    $stmt = $conexao->prepare("SELECT nome, altocusto FROM exame WHERE id = ?");
    $stmt->execute([$idExame]);
    $exame = $stmt->fetch();

    //Caso o médico não tiver permissão e o exame for de alto custo
    if (!$permissaoMedico['permite_req'] && $exame['altocusto'])
        echo json_encode(array("permissaoNegada" => true));

    else {
        $nomeExame = $exame['nome'];

        //2ª validação - verificar se é possível adicionar exames (um pedido pode conter no máximo 10 exames)
        $stmt = $conexao->prepare("SELECT COUNT(idExame) as contador FROM examespedido 
        WHERE idPedido = ?");
        $stmt->execute([$idPedido]);
        $registroPedidos = $stmt->fetch();
        $aux = $registroPedidos["contador"];  

        $qtdePermitidaExames = 10;

        //Caso o contador encontrar um valor igual ao permitido
        if ($aux == $qtdePermitidaExames) 
            echo json_encode(array("limiteMax" => true)); 
        else {
            //3ª validação - verificar se já existe registro do exame solicitado
            $stmt = $conexao->prepare("SELECT idPedido, idExame FROM examespedido 
            WHERE idPedido = ? and idExame = ?");
            $stmt->execute([$idPedido, $idExame]);
            $registroExamePedido = $stmt->fetch();
            if ($registroExamePedido)
                echo json_encode(array("exameExistente" => true)); 
            else {
                $situacao = "em produção";

                $stmt = $conexao->prepare("SELECT COUNT(idExame) FROM examespedido 
                WHERE idPedido = ?");
                $stmt->execute([$idPedido]);
                $registroPedidos = $stmt->fetchAll();

                $stmt = $conexao->prepare('INSERT INTO examespedido(idPedido, idExame, situacao)
                values (:p1,:p2,:p3)');
                $stmt->execute([
                        "p1" => $idPedido,
                        "p2" => $idExame,
                        "p3" => $situacao
                ]);       
                echo json_encode(array("permitido" => true, "nomeExame" => $nomeExame));
            }
        }
    }
} catch (Exception $th) {
    echo json_encode(array("msg" => $th->getMessage()));
    exit;
}