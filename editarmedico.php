<?php include 'session.php';

//se o usuário não estiver logado não acessará esta página e será redirecionado para a página principal
$logged = $_SESSION['logged'] ?? NULL;
$tipoUsuario = $_SESSION['tipoUsuario'] ?? NULL;
if (!$logged or $tipoUsuario != 'sup') die('Acesso permitido apenas para o supervisor!');

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edição de Médicos</title>
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
            <h4 class="title is-4">Alterar de Médico</h4>
        </div>
    </header>

    <main>
    <div class="column is-1"></div>
    <div class="has-text-centered">
    <form method="GET" id="selectForm">
        <div class="select is-link">
        <select name="listaDeMedicos" id="listaDeMedicos" onchange="exibeForm()">
            <option active>Selecione um médico para alterar o cadastro</option>
            <?php
                $lista = listarMedicos();
                for ($i=0; $i<count($lista); $i++) {
                    $id = $lista[$i]["id"];
                    $nomeMedico = $lista[$i]["nomeCompleto"];
                    echo "<option value='$id'>$nomeMedico</option>";
                }
            ?>
        </select>
        </div>
    </form>
    </div>

    <div class="column is-half is-offset-one-quarter">
    <section id="editarMedico">
        <form class="box" method="POST" id="editForm" name="editForm" style="display: none;">
            <div class="field">
                <input type="hidden" id="idMedico" name="idMedico">
                <label class="label">Nome completo</label>
                <div class="control">
                    <input autofocus type="text" class="input" id="nomeMedico" name="nome" required>
                </div>
            </div>

            <div class="field">
                <label class="label">CPF (* valor único)</label>
                <div class="control">
                    <input type="text" id="cpfMedico" class="input" name="cpf" maxlenght="14" required>
                </div>
            </div>

            <div class="field">
                <label class="label">Data de Nascimento</label>
                <div class="control">
                    <input type="date" class="input" id="nascimentoMedico" name="nascimento" required>
                </div>
            </div>

            <div class="field">
                <label class="label">Logradouro</label>
                <div class="control">
                    <input type="text" class="input" id="logradouroMedico" name="logradouro" required>
                </div>
            </div>

            <div class="field">
                <label class="label">Número</label>
                <div class="control">
                    <input type="int" class="input" id="nroMedico" name="nro" required>
                </div>
            </div>

            <div class="field">
                <label class="label">Telefone (*)</label>
                <div class="control">
                    <input type="text" class="input" id="telefoneMedico" name="telefone" required>
                </div>
            </div>

            <div class="field">
                <label class="label">E-mail (*)</label>
                <div class="control">
                    <input type="email" class="input" id="emailMedico" name="email" required>
                </div>
            </div>

            <div class="field">
                <label class="label">CRM (*)</label>
                <div class="control">
                    <input type="text" class="input" id="crmMedico" name="crm" required>
                </div>
            </div>

            <div class="field">
                <label class="label">Especialidade</label>
                <div class="control">
                    <input type="text" class="input" id="especialidadeMedico" name="especialidade" required>
                </div>
            </div>

            <div class="field">
                <label class="label">O(a) médico(a) poderá solicitar exames de alto custo?</label>
                <div class="control">
                    <label class="radio">
                    <input type="radio" id="sim" name="permite_req" value=1>
                    Sim
                    </label>
                    <label class="radio">
                    <input type="radio" id="nao" name="permite_req" value=0 checked>
                    Não
                    </label>
                </div>
            </div>

            <div class="field">
                <label class="label">Login (*)</label>
                <div class="control">
                    <input type="text" class="input" id="loginMedico" name="login" required>
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
            var id = $( "#listaDeMedicos option:selected" ).val();
            var inputIdMedico = document.querySelector("#idMedico");
            var inputNome = document.querySelector("#nomeMedico");
            var inputCpf = document.querySelector("#cpfMedico");
            var inputNascimento = document.querySelector("#nascimentoMedico");
            var inputLogradouro = document.querySelector("#logradouroMedico");
            var inputNro = document.querySelector("#nroMedico");
            var inputTelefone = document.querySelector("#telefoneMedico");
            var inputEmail = document.querySelector("#emailMedico");
            var inputCrm = document.querySelector("#crmMedico");
            var inputEspecialidade = document.querySelector("#especialidadeMedico");
            var inputRadioYes = document.querySelector("#sim");
            var inputRadioNo = document.querySelector("#nao");
            var inputLogin = document.querySelector("#loginMedico");

            $.getJSON("listamedicos.php?id="+id,
            function(dados){
                for (i=0;i<dados.length;i++){
                    inputIdMedico.value = dados[i].id;
                    inputNome.value = dados[i].nomeCompleto;
                    inputCpf.value = dados[i].cpf;
                    inputNascimento.value = dados[i].dataNascimento;
                    inputLogradouro.value = dados[i].logradouro;
                    inputNro.value = dados[i].numero;
                    inputTelefone.value = dados[i].telefone;
                    inputEmail.value = dados[i].email;
                    inputCrm.value = dados[i].crm;
                    inputEspecialidade.value = dados[i].especialidade;
                    inputLogin.value = dados[i].login;
                    if (dados[i].permite_req == 0){
                        inputRadioNo.value = dados[i].permite_req;
                        inputRadioNo.checked = true;
                        inputRadioYes.checked = false;
                    }
                    if (dados[i].permite_req == 1){
                        inputRadioYes.value = dados[i].permite_req
                        inputRadioNo.checked = false;
                        inputRadioYes.checked = true;
                    }
                }
            });

            inputNome.focus();
        }
    </script>        


    <?php 
    function listarMedicos(){
        require_once "database.php";
        $lista = [];
        try {
            $conexao = Conexao::getConexao();
            $stmt = $conexao->prepare("SELECT * FROM medico ORDER BY nomeCompleto");
            $stmt->execute();
            $lista = $stmt->fetchAll();
        } catch (Exception $th) {
            echo $th->getMessage();
            exit;
        }
        return $lista;
    }

    if (isset($_POST['nome']) and !empty($_POST['nome']) and
    isset($_POST['cpf']) and !empty($_POST['cpf']) and
    isset($_POST['nascimento']) and !empty($_POST['nascimento']) and 
    isset($_POST['logradouro']) and !empty($_POST['logradouro']) and
    isset($_POST['nro']) and !empty($_POST['nro']) and
    isset($_POST['telefone']) and !empty($_POST['telefone']) and
    isset($_POST['email']) and !empty($_POST['email']) and
    isset($_POST['crm']) and !empty($_POST['crm']) and
    isset($_POST['especialidade']) and !empty($_POST['especialidade']) and
    isset($_POST['login']) and !empty($_POST['login']) ){
        $id = $_POST["idMedico"];
        $nome = $_POST['nome'];
        $cpf = $_POST['cpf'];
        $nascimento = $_POST['nascimento'];
        $logradouro = $_POST['logradouro'];
        $nro = $_POST['nro'];
        $telefone = $_POST['telefone'];
        $email = $_POST['email'];
        $crm = $_POST['crm'];
        $especialidade = $_POST['especialidade'];
        $permitirAltoCusto = $_POST["permite_req"];
        $login = $_POST['login'];
        try {
            require_once "database.php";
            $conexao = Conexao::getConexao();

            //Verifica se o médico possui solicitações em seu nome, se houver, a edição não será permitida
            $stmt = $conexao->prepare('SELECT * FROM pedido WHERE idMedico = ?');
            $stmt->execute([$id]);
            if ($stmt->rowCount() > 0){
                echo "<meta HTTP-EQUIV='refresh' CONTENT='3'>";
                echo '<script type="text/javascript">toastr.warning("Existem solicitações realizadas por este médico, Edição não permitida!")</script>';
            }else{
                //Checa os campos com índice UNIQUE, se houver duplicidade não deixa editar
                $stmt = $conexao->prepare('SELECT nomeCompleto 
                FROM medico 
                WHERE (cpf = ? OR telefone = ? OR email = ? OR crm = ? OR login = ?) and id not in (select id from medico where id =?) ');
                $stmt->execute([$cpf, $telefone, $email, $crm, $login,$id]);
                $nomeCompleto = $stmt->fetch();
                if ($stmt->rowCount() > 0) 
                    echo '<script type="text/javascript">
                    toastr.warning("Médico(a) ' . $nomeCompleto['nomeCompleto'] . 
                    ' já possui um dos campos <b>(*)</b> cadastrado!")</script>';            
                //Se não houver duplicidade de campos UNIQUE permite a edição
                else {
                    $stmt = $conexao->prepare('UPDATE medico set login=?, nomeCompleto=?, cpf=?, crm=?,permite_req=?,especialidade=?,
                    dataNascimento=?,logradouro=?,numero=?,telefone=?,email=? where id=?');
                    $stmt->execute([$login,$nome,$cpf,$crm,$permitirAltoCusto,$especialidade,$nascimento,$logradouro,$nro,$telefone,$email,$id]);
                    if ($stmt->rowCount() > 0){
                        echo "<meta HTTP-EQUIV='refresh' CONTENT='3'>";
                        echo '<script type="text/javascript">toastr.success("Cadastro médico alterado com sucesso!")</script>';
                    }
                    else{
                        echo "<meta HTTP-EQUIV='refresh' CONTENT='3'>";
                        echo '<script type="text/javascript">toastr.warning("Houve algum problema na edição!")</script>';
                    }
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