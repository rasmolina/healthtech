<?php include "session.php";

$logged = $_SESSION['logged'] ?? NULL;
$tipoUsuario = $_SESSION['tipoUsuario'] ?? NULL;
$idPaciente = $_SESSION['idUser'] ?? NULL;
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
</head>
<style>
    .fullwidth{
        width: 35%;
        margin-bottom: 5px;
    }

    .input{
        width: 20%;
    }

    .rightbutton{
        float: right;
        margin-left: 7px;
    }

    .table{
        text-align: center;
        align-items: center;
        align: center;
        justify-content: center;
        vertical-align: middle;
        height: 50px; 
        width: 50%;
    }

    td{
        height: 50px; 
        width: 50px;
        text-align:center;
        vertical-align:middle;
    }

  
</style>

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
            <h4 class="title is-4" id="titulo">Escolha um pedido para visualizar os resultados de exames</h4>
        </div>
    </header>

    <main>
        <?php

            if ($_SESSION['tipoUsuario'] == "pac")
                include "pacientevisualizalaudo.php";
            else
                include "naopacientevisualizalaudo.php"; //médicos, supervisores e analistas
        ?>

    </main>

    <?php include "footer.php" ?>

</body>


</html>