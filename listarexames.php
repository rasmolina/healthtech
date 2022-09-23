<?php include 'session.php';

//se o usuário não estiver logado não acessará esta página e será redirecionado para a página principal
$logged = $_SESSION['logged'] ?? NULL;
$tipoUsuario = $_SESSION['tipoUsuario'] ?? NULL;
if (!$logged) die('Acesso permitido apenas para o supervisor!');

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Exames</title>
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
            <h4 class="title is-4">Lista de Exames Disponíveis</h4>
        </div>
    </header>

    <main>
    
    <div class="column is-1"></div>
    <div class="has-text-centered">
        <div class="column is-half is-offset-one-quarter">
            <?php gerarListaExames(); ?>
        </div>
    </div>
    </main>

    <?php 

    function gerarListaExames(){
        require_once "database.php";
        try {
            $conexao = Conexao::getConexao();
            $stmt = $conexao->prepare("SELECT * from exame order by nome");
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                echo '<table class="table is-fullwidth is-hoverable">';
                echo '<thead>';
                echo '<tr>';
                echo "<th>Exame</th>";
                echo "<th>Alto Custo</th>";
                echo "</tr>";
                echo "</thead>";
                echo "<tbody>";
                foreach ($stmt as $linha) {
                    if ($linha["altocusto"] == 0)
                        $altoCusto = 'Não';
                    else
                        $altoCusto = 'Sim';
                    echo "<tr>";
                    echo "<td>" . $linha["nome"] . "</td>";
                    echo "<td>" . $altoCusto . "</td>";
                    echo "</tr>";
                }
                echo '</tbody>';
                echo '</table>';
            }else{
                echo '<script type="text/javascript">toastr.warning("Não há exames cadastrados!")</script>';
            }
        } catch (Exception $th) {
            echo $th->getMessage();
            exit;
        }
    }
    
    ?>
    
<?php include "footer.php"; ?>

</body>

</html>