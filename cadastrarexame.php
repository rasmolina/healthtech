<?php include "session.php";

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
            <h4 class="title is-4">Cadastrar Exame</h4>
        </div>
    </header>

    <main>

        <!-- Área central da página -->
        <div class="column is-half is-offset-one-quarter">
                <section id="cadastrarExame">
                    <form class="box" method="POST" id="form"> 
    
                        <div class="field">
                            <label class="label">Nome do exame</label>
                            <div class="control">
                                <input autofocus type="text" class="input" id="nomeExame" name="nome" required>
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">Exame de alto custo?</label>
                            <div class="control">
                                <label class="radio">
                                <input type="radio" id="sim" name="altoCusto" value=1>
                                Sim
                                </label>
                                <label class="radio">
                                <input type="radio" id="nao" name="altoCusto" value=0 checked>
                                Não
                                </label>
                            </div>
                        </div>

                        <div class="buttons">
                            <div class="control">
                                <button type="submit" class="button is-success">Cadastrar</button>
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
if (isset($_POST['nome']) and !empty($_POST['nome'])) {
        try {
            require_once "database.php";
            $conexao = Conexao::getConexao();

            $nome = $_POST['nome'];

            $stmt = $conexao->prepare('SELECT nome FROM exame WHERE nome = ?');
            $stmt->execute([$nome]);
            $nomeExame = $stmt->fetch();
            if ($stmt->rowCount() > 0) 
                echo '<script type="text/javascript">
                    toastr.warning("O exame ' . $nomeExame['nome'] . 
                    ' já está cadastrado no sistema!")</script>';
            else {
                $altoCusto = $_POST["altoCusto"];

                $stmt = $conexao->prepare('INSERT INTO exame(nome, altocusto) values 
                    (:p1,:p2)');
                $stmt->execute([
                    "p1" => $nome,
                    "p2" => $altoCusto
                ]);
                echo '<script type="text/javascript">toastr.success("Exame cadastrado com sucesso!")</script>';
            }
        } catch (Exception $th) {
            echo json_encode(array("msg" => $th->getMessage()));
            exit;
        }
    }
?>

</html>