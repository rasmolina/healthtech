<?php 
include "session.php";

$logged = $_SESSION['logged'] ?? NULL;
$tipoUsuario = $_SESSION['tipoUsuario'] ?? NULL;
if (!$logged or $tipoUsuario != 'sup') die('Acesso permitido apenas para o supervisor!');

        header('Content-Type: application/json', true);
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET");

        require_once "database.php";

        if (isset($_GET['telefone']) and !empty($_GET['telefone'])){
            $telefone = $_GET['telefone'];
            try {
                $conexao = Conexao::getConexao();
                $stmt = $conexao->prepare('SELECT * FROM paciente where telefone=?');
                $stmt->execute([$telefone]);
                $resultado = $stmt->fetch();
                if(count($resultado)!=0)
                    echo json_encode($resultado["telefone"]);
            } catch (Exception $th) {
                echo json_encode(array("msg" => $th->getMessage()));
                exit;
            }
        }
?>