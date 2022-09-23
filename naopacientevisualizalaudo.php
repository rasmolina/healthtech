<?php include "session.php";

$logged = $_SESSION['logged'] ?? NULL;
$tipoUsuario = $_SESSION['tipoUsuario'] ?? NULL;
if (!$logged) die('Acesso negado!');

?>

<script>
        function exibeTabelaPedidos(idPaciente){
            document.getElementById("tabelaPedidos").style.display = "";
            document.getElementById("tabelaExamesPedido").style.display = "none";
            document.getElementById("formLaudo").style.display = "none";
            document.getElementById("selectForm").style.display = "none";
            
            $("#buttonRetornaTabelaPedidos").hide();
            $('#buttonRetornaSelect').show();


            $.getJSON("gerartabelapedidos.php?id=" + idPaciente,function(dados){

                //REMOVO A TABELA PARA GERAR ELA DO ZERO
                var listaPedidos = document.getElementById('tabelaPedidos');
                listaPedidos.parentNode.removeChild(listaPedidos);
                
                //GERO NOVAMENTE A TABELA
                const tabela = document.createElement('table');
                tabela.setAttribute("class","table is-half is-fullwidth table is-hoverable");
                tabela.setAttribute("id","tabelaPedidos");

                const thead = document.createElement('thead');
                const cab = ['PEDIDO', 'DATA E HORA DA SOLICITAÇÃO','EXAMES'];
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
                        const dataPedidoFormatada = moment(dados[i].dataSolicitacao).format('DD/MM/YYYY h:mm');
                        var newRow = $("<tr>");
                        var cols = "";
                        cols += '<td>'+numPedido+'</td>';
                        cols += '<td>'+dataPedidoFormatada+'</td>';
                        cols += '<td><button class="button is-link is-small fullwidth" onclick="exibeExamesPedido(' + numPedido + ')">Detalhar</button>';
                        newRow.append(cols);
                        $("#tabelaPedidos").append(newRow);
                    
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
                    labelTitulo.innerText = "Pedido: " + idPedido + " - Paciente: " + dados[0]["nomePaciente"];
                });


                //REMOVO A TABELA PARA GERAR ELA DO ZERO
                var listaExamesPedido = document.getElementById('tabelaExamesPedido');
                listaExamesPedido.parentNode.removeChild(listaExamesPedido);
                
                //GERO NOVAMENTE A TABELA
                const tabela = document.createElement('table');
                tabela.setAttribute("class","table table is-fullwidth is-hoverable");
                tabela.setAttribute("id","tabelaExamesPedido");

                const thead = document.createElement('thead');
                const cab = ['EXAME', 'SITUAÇÃO','DATA E HORA DA LIBERAÇÃO','LAUDO'];
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
                        if(status == "em produção") //Se o exame estiver em produção
                            cols += '<td> aguardando </td>';
                        //Se o exame estiver liberado
                        else{
                            cols += '<td><button class="button is-link is-small fullwidth" onclick="exibeCampoLaudo('+numPedido+','+numExame+')">Visualizar</button></td>';
                        }
                        newRow.append(cols);
                        $("#tabelaExamesPedido").append(newRow);
                    }
                });

            document.getElementById("tabelaPedidos").style.display = "none";
            document.getElementById("tabelaExamesPedido").style.display = "";
            document.getElementById("selectForm").style.display = "none";
            document.getElementById("formLaudo").style.display = "none";
            $("#buttonRetornaTabelaPedidos").show();
            $('#buttonRetornaSelect').hide();
        }

        function retornarTabelaExames(){
            var labelIdPedidoTabelaExamesPedido = document.getElementById("labelIdPedidoTabelaExamesPedido");
            var idPedido = parseInt(labelIdPedidoTabelaExamesPedido.innerText);
            exibeExamesPedido(idPedido);
        }

        function retornarSelect(){
            $(".firstOp").prop('selected', true);
            document.getElementById("tabelaPedidos").style.display = "none";
            document.getElementById("tabelaExamesPedido").style.display = "none";
            document.getElementById("formLaudo").style.display = "none";
            document.getElementById("selectForm").style.display = "";
            document.getElementById("titulo").innerHTML = "Busque pelo número do pedido ou selecione um(a) paciente";
            document.getElementById("titulo").style.display = "";
            document.querySelector("#inputBuscaIdPedido").value = "";
            $("#inputBuscaIdPedido").focus();
            $('#buttonRetornaTabelaPedidos').hide();
            $('#buttonRetornaSelect').hide();
        }

        function exibeCampoLaudo(idPedido,idExame){
            document.getElementById("tabelaPedidos").style.display = "none";
            document.getElementById("tabelaExamesPedido").style.display = "none";
            document.getElementById("titulo").style.display = "none";
            document.getElementById("formLaudo").style.display = "";
            $('#buttonRetornaTabelaPedidos').hide();
            $('#buttonRetornaSelect').hide();
            
            var inputNumPedido = document.querySelector("#idPedido");
            var inputNumExame = document.querySelector("#idExame");
            var laudo = document.getElementById("laudo");
            var labelPedido = document.getElementById("labelPedido");
            var labelPaciente = document.getElementById("labelPaciente");
            var labelExame = document.getElementById("labelExame");
            inputNumPedido.value="";
            inputNumExame.value="";
            laudo.value="";
            labelPedido.innerText = "";
            labelPaciente.innerText = "";
            labelExame.innerText = "";

            $.getJSON("buscapacientenopedido.php?id=" + idPedido,function(dados){
                labelPaciente.innerText = "Paciente: " + dados[0]["nomePaciente"];
            });
           
            $.getJSON("gerarlaudojson.php?idPedido=" + idPedido + "&idExame=" + idExame,
            function(dados){
                inputNumPedido.value = dados[0]["numPedido"];
                inputNumExame.value = dados[0]["numExame"];
                laudo.value = dados[0]["laudo"];
                labelPedido.innerText = "Pedido: " + dados[0]["numPedido"];
                labelExame.innerText = "Resultado do exame: "+ dados[0]["exame"];
            });
        }

        function buscaExames(){
            var inputBuscaIdPedido = document.querySelector("#inputBuscaIdPedido").value;
            $.getJSON("geratabelaexamespedido.php?id=" + inputBuscaIdPedido,function(dados){
                if (dados.length == 0){
                    $("#inputBuscaIdPedido").val("");
                    $("#inputBuscaIdPedido").focus();
                    toastr.warning("Pedido não localizado!");
                }else{
                    exibeExamesPedido(inputBuscaIdPedido);
                }
            });
            inputBuscaIdPedido.value = "";
        }

        $(document).ready(function() {
            $('#titulo').text("Busque pelo número do pedido ou selecione um(a) paciente");
            $('#buttonRetornaTabelaPedidos').hide();
            $('#buttonRetornaSelect').hide();
            
        });


    </script>

