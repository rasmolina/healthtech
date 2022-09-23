<?php 
        header('Content-Type: application/json', true);
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET");

        require_once "database.php";

        $lista = [];
        if (isset($_GET['idPedido']) and !empty($_GET['idPedido']) and
           isset($_GET['idExame']) and !empty($_GET['idExame']) ){
            $idPedido = $_GET['idPedido'];
            $idExame = $_GET['idExame'];
            try {
                $conexao = Conexao::getConexao();
                $stmt = $conexao->prepare('SELECT * FROM examespedido where idPedido = ? and idExame=?');
                $stmt->execute([$idPedido,$idExame]);
                $lista = $stmt->fetchAll();
                if(count($lista)!=0)
                    echo json_encode($lista);
            } catch (Exception $th) {
                echo json_encode(array("msg" => $th->getMessage()));
                exit;
            }
        }
?>