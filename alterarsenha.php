<?php include 'session.php';

//se o usuário não estiver logado não acessará esta página e será redirecionado para a página principal
$logged = $_SESSION['logged'] ?? NULL;
if (!$logged) die('Acesso negado!');

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
    <?php include_once "menulogado.php" ?>

    <header class="has-text-centered">
        <div class="block">
            <h2 class="title is-2">Health Tech</h2>
            <h4 class="title is-4">Soluções Digitais para Laboratórios</h4>
        </div>
    </header>

    <div class="column is-half is-offset-one-quarter"> <!-- coluna-->
        <form class="box" method="POST" id="form"> 
        <div class="field">
            <label class="label">Digite sua senha atual</label>
            <div class="control">
                <input autofocus type="password" class="input" name="senhaatual" required placeholder="Digite sua senha atual">
            </div>
        </div>

        <div class="field"> <!-- campo senha -->
            <label class="label">Digite sua nova senha</label>
            <div class="control">
                <input type="password" class="input" name="novasenha" required placeholder="Digite sua nova senha">
            </div>
        </div>

        <div class="buttons">
            <div class="control">
                <button type="submit" class="button is-primary">Alterar</button>    
                <button type="button" class="button is-link" onclick="window.location.href='index.php'">Cancelar</button>
            </div>
        </div>

    </form>
    


</div> <!-- fim da coluna-->

    <?php include "footer.php" ?>

</body>

<?php

require_once "database.php";

if (isset($_POST['senhaatual']) and !empty($_POST['senhaatual']) and
isset($_POST['novasenha']) and !empty($_POST['novasenha']) ){
    $loggedType = $_SESSION['tipoUsuario'];
    $id = $_SESSION['idUser'];
    $senhaAntiga = $_POST['senhaatual'];
    $senhaNova = $_POST['novasenha'];

    if ($senhaAntiga == $senhaNova){
        echo '<script type="text/javascript">toastr.warning("A senha atual digitada é igual a nova senha!")</script>';
    }else{
        try {
            $conexao = Conexao::getConexao();
       
            //verificação para supervisor---------------------------------------------------------------
            if ($loggedType == 'sup'){
                //Localiza o id no BD e resgata a senha atribuindo seu valor para a variável
                $stmt = $conexao->prepare('SELECT * FROM supervisor where id = ?');
                $stmt->execute([$id]);
                if ($stmt->rowCount() > 0){
                    foreach ($stmt as $user) {
                        $passwordDB = $user["senha"];
                    }
                    //se a senha antiga estiver correta realiza a atualização do BD com a nova senha
                    if (password_verify($senhaAntiga,$passwordDB)){
                        $userNewPassword = password_hash($senhaNova,PASSWORD_BCRYPT,["cost"=>11]); //criptografia da senha 
                        $stmt = $conexao->prepare('UPDATE supervisor SET senha = ? where id=?');
                        $stmt->execute([$userNewPassword,$id]);
                        if ($stmt->rowCount() > 0){
                            echo '<script type="text/javascript"> document.getElementById("form").style.display = "none";</script>';
                            echo '<script type="text/javascript">toastr.success("Senha alterada com sucesso!")</script>';
                            echo "<meta HTTP-EQUIV='refresh' CONTENT='2';url=index.php>";            
                        }
                    }else
                        echo '<script type="text/javascript">toastr.warning("A senha atual está incorreta!")</script>';
                }
                
            }
            //-------------------------------------------------------------------

            //verificação para medico---------------------------------------------------------------
            if ($loggedType == 'med'){
                //Localiza o id no BD e resgata a senha atribuindo seu valor para a variável
                $stmt = $conexao->prepare('SELECT * FROM medico where id = ?');
                $stmt->execute([$id]);
                if ($stmt->rowCount() > 0){
                    foreach ($stmt as $user) {
                        $passwordDB = $user["senha"];
                    }
                    //se a senha antiga estiver correta realiza a atualização do BD com a nova senha
                    if (password_verify($senhaAntiga,$passwordDB)){
                        $userNewPassword = password_hash($senhaNova,PASSWORD_BCRYPT,["cost"=>11]); //criptografia da senha 
                        $stmt = $conexao->prepare('UPDATE medico SET senha = ? where id=?');
                        $stmt->execute([$userNewPassword,$id]);
                        if ($stmt->rowCount() > 0){
                            echo '<script type="text/javascript"> document.getElementById("form").style.display = "none";</script>';
                            echo '<script type="text/javascript">toastr.success("Senha alterada com sucesso!")</script>';
                            echo "<meta HTTP-EQUIV='refresh' CONTENT='2';url=index.php>";
                        }
                    }else
                        echo '<script type="text/javascript">toastr.warning("A senha atual está incorreta!")</script>';
                }
                
            }
            //-------------------------------------------------------------------

            //verificação para analista---------------------------------------------------------------
            if ($loggedType == 'ana'){
                //Localiza o id no BD e resgata a senha atribuindo seu valor para a variável
                $stmt = $conexao->prepare('SELECT * FROM analista where id = ?');
                $stmt->execute([$id]);
                if ($stmt->rowCount() > 0){
                    foreach ($stmt as $user) {
                        $passwordDB = $user["senha"];
                    }
                    //se a senha antiga estiver correta realiza a atualização do BD com a nova senha
                    if (password_verify($senhaAntiga,$passwordDB)){
                        $userNewPassword = password_hash($senhaNova,PASSWORD_BCRYPT,["cost"=>11]); //criptografia da senha 
                        $stmt = $conexao->prepare('UPDATE analista SET senha = ? where id=?');
                        $stmt->execute([$userNewPassword,$id]);
                        if ($stmt->rowCount() > 0){
                            echo '<script type="text/javascript"> document.getElementById("form").style.display = "none";</script>';
                            echo '<script type="text/javascript">toastr.success("Senha alterada com sucesso!")</script>';
                            echo "<meta HTTP-EQUIV='refresh' CONTENT='2';url=index.php>";            
                        }
                    }else
                        echo '<script type="text/javascript">toastr.warning("A senha atual está incorreta!")</script>';
                }
                
            }
            //-------------------------------------------------------------------

            //verificação para paciente---------------------------------------------------------------
            if ($loggedType == 'pac'){
                //Localiza o id no BD e resgata a senha atribuindo seu valor para a variável
                $stmt = $conexao->prepare('SELECT * FROM paciente where id = ?');
                $stmt->execute([$id]);
                if ($stmt->rowCount() > 0){
                    foreach ($stmt as $user) {
                        $passwordDB = $user["senha"];
                    }
                    //se a senha antiga estiver correta realiza a atualização do BD com a nova senha
                    if (password_verify($senhaAntiga,$passwordDB)){
                        $userNewPassword = password_hash($senhaNova,PASSWORD_BCRYPT,["cost"=>11]); //criptografia da senha 
                        $stmt = $conexao->prepare('UPDATE paciente SET senha = ? where id=?');
                        $stmt->execute([$userNewPassword,$id]);
                        echo '<script type="text/javascript"> document.getElementById("form").style.display = "none";</script>';
                        echo '<script type="text/javascript">toastr.success("Senha alterada com sucesso!")</script>';
                        echo "<meta HTTP-EQUIV='refresh' CONTENT='2';url=index.php?logout=1>";            
                    }else
                        echo '<script type="text/javascript">toastr.warning("A senha atual está incorreta!")</script>';
                }
                
            }
            //-------------------------------------------------------------------

        } catch (Exception $th) {
            echo json_encode(array("msg" => $th->getMessage()));
            exit;
        }
    }
        
}

?>