<?php include "session.php";

$logged = $_SESSION['logged'] ?? NULL;
$tipoUsuario = $_SESSION['tipoUsuario'] ?? NULL;
if (!$logged) die('Acesso negado!');

    if (isset($_GET["id"]) and !empty($_GET["id"]) ){
        $id = $_GET["id"];
        require_once "database.php";
        try {
            $conexao = Conexao::getConexao();
            $stmt = $conexao->prepare('SELECT pedido.id as id, pedido.hipotese as hipotese, pedido.dataSolicitacao as dataPedido, paciente.nomeCompleto as nomePaciente from pedido inner join paciente on pedido.idPaciente = paciente.id and pedido.idMedico = ?');
            $stmt->execute([$id]);
            $resultado = $stmt->fetchAll();
            echo json_encode($resultado);
            
        } catch (Exception $th) {
            echo $th->getMessage();
            exit;
        }
    }

?>