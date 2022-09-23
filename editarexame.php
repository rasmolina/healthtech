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
    <title>Edição de Exames</title>
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
            <h4 class="title is-4">Alteração de Exame</h4>
        </div>
    </header>

    <main>
    <div class="column is-1"></div>
    <div class="has-text-centered">
    <form method="GET" id="selectForm">
        <div class="select is-link">
        <select name="listaDeExames" id="listaDeExames" onchange="exibeForm()">
            <option active>Selecione um exame para alterar o cadastro</option>
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
    </form>
    </div>


    <div class="column is-half is-offset-one-quarter">
    <form class="box" method="POST" id="editForm" name="editForm" style="display: none;">
        <div class="field">
            <input type="hidden" id="idExame" name="idExame">
            <label class="label">Nome do exame</label>
            <div class="control">
                <input autofocus type="text" class="input" id="nomeExame" name="nome" required>
            </div>
        </div>

        <div class="field">
            <label class="label">Exame de alto custo?</label>
            <div class="control">
                <label class="radio">
                <input type="radio" id="sim" name="altoCusto" value=1>Sim</label>
                <label class="radio">
                <input type="radio" id="nao" name="altoCusto" value=0>Não</label>
            </div>
        </div>

        <div class="buttons">
            <div class="control">
                <button type="submit" class="button is-success">Atualizar</button>
                <button type="button" class="button is-link" onclick="window.location.href='index.php'">Cancelar</button>
            </div>
        </div>
    </form>
    </div>

    </main>

    <script>
        //Função para resgatar os valores cadastrados no BD e jogar para os inputs
        function exibeForm(){
            document.getElementById("selectForm").style.display = "none"; //oculta o form de seleção
            document.getElementById("editForm").style.display = "block"; //exibe o form de edição
            var id = $( "#listaDeExames option:selected" ).val();
            var inputIdExame = document.querySelector("#idExame");
            var inputNome = document.querySelector("#nomeExame");
            var inputRadioYes = document.querySelector("#sim");
            var inputRadioNo = document.querySelector("#nao");

            $.getJSON("listaexames.php?id="+id,
            function(dados){
                for (i=0;i<dados.length;i++){
                    inputIdExame.value = dados[i].id;
                    inputNome.value = dados[i].nome;
                    if (dados[i].altocusto == 0){
                        inputRadioNo.value = dados[i].altocusto;
                        inputRadioNo.checked = true;
                        inputRadioYes.checked = false;
                    }
                    if (dados[i].altocusto == 1){
                        inputRadioYes.value = dados[i].altocusto;
                        inputRadioNo.checked = false;
                        inputRadioYes.checked = true;
                    }
                }
            });

            inputNome.focus();
        }
    </script>        


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
    
if (isset($_POST['nome']) and !empty($_POST['nome'])) {
        try {
            require_once "database.php";
            $conexao = Conexao::getConexao();

            $nome = $_POST['nome'];
            $id = $_POST['idExame'];
            $altoCusto = $_POST["altoCusto"];

            //Verifica se há solicitação de exame contendo o exame que se deseja editar, se houver, a edição não será permitida
            $stmt = $conexao->prepare('SELECT * FROM examespedido WHERE idExame = ?');
            $stmt->execute([$id]);
            if ($stmt->rowCount() > 0){
                echo "<meta HTTP-EQUIV='refresh' CONTENT='3'>";
                echo '<script type="text/javascript">toastr.warning("Existem solicitações realizadas com este exame, edição não permitida!")</script>';
            }else{
                //Verifica se o exame já está cadastrado no sistema para não haver duplicidade
                $stmt = $conexao->prepare('SELECT * FROM exame WHERE nome = ? and altocusto=?');
                $stmt->execute([$nome,$altoCusto]);
                $resultado = $stmt->fetch();
                if ($stmt->rowCount() > 0)
                    if($altoCusto == $resultado['altocusto']) 
                        echo '<script type="text/javascript">
                        toastr.warning("O exame ' . $resultado['nome'] . 
                        ' já está cadastrado no sistema!")</script>';
                        else{
                        //Caso queira editar e alterar só o campo alto custo
                        $stmt = $conexao->prepare('UPDATE exame set nome=?, altocusto=? where id=?');
                        $stmt->execute([$nome,$altoCusto,$id]);
                        echo "<meta HTTP-EQUIV='refresh' CONTENT='2'>";
                        echo '<script type="text/javascript">toastr.success("Exame atualizado com sucesso!")</script>';
                    }
                else {
                    $stmt = $conexao->prepare('UPDATE exame set nome=?, altocusto=? where id=?');
                    $stmt->execute([$nome,$altoCusto,$id]);
                    echo "<meta HTTP-EQUIV='refresh' CONTENT='2'>";
                    echo '<script type="text/javascript">toastr.success("Exame atualizado com sucesso!")</script>';
                }
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