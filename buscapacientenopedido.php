<?php include "session.php";

$logged = $_SESSION['logged'] ?? NULL;
$tipoUsuario = $_SESSION['tipoUsuario'] ?? NULL;
if (!$logged) die('Acesso negado!');

    if (isset($_GET["id"]) and !empty($_GET["id"]) ){
        $id = $_GET["id"];
        require_once "database.php";
        try {
            $conexao = Conexao::getConexao();
            $stmt = $conexao->prepare('select paciente.nomeCompleto as nomePaciente from paciente inner join pedido on paciente.id = pedido.idPaciente and pedido.id = ?');
            $stmt->execute([$id]);
            $resultado = $stmt->fetchAll();
            echo json_encode($resultado);
            
        } catch (Exception $th) {
            echo $th->getMessage();
            exit;
        }
    }

?>
