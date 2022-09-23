<?php include "session.php";

$logged = $_SESSION['logged'] ?? NULL;
$tipoUsuario = $_SESSION['tipoUsuario'] ?? NULL;
$idMedico = $_SESSION['idUser'];

if (!$logged) die;

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
<script>
        function exibeTabelaPedidos(idMedico){
            document.getElementById("tabelaPedidos").style.display = "";
            document.getElementById("tabelaExamesPedido").style.display = "none";
            $("#buttonRetornaTabelaPedidos").show();
            


            $.getJSON("gerapedidospormedico.php?id=" + idMedico,function(dados){

                //REMOVO A TABELA PARA GERAR ELA DO ZERO
                var listaPedidos = document.getElementById('tabelaPedidos');
                listaPedidos.parentNode.removeChild(listaPedidos);
                
                //GERO NOVAMENTE A TABELA
                const tabela = document.createElement('table');
                tabela.setAttribute("class","table is-half is-fullwidth table is-hoverable");
                tabela.setAttribute("id","tabelaPedidos");

                const thead = document.createElement('thead');
                const cab = ['PEDIDO', 'HIPÓTESE','DATA E HORA DA SOLICITAÇÃO','PACIENTE','AÇÃO'];
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
                        var numPedido = dados[i].id;
                        var hipotese = dados[i].hipotese;
                        var dataPedido = dados[i].dataPedido;
                        var paciente = dados[i].nomePaciente;
                        const dataPedidoFormatada = moment(dados[i].dataPedido).format('DD/MM/YYYY h:mm');
                        var newRow = $("<tr>");
                        var cols = "";
                        cols += '<td>'+numPedido+'</td>';
                        cols += '<td>'+hipotese+'</td>';
                        cols += '<td>'+dataPedidoFormatada+'</td>';
                        cols += '<td>'+paciente+'</td>';
                        cols += '<td><button class="button is-link is-small fullwidth" onclick="exibeExamesPedido(' + numPedido + ')">Visualizar Exames</button><button class="button is-danger is-small fullwidth" onclick="apagaPedido(' + numPedido + ')">Apagar pedido</button>';
                        newRow.append(cols);
                        $("#tabelaPedidos").append(newRow);
                    
                }
                
                if(dados.length == 0){
                    $("#tabelaPedidos").hide();
                    $('#titulo').hide();
                    toastr.warning("Você não possui solicitações de exame!");
                }
            });
        }

        function exibeExamesPedido(idPedido){
            $('#labelIdPedidoTabelaExamesPedido').text(idPedido);
            
            var labelTitulo = document.getElementById("titulo");
            labelTitulo.style.display = "";
            $.getJSON("geratabelaexamespedido.php?id=" + idPedido,function(dados){
                if (dados.length == 0){
                    toastr.warning("Não existem exames nesse pedido!");
                }

                $.getJSON("buscapacientenopedido.php?id=" + idPedido,function(dados){
                    labelTitulo.innerText = "Exames do pedido: " + idPedido + " - Paciente: " + dados[0]["nomePaciente"];
                });


                //REMOVO A TABELA PARA GERAR ELA DO ZERO
                var listaExamesPedido = document.getElementById('tabelaExamesPedido');
                listaExamesPedido.parentNode.removeChild(listaExamesPedido);
                
                //GERO NOVAMENTE A TABELA
                const tabela = document.createElement('table');
                tabela.setAttribute("class","table table is-fullwidth is-hoverable");
                tabela.setAttribute("id","tabelaExamesPedido");

                const thead = document.createElement('thead');
                const cab = ['EXAME', 'SITUAÇÃO','DATA E HORA DA LIBERAÇÃO'];
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
                        var numPedido = dados[i].numPedido;
                        var numExame = dados[i].numExame;
                        var status = dados[i].status;
                        dataLiberado = dados[i].dataLiberado;
                        var newRow = $("<tr>");
                        var cols = "";
                        cols += '<td>'+dados[i].exame+'</td>';
                        if(status == 'em produção')
                            cols += '<td>'+status+'</td>';
                        else
                            cols += '<td><strong>'+status+'</strong></td>';
                        if (dataLiberado == null){
                            dataLiberado = "aguardando";
                            cols += '<td>'+dataLiberado+'</td>';
                        }else{
                            const dataLiberadoFormatada = moment(dataLiberado).format('DD/MM/YYYY h:mm');
                            cols += '<td>'+dataLiberadoFormatada+'</td>';
                        }
                        newRow.append(cols);
                        $("#tabelaExamesPedido").append(newRow);
                    }
                });

            document.getElementById("tabelaPedidos").style.display = "none";
            document.getElementById("tabelaExamesPedido").style.display = "";
            $("#buttonRetornaTabelaPedidos").show();
        }

        function retornaTabelaPedido(){
            var idMedico = <?php echo $idMedico ?>;
            exibeTabelaPedidos(idMedico);
            $('#titulo').text("Selecione um pedido para gerenciar");
            $('#buttonRetornaTabelaPedidos').hide();
        }

        function apagaPedido(idPedido){
            if (confirm("Deseja remover o pedido "+idPedido+"?")){
                
                $.getJSON("apagapedido.php?id=" + idPedido,
                function(dados){
                    toastr.success("Pedido removido com sucesso!");
                });
                var idMedico = <?php echo $idMedico ?>;
                exibeTabelaPedidos(idMedico);
            }
        }

        $(document).ready(function() {
            var idMedico = <?php echo $idMedico ?>;
            exibeTabelaPedidos(idMedico);
            $('#titulo').text("Selecione um pedido para gerenciar");
            $('#buttonRetornaTabelaPedidos').hide();
        });



    </script>

<style>
    .fullwidth{
        width: 35%;
        margin-bottom: 5px;
        margin-left: 9px;
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

<body>

    <main>

    <?php
        if ($tipoUsuario != "med"){
            echo '<h4 style="text-align: center" class="title is-4">ACESSO EXCLUSIVO PARA MÉDICOS!</h4>';
            die;
        }
    ?>
    
            <!-- TABELA PEDIDOS -->
            <div class="has-text-centered">
                <form method="POST"><input type="hidden" id="TabelaPedidos"></form>
                <div class="column is-half is-offset-one-quarter">
                <table class="table is-fullwidth is-hoverable" id="tabelaPedidos" >
                </table>
                </div>
            </div>


        <!-- TABELA EXAMES DO PEDIDO -->
        <div class="has-text-centered">
            <label id="labelIdPedidoTabelaExamesPedido" hidden></label>
            <div class="column is-half is-offset-one-quarter">
            <button type="button" id="buttonRetornaTabelaPedidos" class="button is-link is-small" onclick="retornaTabelaPedido()">Voltar</button>
            <table class="table is-fullwidth is-hoverable" id="tabelaExamesPedido" >
            </table>
            </div>
        </div>


    </main>

    <?php include "footer.php" ?>

</body>


</html>
