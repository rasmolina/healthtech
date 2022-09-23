<?php 
include "session.php";

$logged = $_SESSION['logged'] ?? NULL;
$tipoUsuario = $_SESSION['tipoUsuario'] ?? NULL;
if (!$logged or $tipoUsuario != 'sup') die('Acesso permitido apenas para o supervisor!');

        header('Content-Type: application/json', true);
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET");

        require_once "database.php";

        if (isset($_GET['email']) and !empty($_GET['email'])){
            $email = $_GET['email'];
            try {
                $conexao = Conexao::getConexao();
                $stmt = $conexao->prepare('SELECT * FROM paciente where email=?');
                $stmt->execute([$email]);
                $resultado = $stmt->fetch();
                if(count($resultado)!=0)
                    echo json_encode($resultado["email"]);
            } catch (Exception $th) {
                echo json_encode(array("msg" => $th->getMessage()));
                exit;
            }
        }
?>