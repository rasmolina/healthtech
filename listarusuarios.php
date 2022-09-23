<?php include 'session.php';

//se o usuário não estiver logado não acessará esta página e será redirecionado para a página principal
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

    footer{
        width: 100%;
        height: 20px;
        margin: auto;
        bottom: 0;
    }
   
</style>
<script>

    function listaUsuarios(classe){
        if (classe == "med")
            var url = "listamedicos.php";
        if (classe == "ana")
            var url = "listaanalistas.php";
        if (classe == "pac")
            var url = "listapacientes.php";
        if (classe == "sup")
            var url = "listasupervisores.php";

        $.getJSON(url,function(dados){

            //REMOVO A TABELA PARA GERAR ELA DO ZERO
            var listaUsuarios = document.getElementById('tabelaUsuarios');
            listaUsuarios.parentNode.removeChild(listaUsuarios);

            //GERO NOVAMENTE A TABELA
            const tabela = document.createElement('table');
            tabela.setAttribute("class","table is-half is-fullwidth table is-hoverable");
            tabela.setAttribute("id","tabelaUsuarios");

            const thead = document.createElement('thead');
            const cab = ['Nome', 'CPF','Data de Nascimento','Login','Informações Adicionais'];
            for(let i = 0; i < cab.length; i++){
                const th = document.createElement('th');
                th.innerText = cab[i];
                thead.appendChild(th);
            }

            tabela.appendChild(thead)
            const tbody = document.createElement('tbody');
            tabela.appendChild(tbody);
            document.querySelector('main').appendChild(tabela);

            //PERCORRO OS REGISTROS RESGATANDO OS VALORES DE CADA CAMPO
            for(let i=0;i<dados.length;i++){ 
                    var id = dados[i].id;
                    
                    var classeUsuario = dados[i].classeUsuario;
                    console.log("Classe dentro do for: " + classeUsuario);
                    var newRow = $("<tr>");
                    var cols = "";
                    const dataFormatada = moment(dados[i].dataNascimento).format('DD/MM/YYYY');
                    cols += '<td>'+dados[i].nomeCompleto+'</td>';
                    cols += '<td>'+dados[i].cpf+'</td>';
                    //cols += '<td>'+dados[i].dataNascimento+'</td>';
                    cols += '<td>'+dataFormatada+'</td>';
                    cols += '<td>'+dados[i].login+'</td>';
                    cols += '<td><button class="button is-link is-small fullwidth" onclick="exibeCard('+id+')">Detalhar</button>';
                    newRow.append(cols);
                    $("#tabelaUsuarios").append(newRow);
            }
        });

    }

    function fechaCard(){
        $('#card').hide();
        $('#tabelaUsuarios').show();
        $('#labelClasse').show();
        $('#form').show();
    }
    
    function exibeCard(id){
        var select = document.getElementById("listaDeUsuarios");
        var classe = select.options[select.selectedIndex].text;
        $('#form').hide();
        
        //CARD
        if (classe == "Médicos"){
            $.getJSON("listamedicos.php?id=" + id,function(dados){
                const dataFormatada = moment(dados[0].dataNascimento).format('DD/MM/YYYY');

                $('#tituloCard').text("Médico(a): "+dados[0]["nomeCompleto"]);
                $('#cpfCard').text("CPF: "+dados[0]["cpf"]);
                $('#nascimentoCard').text("Data de nascimento: "+dataFormatada);
                $('#crmCard').text("CRM: "+dados[0]["crm"]);
                $('#especialidadeCard').text("Especialidade: "+dados[0]["especialidade"]);
                $('#adressCard').text("Endereço: " + dados[0]["logradouro"] + ", " + dados[0]["numero"]);
                $('#foneCard').text("Telefone: "+dados[0]["telefone"]);
                $('#emailCard').text("Email: "+dados[0]["email"]);
            });
        }

        if (classe == "Analistas"){
            $.getJSON("listaanalistas.php?id=" + id,function(dados){
                const dataFormatada = moment(dados[0].dataNascimento).format('DD/MM/YYYY');
                $('#tituloCard').text("Analista: "+dados[0]["nomeCompleto"]);
                $('#cpfCard').text("CPF: "+dados[0]["cpf"]);
                $('#nascimentoCard').text("Data de nascimento: "+dataFormatada);
                $('#crmCard').text("");
                $('#especialidadeCard').text("");
                $('#adressCard').text("Endereço: " + dados[0]["logradouro"] + ", " + dados[0]["numero"]);
                $('#foneCard').text("Telefone: "+dados[0]["telefone"]);
                $('#emailCard').text("Email: "+dados[0]["email"]);
            });
        }

        if (classe == "Pacientes"){
            $.getJSON("listapacientes.php?id=" + id,function(dados){
                const dataFormatada = moment(dados[0].dataNascimento).format('DD/MM/YYYY');
                $('#tituloCard').text("Paciente: "+dados[0]["nomeCompleto"]);
                $('#cpfCard').text("CPF: "+dados[0]["cpf"]);
                $('#nascimentoCard').text("Data de nascimento: "+dataFormatada);
                $('#adressCard').text("Endereço: " + dados[0]["logradouro"] + ", " + dados[0]["numero"]);
                $('#crmCard').text("");
                $('#especialidadeCard').text("");
                $('#foneCard').text("Telefone: "+dados[0]["telefone"]);
                $('#emailCard').text("Email: "+dados[0]["email"]);
            });
        }

        if (classe == "Supervisor"){
            $.getJSON("listasupervisores.php?id=" + id,function(dados){
                const dataFormatada = moment(dados[0].dataNascimento).format('DD/MM/YYYY');
                $('#tituloCard').text("Supervisor(a): "+dados[0]["nomeCompleto"]);
                $('#cpfCard').text("CPF: "+dados[0]["cpf"]);
                $('#nascimentoCard').text("Data de nascimento: "+dataFormatada);
                $('#crmCard').text("");
                $('#especialidadeCard').text("");
                $('#adressCard').text("Endereço: " + dados[0]["logradouro"] + ", " + dados[0]["numero"]);
                $('#foneCard').text("Telefone: "+dados[0]["telefone"]);
                $('#emailCard').text("Email: "+dados[0]["email"]);
            });
        }


        $('#card').show();
        $('#tabelaUsuarios').hide();
        $('#labelClasse').hide();
    
    }


    $(document).ready(function() {
        $('#card').hide();
    });


