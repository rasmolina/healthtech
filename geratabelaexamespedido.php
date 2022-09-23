<?php include "session.php";

$logged = $_SESSION['logged'] ?? NULL;
$tipoUsuario = $_SESSION['tipoUsuario'] ?? NULL;
if (!$logged) die('Acesso negado!');

    if (isset($_GET["id"]) and !empty($_GET["id"]) ){
        $idPedido = $_GET["id"];
        require_once "database.php";
        try {
            $conexao = Conexao::getConexao();
            $stmt = $conexao->prepare('SELECT examespedido.idPedido as numPedido, examespedido.idExame as numExame, exame.nome as exame, examespedido.situacao as status, examespedido.dataHoraRealizacao as dataLiberado from examespedido inner join exame on examespedido.idExame = exame.id and examespedido.idPedido = ?');
            $stmt->execute([$idPedido]);
            $resultado = $stmt->fetchAll();
            echo json_encode($resultado);
            
        } catch (Exception $th) {
            echo $th->getMessage();
            exit;
        }
    }

?>