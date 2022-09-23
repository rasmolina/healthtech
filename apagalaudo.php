<?php include "session.php";

$logged = $_SESSION['logged'] ?? NULL;
$tipoUsuario = $_SESSION['tipoUsuario'] ?? NULL;
if (!$logged or $tipoUsuario == 'pac' or $tipoUsuario == 'med') die('Acesso permitido apenas para o supervisor e analista!');

?>

<?php

    if (isset($_GET["idPedido"]) and !empty($_GET["idPedido"]) and
        isset($_GET["idExame"]) and !empty($_GET["idExame"]) ){
        $idPedido = $_GET["idPedido"];
        $idExame = $_GET["idExame"];
        require_once "database.php";
        try {
            $conexao = Conexao::getConexao();
            $stmt = $conexao->prepare('DELETE from examespedido where idPedido = ? and idExame = ?');
            $stmt->execute([$idPedido,$idExame]);
            $resultado = $stmt->fetchAll();
            echo json_encode($resultado);
        } catch (Exception $th) {
            echo json_encode(array("msg" => "Ocorreu algum erro durante a deleção!"));
            exit;
        }
    }

?>