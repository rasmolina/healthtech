<?php include "session.php" ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sobre</title>
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
            <h4 class="title is-4">Soluções Digitais para Laboratórios</h4>
        </div>
    </header>

    <main>
    
    <div class="column is-1"></div>
    <div class="box" style="text-align: justify">
        <p><b>SOBRE O SISTEMA</b></p>
        <p>O Health Tech é um sistema desenvolvido para o gerenciamento laboratorial. Permite diferentes níveis de acesso com diferentes tipos de permissões. Um supervisor tem acesso pleno ao sistema, ele que realiza o cadastro dos demais usuários oferecendo um login e senha padrão, a senha poderá depois ser alterada pelo próprio usuário. Um médico pode realizar pedido de exames e visualizar o laudo de qualquer paciente. Cada pedido ou requisição pode conter até 10 exames, e cada exame terá seu laudo, sua hora de solicitação e de realização, bem como a situação em que se encontra (produção ou liberado). Alguns exames são classificados como alto custo, e somente médicos com a permissão podem solicitar este tipo de exame. Após a solicitação, o status do exame é “em produção”. Uma vez que o exame é realizado, o analista libera o exame incluindo o resultado no laudo, e o status passa a ser “liberado”, podendo ser visualizado pelo médico, paciente ou analista. Somente o analista consegue cadastrar e remover laudos. Um paciente só consegue acessar os seus próprios resultados de exames.</p>
    </div>
    
    <div class="column is-1"></div>
    <div class="box" style="text-align: justify">
        <p><b>ESTRUTURAÇÃO</b></p>
        <p>O projeto foi desenvolvido na linguagem PHP utilizando recursos de Java Script, JQuery CSS e do framework Bulma. Os menus são dinâmicos. Há um menu padrão para sessão não logada e um menu exclusivo para sessão logada (acesso restrito). Na sessão logada, a cor da barra de menu bem como seus itens serão habilitados ou desabilitados de acordo com o nível de acesso do usuário. 
</p>
    </div>

    <div class="column is-1"></div>
    <div class="box" style="text-align: justify">
        <p><b>PERMISSÕES E RESTRIÇÕES</b></p><br>
        <p>PACIENTE</p>
        <p>Poderá alterar seus dados pessoais, sua senha e visualizar seus próprios laudos. Seu prontuário é único e intransferível, não pode ser editado. Poderá ver a lista de exames disponíveis. Se um paciente possuir pedidos de exames em seu nome, não será permitido editar ou excluir seu cadastro.</p><br>
        <p>ANALISTA</p>
        <p>Poderá alterar seus dados pessoais, sua senha, visualizar a lista de exames disponíveis, consultar laudos, cadastrar, editar e remover exames bem como incluir e remover laudos. Se um analista possuir pedidos laudados, não será permitido editar ou excluir seu cadastro. Um laudo não pode ser editado. </p><br>
        <p>MÉDICO</p>
        <p>Poderá alterar seus dados pessoais, sua senha, visualizar a lista de exames disponíveis, visualizar laudos, solicitar exames, visualizar e remover pedidos. Um pedido não pode ser editado e só poderá ser removido se o(s) exame(s) ainda não tiver sido realizado, ou seja, se ainda estiver em produção. Alguns médicos não possuem a permissão para solicitar exames de alto custo. Em cada pedido só é permitido solicitar 10 exames. Se houver solicitação em nome do médico, não será permitido editar ou excluir seu cadastro.</p><br>
        <p>SUPERVISOR</p>
        <p>É único e tem acesso pleno a todas funcionalidades do sistema. Ele é o único usuário capaz de cadastrar, editar e remover outros usuários.</p><br>
    </div>


    </main>

<?php include "footer.php" ?>    

</body>


</html>