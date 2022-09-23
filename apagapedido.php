<?php include "session.php";

$logged = $_SESSION['logged'] ?? NULL;
$tipoUsuario = $_SESSION['tipoUsuario'] ?? NULL;
if (!$logged or $tipoUsuario == 'pac' or $tipoUsuario == 'med') die('Acesso permitido apenas para o supervisor e médico!');

?>

<?php

    if (isset($_GET["id"]) and !empty($_GET["id"]) )}
        $id = $_GET["id"];
        require_once "database.php";
        try {
            $conexao = Conexao::getConexao();
            $stmt = $conexao->prepare('DELETE from examespedido where idPedido = ?');
            $stmt->execute([$id]);
            if ($stmt->rowCount() > 0){
                $stmt = $conexao->prepare('DELETE from pedido where id = ?');
                $stmt->execute([$id]);
                $resultado = $stmt->fetchAll();
                echo json_encode($resultado);
            }
        } catch (Exception $th) {
            echo json_encode(array("msg" => "Ocorreu algum erro durante a deleção!"));
            exit;
        }
    }

?>