<body>

    <main>
    
    <!-- ESCOLHA DO PACIENTE OU PEDIDO -->
    <div class="column is-1"></div>
    <div class="has-text-centered">
    <form method="GET" id="selectForm">
        <label for="inputBuscaIdPedido" class="label" >Digite o número do pedido e pressione Tab: </label>
        <input autofocus type="number" class="input" id="inputBuscaIdPedido" style="margin-bottom: 20px" onchange="buscaExames()">
        <label class="label" >ou</label>
        <div class="select is-link">
        <select name="listaDePacientes" id="listaDePacientes" onchange="getId()">
            <option class="firstOp">Selecione um(a) paciente</option>
            <?php
                $lista = listarPacientes();
                for ($i=0; $i<count($lista); $i++) {
                    $id = $lista[$i]["id"];
                    $nomePaciente = $lista[$i]["nomeCompleto"];
                    echo "<option value='$id'>$nomePaciente</option>";
                }
            ?>
        </select>
        <script>
            function getId(){
                var select = document.getElementById("listaDePacientes");
                var idPaciente = select.options[select.selectedIndex].value;
                var nomePaciente = select.options[select.selectedIndex].text;
                $('#titulo').text("Pedidos de exame do(a) paciente: " + nomePaciente);
                exibeTabelaPedidos(idPaciente);
            }
        </script>
        </div>
    </form>
    </div>

            <!-- TABELA PEDIDOS -->
            <div class="has-text-centered">
                <form method="POST"><input type="hidden" id="idPacienteTabelaPedido"></form>
                <div class="column is-half is-offset-one-quarter">
                <button type="button" id="buttonRetornaSelect" class="button is-link is-small" onclick="retornarSelect()">Voltar</button>    
                <table class="table is-fullwidth is-hoverable" id="tabelaPedidos" >
                </table>
                </div>
            </div>


        <!-- TABELA EXAMES DO PEDIDO -->
        <div class="has-text-centered">
            <label id="labelIdPedidoTabelaExamesPedido" hidden></label>
            <div class="column is-half is-offset-one-quarter">
            <button type="button" id="buttonRetornaTabelaPedidos" class="button is-link is-small" onclick="retornarSelect()">Voltar</button>
            <table class="table is-fullwidth is-hoverable" id="tabelaExamesPedido" >
            </table>
            </div>
        </div>

        <!-- CAMPO LAUDO -->
        <div class="column is-half is-offset-one-quarter">
        <form class="box" method="POST" id="formLaudo" style="display: none"> 
            <input type="hidden" id="idPedido" name="idPedido">
            <input type="hidden" id="idExame" name="idExame">    
            <button type="button" class="button is-link is-small rightbutton" onclick="retornarTabelaExames()">Voltar para lista de exames</button>
            <label id="labelPedido" class="label"></label>
            <label id="labelPaciente" class="label"></label>
            <div class="column is-1"></div>
                <label id="labelExame" class="label"></label>
                <div class="field">
                    <div class="control">
                    <textarea class="textarea" maxlength="800" style="resize: none" id="laudo" name="laudo" disabled></textarea>
                    </div>
                </div>
        </form>

    </main>

</body>

<?php

    function listarPacientes(){
        require_once "database.php";
        $lista = [];
        try {
            $conexao = Conexao::getConexao();
            $stmt = $conexao->prepare("SELECT * FROM paciente ORDER BY nomeCompleto");
            $stmt->execute();
            $lista = $stmt->fetchAll();
        } catch (Exception $th) {
            echo $th->getMessage();
            exit;
        }
        return $lista;
    }

?>

</html>
