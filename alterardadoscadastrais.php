<?php include 'session.php';

//se o usuário não estiver logado não acessará esta página e será redirecionado para a página principal
$logged = $_SESSION['logged'] ?? NULL;
$inputId = $_SESSION['idUser'];
$inputClasse = $_SESSION['tipoUsuario'];
if (!$logged) die('Acesso negado!');

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alterar Dados Cadastrais</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.9.3/css/bulma.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css">
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
</head>
<?php
if ($inputClasse == "sup")
    $url = "listasupervisores.php?id=";
if ($inputClasse == "med")
    $url = "listamedicos.php?id=";
if ($inputClasse == "ana")
    $url = "listaanalistas.php?id=";
if ($inputClasse == "pac")
    $url = "listapacientes.php?id=";
?>

<script>
    function carregaInput(id){

        var inputNome = document.querySelector("#nome");
        var inputNascimento = document.querySelector("#dataNascimento");
        var inputLogradouro = document.querySelector("#logradouro");
        var inputNumero = document.querySelector("#nro");
        var inputFone = document.querySelector("#telefone");
        var inputEmail = document.querySelector("#email");

        let url = "<?php echo $url.$inputId; ?>";
        
        $.getJSON(url,
        function(dados){
            inputNome.value = dados[0].nomeCompleto;
            inputNascimento.value = dados[0].dataNascimento;
            inputLogradouro.value = dados[0].logradouro;
            inputNumero.value = dados[0].numero;
            inputFone.value = dados[0].telefone;
            inputEmail.value = dados[0].email;
        });

        
    }
    
    $(document).ready( function () {
        var id = <?php echo $inputId; ?>;
        carregaInput(id);
    });
</script>
<body>
    <?php include_once "menulogado.php" ?>

    <header class = "has-text-centered">
    <div    class = "block">
    <h2     class = "title is-2">Health Tech</h2>
    <h4     class = "title is-4">Alteração de dados pessoais</h4>
        </div>
    </header>

    <main>

        <!-- Área central da página -->
            <div class="column is-half is-offset-one-quarter">
                <section id="alterarDadosPessoais">
                    <form class="box" method="POST" id="form"> 
                        <div class="field">
                            <label class="label">Nome completo</label>
                            <div class="control">
                                <input autofocus type="text" class="input" id="nome" name="nome" required>
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">Data de Nascimento</label>
                            <div class="control">
                                <input type="date" class="input" id="dataNascimento" name="dataNascimento" required>
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">Logradouro</label>
                            <div class="control">
                                <input type="text" class="input" id="logradouro" name="logradouro" required>
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">Número</label>
                            <div class="control">
                                <input type="int" class="input" id="nro" name="nro" required>
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">Telefone (*)</label>
                            <div class="control">
                                <input type="text" class="input" id="telefone" name="telefone" required>
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">E-mail (*)</label>
                            <div class="control">
                                <input type="email" class="input" id="email" name="email" required>
                            </div>
                        </div>

                        <div class="buttons">
                            <div class="control">
                                <button type="submit" class="button is-success" >Atualizar</button>
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

require_once "database.php";