</script>
<body>
    <?php include_once "menulogado.php" ?>
    <main>

    <!-- LISTA DE SELEÇÃO DE USUÁRIOS -->
    <div class="column is-1"></div>
    <div class="has-text-centered">
    <form method="POST" id="form">
        <div class="select is-link">
        <select name="escolhaUsuario" id="listaDeUsuarios" onchange="getClasse()">
            <option class="firstOp" value="">Selecione uma classe de usuário</option>
            <option value="med">Médicos</option>
            <option value="pac">Pacientes</option>
            <option value="ana">Analistas</option>
            <option value="sup">Supervisor</option>
        </select>
        <script>
            function getClasse(){
                var select = document.getElementById("listaDeUsuarios");
                var classe = select.options[select.selectedIndex].value;
                
                var nomeClasse = select.options[select.selectedIndex].text;
                $('#labelClasse').text(nomeClasse);
                listaUsuarios(classe);
                //$(".firstOp").prop('selected', true);
            }
        </script>
        <input type="hidden" id="classeSelecionada" name="classeSelecionada">
        </div>
        <div class="column is-1"></div>
        <h3 class="title is-3" id="labelClasse" class="label"></h3>
    </form>

    <!-- CARD -->
    <div class="column is-half is-offset-one-quarter" id="divcard">
        <article class="message is-info" id="card">
        <div class="message-header">
            <p id="tituloCard"></p>
            <button class="delete" aria-label="delete" onclick="fechaCard()"></button>
        </div>
        <div class="message-body">
            <p style="text-align: left" id="cpfCard"></p>
            <p style="text-align: left" id="nascimentoCard"></p>
            <p style="text-align: left" id="crmCard"></p>
            <p style="text-align: left" id="especialidadeCard"></p>
            <p style="text-align: left" id="adressCard"></p>
            <p style="text-align: left" id="foneCard"></p>
            <p style="text-align: left" id="emailCard"></p>
        </div>
        </article>
    </div>

     <!-- TABELA USUÁRIOS -->
     <div class="has-text-centered">
         <div class="column is-half is-offset-one-quarter">
            <table class="table is-fullwidth is-hoverable" id="tabelaUsuarios" >
            </table>
        </div>
    </div>


    </main>

    <?php require_once "database.php"; ?>
    
<?php include "footer.php"; ?>

</body>

</html>