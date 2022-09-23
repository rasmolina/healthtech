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
    <title>Edição de Pacientes</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.9.3/css/bulma.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css">
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
</head>

<body>
    <?php include_once "menulogado.php" ?>

    <header class="has-text-centered">
        <div class="block">
            <h2 class="title is-2">Health Tech</h2>
            <h4 class="title is-4">Alterar Paciente</h4>
        </div>
    </header>

    <main>
    <div class="column is-1"></div>
    <div class="has-text-centered">
    <form method="GET" id="selectForm">
        <div class="select is-link">
        <select name="listaDePacientes" id="listaDePacientes" onchange="exibeForm()">
            <option active>Selecione um(a) paciente para alterar o cadastro</option>
            <?php
                $lista = listarPacientes();
                for ($i=0; $i<count($lista); $i++) {
                    $id = $lista[$i]["id"];
                    $nomePaciente = $lista[$i]["nomeCompleto"];
                    echo "<option value='$id'>$nomePaciente</option>";
                }
            ?>
        </select>
        </div>
    </form>
    </div>

    <div class="column is-half is-offset-one-quarter">
    <section id="editarPaciente">
        <form class="box" method="POST" id="editForm" name="editForm" style="display: none;">
            <div class="field">
                <input type="hidden" id="idPaciente" name="idPaciente">
                <label class="label">Nome completo</label>
                <div class="control">
                    <input autofocus type="text" class="input" id="nomePaciente" name="nome" required>
                </div>
            </div>

            <div class="field">
                <label class="label">CPF (* valor único)</label>
                <div class="control">
                    <input type="text" class="input" id="cpfPaciente" name="cpf" maxlength=14 required>
                </div>
            </div>

            <div class="field">
                <label class="label">Data de Nascimento</label>
                <div class="control">
                    <input type="date" class="input" id="nascimentoPaciente" name="nascimento" required>
                </div>
            </div>

            <div class="field">
                <label class="label">Logradouro</label>
                <div class="control">
                    <input type="text" class="input" id="logradouroPaciente" name="logradouro" required>
                </div>
            </div>

            <div class="field">
                <label class="label">Número</label>
                <div class="control">
                    <input type="int" class="input" id="nroPaciente" name="nro" required>
                </div>
            </div>

            <div class="field">
                <label class="label">Telefone (*)</label>
                <div class="control">
                    <input type="text" class="input" id="telefonePaciente" name="telefone" maxlength=14 required>
                </div>
            </div>

            <div class="field">
                <label class="label">E-mail (*)</label>
                <div class="control">
                    <input type="email" class="input" id="emailPaciente" name="email" required >
                </div>
            </div>

            <div class="field">
                <label class="label">Login (*)</label>
                <div class="control">
                    <input type="text" class="input" id="loginPaciente" name="login" required>
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
            var id = $( "#listaDePacientes option:selected" ).val();
            var inputIdPaciente = document.querySelector("#idPaciente");
            var inputNome = document.querySelector("#nomePaciente");
            var inputCpf = document.querySelector("#cpfPaciente");
            var inputNascimento = document.querySelector("#nascimentoPaciente");
            var inputLogradouro = document.querySelector("#logradouroPaciente");
            var inputNro = document.querySelector("#nroPaciente");
            var inputTelefone = document.querySelector("#telefonePaciente");
            var inputEmail = document.querySelector("#emailPaciente");
            var inputLogin = document.querySelector("#loginPaciente");

            $.getJSON("listapacientes.php?id="+id,
            function(dados){
                for (i=0;i<dados.length;i++){
                    inputIdPaciente.value = dados[i].id;
                    inputNome.value = dados[i].nomeCompleto;
                    inputCpf.value = dados[i].cpf;
                    inputNascimento.value = dados[i].dataNascimento;
                    inputLogradouro.value = dados[i].logradouro;
                    inputNro.value = dados[i].numero;
                    inputTelefone.value = dados[i].telefone;
                    inputEmail.value = dados[i].email;
                    inputLogin.value = dados[i].login;
                }
            });

            inputNome.focus();
        }
    </script>        


    <?php 
    function listarPacientes(){
        require_once "database.php";
        $lista = [];
        try {
            $conexao = Conexao::getConexao();
            $stmt = $conexao->prepare("SELECT * FROM paciente ORDER BY nomeCompleto");
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
    isset($_POST['login']) and !empty($_POST['login']) ){
        $id = $_POST["idPaciente"];
        $nome = $_POST['nome'];
        $cpf = $_POST['cpf'];
        $nascimento = $_POST['nascimento'];
        $logradouro = $_POST['logradouro'];
        $nro = $_POST['nro'];
        $telefone = $_POST['telefone'];
        $email = $_POST['email'];
        $login = $_POST['login'];
        try {
            require_once "database.php";
            $conexao = Conexao::getConexao();

            //Verifica se o Paciente possui solicitações em seu nome, se houver, a edição não será permitida
            $stmt = $conexao->prepare('SELECT * FROM pedido WHERE idPaciente = ?');
            $stmt->execute([$id]);
            if ($stmt->rowCount() > 0){
                echo "<meta HTTP-EQUIV='refresh' CONTENT='3'>";
                echo '<script type="text/javascript">toastr.warning("Existem pedidos de exames pendentes para este paciente, edição não permitida!")</script>';
            }else{
                //Checa os campos com índice UNIQUE, se houver duplicidade não deixa editar
                $stmt = $conexao->prepare('SELECT nomeCompleto 
                FROM paciente 
                WHERE (cpf = ? OR telefone = ? OR email = ? OR login = ?) and id not in (select id from paciente where id =?) ');
                $stmt->execute([$cpf, $telefone,$email,$login,$id]);
                $nomeCompleto = $stmt->fetch();
                if ($stmt->rowCount() > 0) 
                    echo '<script type="text/javascript">
                    toastr.warning("Paciente ' . $nomeCompleto['nomeCompleto'] . 
                    ' já possui um dos campos <b>(*)</b> cadastrado!")</script>';            
                //Se não houver duplicidade de campos UNIQUE permite a edição -- NÃO É PERMITIDO ALTERAR O PRONTUÁRIO DO PACIENTE
                else {
                    $stmt = $conexao->prepare('UPDATE paciente set login=?, nomeCompleto=?, cpf=?,dataNascimento=?,logradouro=?,numero=?,telefone=?,email=? where id=?');
                    $stmt->execute([$login,$nome,$cpf,$nascimento,$logradouro,$nro,$telefone,$email,$id]);
                    if ($stmt->rowCount() > 0){
                        echo "<meta HTTP-EQUIV='refresh' CONTENT='3'>";
                        echo '<script type="text/javascript">toastr.success("Cadastro de paciente alterado com sucesso!")</script>';
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