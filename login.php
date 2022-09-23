<?php include 'session.php';

//se o usuário já estiver logado não acessará esta página e será redirecionado para a página principal
$logged = $_SESSION['logged'] ?? NULL;
if ($logged) header('Location: index.php');

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
    <?php include_once "menu.php" ?>

    <header class="has-text-centered">
        <div class="block">
            <h2 class="title is-2">Health Tech</h2>
            <h4 class="title is-4">Soluções Digitais para Laboratórios</h4>
        </div>
    </header>
    
      <div class="column is-half is-offset-one-quarter"> <!-- coluna-->
        <form class="box" method="POST" id="form"> 
        <div class="field"> <!-- campo classe usuário -->
          <label class="label">Sou:</label>
          <div class="control">
          <label class="radio">
          <input type="radio" name="radioEscolhaUsuario" value="sup" checked>
          Supervisor
          </label>
          <label class="radio">
            <input type="radio" name="radioEscolhaUsuario" value="med">
            Médico
          </label>
          <label class="radio">
            <input type="radio" name="radioEscolhaUsuario" value="ana">
            Analista
          </label>
          <label class="radio">
          <input type="radio" name="radioEscolhaUsuario" value="pac">
          Paciente
          </label>
      </div> <!-- fim do campo classe usuário -->

      <div class="field"> <!-- campo login -->
        <label class="label">Login</label>
        <div class="control">
          <input autofocus class="input" type="text" name="login" placeholder="exemplo@email.com">
        </div>
      </div>

        <div class="field"> <!-- campo senha -->
            <label class="label">Senha</label>
            <div class="control">
                <input type="password" class="input" name="senha" required placeholder="Digite sua senha">
            </div>
        </div>
    </form>
    <button type="submit" class="button is-primary">Login</button>

</div>

</div> <!-- fim da coluna-->

    <?php include "footer.php" ?>

</body>


<?php

require_once "database.php";

if (isset($_POST['login']) and !empty($_POST['login']) and
isset($_POST['senha']) and !empty($_POST['senha']) and
isset($_POST['radioEscolhaUsuario']) and !empty($_POST['radioEscolhaUsuario']) ){
 $loggedUser = $_POST['login'];
 $loggedPassword = $_POST['senha'];
 $loggedType = $_POST['radioEscolhaUsuario'];
 
 try {
    //estabelece a conexão
    $conexao = Conexao::getConexao();
    //Verifica o tipo de usuário logado para fazer a verificação na tabela correspondente do BD
    if ($loggedType == 'sup')
      $stmt = $conexao->prepare('SELECT * FROM supervisor where login=? and classeUsuario=?');
    if ($loggedType == 'pac')
      $stmt = $conexao->prepare('SELECT * FROM paciente where login=? and classeUsuario=?');
    if ($loggedType == 'med')
      $stmt = $conexao->prepare('SELECT * FROM medico where login=? and classeUsuario=?');
    if ($loggedType == 'ana')
      $stmt = $conexao->prepare('SELECT * FROM analista where login=? and classeUsuario=?');
      
    $stmt->execute([$loggedUser,$loggedType]);
    if ($stmt->rowCount() > 0) {
        //resgata os dados do banco de dados e os atribui às variáveis
        foreach ($stmt as $user) {
            $IdDB = $user["id"];
            $userDB = $user["login"];
            $passwordDB = $user["senha"];
            $nomeDB = $user["nomeCompleto"];
            $userTypeDB = $user["classeUsuario"];
        }
    }else{
        $userDB = "";
        $passwordDB = "";
    }
    //se houver correspondência de login e senha com o banco de dados, inicia a sessão e implementa seus atributos
    if ($loggedUser == $userDB and password_verify($loggedPassword,$passwordDB)){
        $_SESSION['idUser'] = $IdDB;
        $_SESSION['usuario'] = $userDB;
        $_SESSION['senha'] = $passwordDB;
        $_SESSION['nome'] = $nomeDB;
        $_SESSION['logged'] = true;
        $_SESSION['tipoUsuario'] = $userTypeDB;
        //Oculta o formulário para evitar manipulação indevida durante o início da sessão
        echo '<script type="text/javascript"> document.getElementById("form").style.display = "none";</script>';
        //Mensagem de sucesso no formato toast
        echo '<script type="text/javascript">toastr.success("Você será redirecionado em 3s")</script>';
        echo '<script type="text/javascript">toastr.success("Login realizado com sucesso!")</script>';
        //redirecionamento para a página principal após 2 segundos
        echo "<meta HTTP-EQUIV='refresh' CONTENT='2';url=index.php>";
    }else{
        echo '<script type="text/javascript">toastr.warning("Usuário e/ou senha inválidos!")</script>';
    }
       
 } catch (Exception $th) {
     echo json_encode(array("msg" => $th->getMessage()));
     exit;
 }
}



?>

</html>