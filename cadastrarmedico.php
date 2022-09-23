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
            <h4 class="title is-4">Cadastrar Médico</h4>
        </div>
    </header>

    <main>

        <!-- Área central da página -->
            <div class="column is-half is-offset-one-quarter">
                <section id="cadastrarMedico">
                    <form class="box" method="POST" id="form"> 
                        <div class="field">
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

                        <div class="field">
                            <label class="label">Senha</label>
                            <div class="control">
                                <input type="password" class="input" id="passwordMedico" name="password" required>
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
    isset($_POST['crm']) and !empty($_POST['crm']) and
    isset($_POST['especialidade']) and !empty($_POST['especialidade']) and
    isset($_POST['login']) and !empty($_POST['login']) and
    isset($_POST['password']) and !empty($_POST['password'])) {
        try {
            require_once "database.php";
            $conexao = Conexao::getConexao();

            $cpf = $_POST['cpf'];
            $telefone = $_POST['telefone'];
            $email = $_POST['email'];
            $crm = $_POST['crm'];
            $login = $_POST['login'];

            $stmt = $conexao->prepare('SELECT nomeCompleto 
            FROM medico 
            WHERE cpf = ? OR telefone = ? OR email = ? OR crm = ? OR login = ?');
            $stmt->execute([$cpf, $telefone, $email, $crm, $login]);
            $nomeCompleto = $stmt->fetch();
            if ($stmt->rowCount() > 0) 
            echo '<script type="text/javascript">
                toastr.warning("Médico ' . $nomeCompleto['nomeCompleto'] . 
                ' já possui um dos campos <b>(*)</b> cadastrado!")</script>';            
            else {
                $classeUsuario = "med";
                $nome = $_POST['nome'];
                $nascimento = $_POST['nascimento'];
                $logradouro = $_POST['logradouro'];
                $nro = $_POST['nro'];
                $especialidade = $_POST['especialidade'];
                $permitirAltoCusto = $_POST["permite_req"];
                $senha = password_hash($_POST['password'],PASSWORD_BCRYPT,["cost"=>11]); //criptografia da senha

                $stmt = $conexao->prepare('INSERT INTO medico(classeUsuario, login, senha, nomeCompleto, cpf, crm, 
                    permite_req, especialidade, dataNascimento, logradouro, numero, telefone, email) values 
                    (:p1,:p2,:p3,:p4,:p5,:p6,:p7,:p8,:p9,:p10,:p11,:p12,:p13)');
                $stmt->execute([
                    "p1" => $classeUsuario,
                    "p2" => $login,
                    "p3" => $senha,
                    "p4" => $nome,
                    "p5" => $cpf,
                    "p6" => $crm,
                    "p7" => $permitirAltoCusto,
                    "p8" => $especialidade,
                    "p9" => $nascimento,
                    "p10" => $logradouro,
                    "p11" => $nro,
                    "p12" => $telefone,
                    "p13" => $email
                ]);
                echo '<script type="text/javascript">toastr.success("Médico cadastrado com sucesso!")</script>';
            }
        } catch (Exception $th) {
            echo json_encode(array("msg" => $th->getMessage()));
            exit;
        }
    }
?>

</html>