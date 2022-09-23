<?php 
        header('Content-Type: application/json', true);
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET");

        require_once "database.php";

        $lista = [];
        if (isset($_GET['id']) and !empty($_GET['id'])){
            $id = $_GET['id'];
            try {
                $conexao = Conexao::getConexao();
                $stmt = $conexao->prepare('SELECT * FROM supervisor where id=?');
                $stmt->execute([$id]);
                $lista = $stmt->fetchAll();
                if(count($lista)!=0)
                    echo json_encode($lista);
            } catch (Exception $th) {
                echo json_encode(array("msg" => $th->getMessage()));
                exit;
            }
        }else{
            try {
                $conexao = Conexao::getConexao();
                $stmt = $conexao->prepare('SELECT * FROM supervisor order by nomeCompleto');
                $stmt->execute();
                $lista = $stmt->fetchAll();
                if(count($lista)!=0)
                    echo json_encode($lista);
            } catch (Exception $th) {
                echo json_encode(array("msg" => $th->getMessage()));
                exit;
            }
        }

?>