if (isset($_POST['nome']) and !empty($_POST['nome']) and
    isset($_POST['logradouro']) and !empty($_POST['logradouro']) and
    isset($_POST['nro']) and !empty($_POST['nro']) and
    isset($_POST['telefone']) and !empty($_POST['telefone']) and
    isset($_POST['dataNascimento']) and !empty($_POST['dataNascimento']) and
    isset($_POST['email']) and !empty($_POST['email']) ) {
        $loggedType = $_SESSION['tipoUsuario'];
        $id = $_SESSION['idUser'];
        $nome = $_POST['nome'];
        $logradouro = $_POST['logradouro'];
        $numero = $_POST['nro'];
        $telefone = $_POST['telefone'];
        $email = $_POST['email'];
        $dataNascimento = $_POST['dataNascimento'];
        try {
            $conexao = Conexao::getConexao();

            //verificação para supervisor---------------------------------------------------------------
            if ($loggedType == 'sup'){
                $stmt = $conexao->prepare('SELECT nomeCompleto FROM supervisor WHERE (telefone = ? OR email = ?) and id not in (select id from supervisor where id =?) ');
                $stmt->execute([$telefone, $email,$id]);
                $nomeCompleto = $stmt->fetch();
                if ($stmt->rowCount() > 0) 
                    echo '<script type="text/javascript">
                    toastr.warning("Usuário ' . $nomeCompleto['nomeCompleto'] . 
                    ' já possui um dos campos <b>(*)</b> cadastrado!")</script>';            
                else {
                    try {
                        $stmt = $conexao->prepare("UPDATE supervisor SET nomeCompleto=?,logradouro=?,dataNascimento=?,numero=?,telefone=?,email=?  WHERE id=?");
                        $stmt->execute([$nome,$logradouro,$dataNascimento,$numero,$telefone,$email,$id]);
                        echo '<script type="text/javascript">toastr.succes("Dados alterados com sucesso!")</script>';
                        echo "<meta HTTP-EQUIV='refresh' CONTENT='2';url=index.php>";
                    } catch (Exception $th) {
                        echo '<script type="text/javascript">toastr.warning("Houve algum problema na edição!")</script>';
                        //echo json_encode(array("msg" => $th->getMessage()));
                        exit;
                    }
                }
            }
            //---------------------------------------------------------------

            //verificação para médico---------------------------------------------------------------
            if ($loggedType == 'med'){
                $stmt = $conexao->prepare('SELECT nomeCompleto FROM medico WHERE (telefone = ? OR email = ?) and id not in (select id from medico where id =?) ');
                $stmt->execute([$telefone, $email,$id]);
                $nomeCompleto = $stmt->fetch();
                if ($stmt->rowCount() > 0) 
                    echo '<script type="text/javascript">
                    toastr.warning("Usuário ' . $nomeCompleto['nomeCompleto'] . 
                    ' já possui um dos campos <b>(*)</b> cadastrado!")</script>';            
                else {
                    try {
                        $stmt = $conexao->prepare("UPDATE medico SET nomeCompleto=?,logradouro=?,dataNascimento=?,numero=?,telefone=?,email=?  WHERE id=?");
                        $stmt->execute([$nome,$logradouro,$dataNascimento,$numero,$telefone,$email,$id]);
                        echo '<script type="text/javascript">toastr.succes("Dados alterados com sucesso!")</script>';
                        echo "<meta HTTP-EQUIV='refresh' CONTENT='2';url=index.php>";
                    } catch (Exception $th) {
                        echo '<script type="text/javascript">toastr.warning("Houve algum problema na edição!")</script>';
                        //echo json_encode(array("msg" => $th->getMessage()));
                        exit;
                    }
                }
            }
            //---------------------------------------------------------------

            //verificação para analista---------------------------------------------------------------
            if ($loggedType == 'ana'){
                $stmt = $conexao->prepare('SELECT nomeCompleto FROM analista WHERE (telefone = ? OR email = ?) and id not in (select id from analista where id =?) ');
                $stmt->execute([$telefone, $email,$id]);
                $nomeCompleto = $stmt->fetch();
                if ($stmt->rowCount() > 0) 
                    echo '<script type="text/javascript">
                    toastr.warning("Usuário ' . $nomeCompleto['nomeCompleto'] . 
                    ' já possui um dos campos <b>(*)</b> cadastrado!")</script>';            
                else {
                    try {
                        $stmt = $conexao->prepare("UPDATE analista SET nomeCompleto=?,logradouro=?,dataNascimento=?,numero=?,telefone=?,email=?  WHERE id=?");
                        $stmt->execute([$nome,$logradouro,$dataNascimento,$numero,$telefone,$email,$id]);
                        echo '<script type="text/javascript">toastr.succes("Dados alterados com sucesso!")</script>';
                        echo "<meta HTTP-EQUIV='refresh' CONTENT='2';url=index.php>";
                    } catch (Exception $th) {
                        echo '<script type="text/javascript">toastr.warning("Houve algum problema na edição!")</script>';
                        //echo json_encode(array("msg" => $th->getMessage()));
                        exit;
                    }
                }
            }
            //---------------------------------------------------------------

            //verificação para paciente---------------------------------------------------------------
            if ($loggedType == 'pac'){
                $stmt = $conexao->prepare('SELECT nomeCompleto FROM paciente WHERE (telefone = ? OR email = ?) and id not in (select id from paciente where id =?) ');
                $stmt->execute([$telefone, $email,$id]);
                $nomeCompleto = $stmt->fetch();
                if ($stmt->rowCount() > 0) 
                    echo '<script type="text/javascript">
                    toastr.warning("Usuário ' . $nomeCompleto['nomeCompleto'] . 
                    ' já possui um dos campos <b>(*)</b> cadastrado!")</script>';            
                else {
                    try {
                        $stmt = $conexao->prepare("UPDATE paciente SET nomeCompleto=?,logradouro=?,dataNascimento=?,numero=?,telefone=?,email=?  WHERE id=?");
                        $stmt->execute([$nome,$logradouro,$dataNascimento,$numero,$telefone,$email,$id]);
                        echo '<script type="text/javascript">toastr.succes("Dados alterados com sucesso!")</script>';
                        echo "<meta HTTP-EQUIV='refresh' CONTENT='2';url=index.php>";
                    } catch (Exception $th) {
                        echo '<script type="text/javascript">toastr.warning("Houve algum problema na edição!")</script>';
                        //echo json_encode(array("msg" => $th->getMessage()));
                        exit;
                    }
                }
            }
            //---------------------------------------------------------------


        } catch (Exception $th) {
            echo json_encode(array("msg" => $th->getMessage()));
            exit;
        }
}

?>