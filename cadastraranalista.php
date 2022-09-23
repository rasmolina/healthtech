<?php include "session.php";

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
    <title>Health Tech</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.9.3/css/bulma.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css">
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
</head>
<script>
    function validaCPF(){
        var cpf = document.querySelector("#cpfAnalista").value;
        var nascimento = document.querySelector("#nascimentoAnalista");
        inputCpf = document.querySelector("#cpfAnalista");
        var resultado = "";
        $.getJSON("cpfanalista.php?cpf="+cpf,
            function(dados){
                for (i=0;i<1;i++){
                    resultado = dados;
                    if(resultado == cpf){
                        toastr.warning("O CPF informado já consta no banco de dados!");
                        inputCpf.value = "";
                        inputCpf.focus();
                    }else{
                        nascimento.focus();
                    }
                }
        });
    }

    function validaLogin(){
        var login = document.querySelector("#loginAnalista").value;
        var inputSenha = document.querySelector("#senhaAnalista");
        inputLogin = document.querySelector("#loginAnalista");
        var resultado = "";
        $.getJSON("loginanalista.php?login="+login,
            function(dados){
                for (i=0;i<1;i++){
                    resultado = dados;
                    if(resultado == login){
                        toastr.warning("O login informado já consta no banco de dados!");
                        inputLogin.value = "";
                        inputLogin.focus();
                    }else{
                        inputSenha.focus();
                    }
                }
        });
    }

    function validaTelefone(){
    var telefone = document.querySelector("#telefoneAnalista").value;
    var inputEmail = document.querySelector("#emailAnalista");
    inputTelefone = document.querySelector("#telefoneAnalista");
    var resultado = "";
    $.getJSON("telefoneanalista.php?telefone="+telefone,
        function(dados){
            for (i=0;i<1;i++){
                resultado = dados;
                if(resultado == telefone){
                    toastr.warning("O telefone informado já consta no banco de dados!");
                    inputTelefone.value = "";
                    inputTelefone.focus();
                }else{
                    inputEmail.focus();
                }
            }
    });
}

function validaEmail(){
    var email = document.querySelector("#emailAnalista").value;
    var inputLogin = document.querySelector("#loginAnalista");
    inputEmail = document.querySelector("#emailAnalista");
    var resultado = "";
    $.getJSON("emailanalista.php?email="+email,
        function(dados){
            for (i=0;i<1;i++){
                resultado = dados;
                if(resultado == email){
                    toastr.warning("O email informado já consta no banco de dados!");
                    inputEmail.value = "";
                    inputEmail.focus();
                }else{
                    inputLogin.focus();
                }
            }
    });
}
</script>


<body>
    <?php     
    if ($_SESSION['logged'])
        include "menulogado.php";
    else
        include "menu.php";
    ?>

    <header class="has-text-centered">
        <div class="block">
            <h2 class="title is-2">Health Tech</h2>
            <h4 class="title is-4">Cadastrar Analista</h4>
        </div>
    </header>

    <main>

        <!-- Área central da página -->
        <div class="column is-half is-offset-one-quarter">
                <section id="cadastrarAnalista">
                    <form class="box" method="POST" id="form"> 
                        <div class="field">
                            <label class="label">Nome completo</label>
                            <div class="control">
                                <input autofocus type="text" class="input" id="nomeAnalista" name="nome" required>
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">CPF (* valor único)</label>
                            <div class="control">
                                <input type="text" class="input" id="cpfAnalista" name="cpf" onblur="validaCPF()" maxlength=14 required>
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">Data de Nascimento</label>
                            <div class="control">
                                <input type="date" class="input" id="nascimentoAnalista" name="nascimento" required>
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">Logradouro</label>
                            <div class="control">
                                <input type="text" class="input" id="logradouroAnalista" name="logradouro" required>
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">Número</label>
                            <div class="control">
                                <input type="int" class="input" id="nroAnalista" name="nro" required>
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">Telefone</label>
                            <div class="control">
                                <input type="text" class="input" id="telefoneAnalista" name="telefone" onblur="validaTelefone()" required>
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">E-mail (*)</label>
                            <div class="control">
                                <input type="email" class="input" id="emailAnalista" name="email" onblur="validaEmail()" required>
                            </div>
                        </div>
                        <div class="field">
                            <label class="label">Login (*)</label>
                            <div class="control">
                                <input type="text" class="input" id="loginAnalista" name="login" onblur="validaLogin()" required>
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">Senha</label>
                            <div class="control">
                                <input type="password" class="input" id="passworAnalista" name="password" required>
                            </div>
                        </div>

                        <div class="buttons">
                            <div class="control">
                                <button type="submit" class="button is-success" >Cadastrar</button>
                                <button type="button" class="button is-link" onclick="window.location.href='index.php'">Cancelar</button>
                            </div>
                        </div>
                        
                    </form>
                </section>
            </div>
    </main>

    <?php include "footer.php" ?>

</body>

<?php
if (isset($_POST['nome']) and !empty($_POST['nome']) and
    isset($_POST['cpf']) and !empty($_POST['cpf']) and
    isset($_POST['nascimento']) and !empty($_POST['nascimento']) and 
    isset($_POST['logradouro']) and !empty($_POST['logradouro']) and
    isset($_POST['nro']) and !empty($_POST['nro']) and
    isset($_POST['telefone']) and !empty($_POST['telefone']) and
    isset($_POST['email']) and !empty($_POST['email']) and
    isset($_POST['login']) and !empty($_POST['login']) and
    isset($_POST['password']) and !empty($_POST['password'])) {
        try {
            require_once "database.php";
            $conexao = Conexao::getConexao();

            $cpf = $_POST['cpf'];
            $telefone = $_POST['telefone'];
            $email = $_POST['email'];
            $login = $_POST['login'];

            $stmt = $conexao->prepare('SELECT nomeCompleto 
            FROM analista 
            WHERE cpf = ? OR telefone = ? OR email = ? OR login = ?');
            $stmt->execute([$cpf, $telefone, $email, $login]);
            $nomeCompleto = $stmt->fetch();
            if ($stmt->rowCount() > 0) 
                echo '<script type="text/javascript">
                    toastr.warning("Analista ' . $nomeCompleto['nomeCompleto'] . 
                    ' já possui um dos campos <b>(*)</b> cadastrado!")</script>';
            else {
                $classeUsuario = "ana";
                $nome = $_POST['nome'];
                $nascimento = $_POST['nascimento'];
                $logradouro = $_POST['logradouro'];
                $nro = $_POST['nro'];
                $senha = password_hash($_POST['password'],PASSWORD_BCRYPT,["cost"=>11]); //criptografia da senha
                $stmt = $conexao->prepare('INSERT INTO analista(classeUsuario, login, senha, nomeCompleto, cpf, 
                    dataNascimento, logradouro, numero, telefone, email) values 
                    (:p1,:p2,:p3,:p4,:p5,:p6,:p7,:p8,:p9,:p10)');
                $stmt->execute([
                    "p1" => $classeUsuario,
                    "p2" => $login,
                    "p3" => $senha,
                    "p4" => $nome,
                    "p5" => $cpf,
                    "p6" => $nascimento,
                    "p7" => $logradouro,
                    "p8" => $nro,
                    "p9" => $telefone,
                    "p10" => $email
                ]);
                echo '<script type="text/javascript">toastr.success("Analista cadastrado com sucesso!")</script>';
        }
        } catch (Exception $th) {
            echo json_encode(array("msg" => $th->getMessage()));
            exit;
        }
    }
?>

</html>