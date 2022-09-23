<?php include "session.php" ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fale Conosco</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.9.3/css/bulma.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css">
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
</head>
<body>
    <?php     
    //$_SESSION['logged'] = false; //para teste
    if ($_SESSION['logged'])
        include "menulogado.php";
    else
        include "menu.php";
    ?>

    <header class="has-text-centered">
        <div class="block">
            <h2 class="title is-2">Health Tech</h2>
            <h4 class="title is-4">Fale Conosco!</h4>
        </div>
    </header>

    <main>

        <!-- Área central da página -->
        <div class="column is-half is-offset-one-quarter">
                <section id="faleconosco">
                    <form class="box" method="POST" id="form"> 
                        <div class="field">
                            <label class="label">Nome Completo</label>
                            <div class="control">
                                <input autofocus type="text" class="input" id="nome" name="nome" required>
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">Telefone para contato com DDD</label>
                            <div class="control">
                                <input autofocus type="text" class="input" id="fone" name="fone" required>
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">Email para contato</label>
                            <div class="control">
                                <input autofocus type="email" class="input" id="email" name="email" placeholder="exemplo@mail.com" required>
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">Digite sua mensagem</label>
                            <div class="control">
                                <textarea class="textarea has-fixed-size" ></textarea>
                            </div>
                        </div>

                        <div class="buttons">
                            <div class="control">
                                <button type="button" class="button is-success" onclick="window.location.href='index.php'">Enviar</button>
                            </div>
                        </div>
                        
                    </form>
                </section>
            </div>
    </main>

<?php include "footer.php" ?>    

</body>


</html>