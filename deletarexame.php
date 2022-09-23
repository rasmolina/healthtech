<?php include 'session.php';

//se o usuário não estiver logado não acessará esta página e será redirecionado para a página principal
$logged = $_SESSION['logged'] ?? NULL;
$tipoUsuario = $_SESSION['tipoUsuario'] ?? NULL;
if (!$logged or $tipoUsuario == 'pac' or $tipoUsuario == 'med') die('Acesso permitido apenas para o supervisor e analista!');

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exclusão de Exames</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.9.3/css/bulma.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css">
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
</head>
<body>
    <?php include_once "menulogado.php" ?>

    <header class="has-text-centered">
        <div class="block">
            <h2 class="title is-2">Health Tech</h2>
            <h4 class="title is-4">Exclusão de Exame</h4>
        </div>
    </header>

    <main>
    <div class="column is-1"></div>
    <div class="has-text-centered">
    <form method="POST" id="selectForm">
        <div class="select is-link">
        <select name="listaDeExames" id="listaDeExames" onchange="exibeForm()">
            <option active>Selecione um exame</option>
            <?php
                $lista = listarExames();
                for ($i=0; $i<count($lista); $i++) {
                    $id = $lista[$i]["id"];
                    $nomeExame = $lista[$i]["nome"];
                    $altoCusto = $lista[$i]["altocusto"];
                    echo "<option value='$id'>$nomeExame</option>";
                }
            ?>
        </select>
        </div>
        <button type="submit" class="button is-danger" onclick="return confirm('Tem certeza que deseja deletar este exame?')">Excluir</button>
    </form>
    </div>

    </main>

    <?php 
    function listarExames(){
        require_once "database.php";
        $lista = [];
        try {
            $conexao = Conexao::getConexao();
            $stmt = $conexao->prepare("SELECT * FROM exame ORDER BY nome");
            $stmt->execute();
            $lista = $stmt->fetchAll();
        } catch (Exception $th) {
            echo $th->getMessage();
            exit;
        }
        return $lista;
    }
    
    if( isset($_POST["listaDeExames"]) and !empty($_POST["listaDeExames"]) ){
        $id = $_POST["listaDeExames"]; //o id é o value resgatado do elemento select
        try {
            require_once "database.php";
            $conexao = Conexao::getConexao();
            //Verifica se há solicitação de exame contendo o exame que se deseja editar, se houver, a deleção não será permitida
            $stmt = $conexao->prepare('SELECT * FROM examespedido WHERE idExame = ?');
            $stmt->execute([$id]);
            if ($stmt->rowCount() > 0){
                echo "<meta HTTP-EQUIV='refresh' CONTENT='3'>";
                echo '<script type="text/javascript">toastr.warning("Existem solicitações realizadas com este exame, deleção não permitida!")</script>';
            }else{
                $stmt = $conexao->prepare("DELETE from exame WHERE id=?");
                $stmt->execute([$id]);
                if ($stmt->rowCount() > 0) {
                    echo "<meta HTTP-EQUIV='refresh' CONTENT='2'>";
                    echo '<script type="text/javascript">toastr.success("Exame removido com sucesso!")</script>';
                } else 
                    echo '<script type="text/javascript">toastr.warning("Houve algum problema na deleção!")</script>';
            }
        } catch (Exception $th) {
            echo json_encode(array("msg" => $th->getMessage()));
            exit;
        }
    }

?>
    
<?php include "footer.php"; ?>

</body>

</html>