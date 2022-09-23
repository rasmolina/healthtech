<?php include "session.php";

$logged = $_SESSION['logged'] ?? NULL;
$tipoUsuario = $_SESSION['tipoUsuario'] ?? NULL;
if (!$logged) die('Acesso negado!');

    if (isset($_GET["idPedido"]) and !empty($_GET["idPedido"]) and
        isset($_GET["idExame"]) and !empty($_GET["idExame"]) ){
        $idPedido = $_GET["idPedido"];
        $idExame = $_GET["idExame"];
        require_once "database.php";
        try {
            $conexao = Conexao::getConexao();
            $stmt = $conexao->prepare('SELECT e.nome as exame, ep.laudo as laudo, ep.idPedido as numPedido, ep.idExame as numExame from examespedido ep inner join exame e where idPedido = ? and idExame = ? and e.id = ep.idExame');
            $stmt->execute([$idPedido,$idExame]);
            $resultado = $stmt->fetchAll();
            echo json_encode($resultado);
            
        } catch (Exception $th) {
            echo $th->getMessage();
            exit;
        }
    }

?>