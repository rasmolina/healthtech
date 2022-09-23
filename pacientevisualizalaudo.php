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
            
            $("#buttonRetornaTabelaPedidos").hide();


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
                        var dataLiberado = dados[i].dataLiberado;
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
            document.getElementById("formLaudo").style.display = "none";
            $("#buttonRetornaTabelaPedidos").show();

        }

        function retornarTabelaPedidos(){
            document.getElementById("tabelaPedidos").style.display = "";
            document.getElementById("tabelaExamesPedido").style.display = "none";
            document.getElementById("formLaudo").style.display = "none";
            document.getElementById("titulo").innerHTML = "Selecione um pedido para visualizar seus exames";
            document.getElementById("titulo").style.display = "";
            $('#buttonRetornaTabelaPedidos').hide();
        }

        function retornarTabelaExames(){
            document.getElementById("tabelaPedidos").style.display = "none";
            document.getElementById("tabelaExamesPedido").style.display = "";
            document.getElementById("formLaudo").style.display = "none";
            document.getElementById("titulo").innerHTML = "Selecione um exame para visualizar o resultado";
            document.getElementById("titulo").style.display = "";
            $('#buttonRetornaTabelaPedidos').show();
        }

        function exibeCampoLaudo(idPedido,idExame){
            document.getElementById("tabelaPedidos").style.display = "none";
            document.getElementById("tabelaExamesPedido").style.display = "none";
            document.getElementById("titulo").style.display = "none";
            document.getElementById("formLaudo").style.display = "";
            $('#buttonRetornaTabelaPedidos').hide();
            
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

        $(document).ready(function() {
            var idPaciente = <?php echo $idPaciente ?>;
            exibeTabelaPedidos(idPaciente);
            $('#titulo').text("Selecione um pedido para visualizar seus exames");
            $('#buttonRetornaTabelaPedidos').hide();
        });


    </script>

<style>
    .fullwidth{
        width: 35%;
        margin-bottom: 5px;
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
        width: 50px;
    }
    td{
        height: 50px; 
        width: 50px;
        text-align:center;
        vertical-align:middle;
    }
</style>
<body>

    <main>

            <!-- TABELA PEDIDOS -->
            <div class="has-text-centered">
                <form method="POST"><input type="hidden" id="idPacienteTabelaPedido"></form>
                <div class="column is-half is-offset-one-quarter">
                <table class="table is-fullwidth is-hoverable" id="tabelaPedidos" >
                </table>
                </div>
            </div>


        <!-- TABELA EXAMES DO PEDIDO -->
        <div class="has-text-centered">
            <label id="labelIdPedidoTabelaExamesPedido" hidden></label>
            <div class="column is-half is-offset-one-quarter">
            <button type="button" id="buttonRetornaTabelaPedidos" class="button is-link is-small" onclick="retornarTabelaPedidos()">Voltar</button>
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


    <?php
    require_once "database.php";

    function geraTabelaPedidos(){
        $idPaciente = $_SESSION["idUser"];
        require_once "database.php";
        try {
            $conexao = Conexao::getConexao();
            $stmt = $conexao->prepare('SELECT id, dataSolicitacao from pedido where pedido.idPaciente = ?');
            $stmt->execute([$idPaciente]);
            if ($stmt->rowCount() > 0) {
                echo '<thead>';
                echo '<tr>';
                echo "<th>Pedido</th>";
                echo "<th>Data e Hora de Solicitação</th>";
                echo "<th>Exames</th>";
                echo "</tr>";
                echo "</thead>";
                echo "<tbody>";
                foreach ($stmt as $linha) {
                    $idPedido = $linha["id"];
                    $dataPedido = $linha["dataSolicitacao"];
                    $dataPedido = date('d-m-Y h:i',strtotime($dataPedido)); //conversão para formato brasileiro de data
                    echo "<td>" . $idPedido . "</td>";
                    echo "<td>" . $dataPedido . "</td>";
                    echo '<td> <button class="button is-link is-small" onclick="exibeExamesPedido('.$idPedido.')">Detalhar</button></td>';
                    echo "</tr>";
                }
                echo '</tbody>';
                echo '</table>';
            }else{
                echo '<script type="text/javascript">toastr.warning("Você não possui pedidos de exames!")</script>';
            }
        } catch (Exception $th) {
            echo $th->getMessage();
            exit;
        }
    }

    function geraTabelaExamesPedido(){
        $idPedido = $_POST["idPedido"];
        require_once "database.php";
        try {
            $conexao = Conexao::getConexao();
            $stmt = $conexao->prepare('SELECT examespedido.idPedido as numPedido, examespedido.idExame as numExame, exame.nome as exame, examespedido.situacao as status, examespedido.dataHoraRealizacao as dataLiberado from examespedido inner join exame on examespedido.idExame = exame.id and examespedido.idPedido = ?');
            $stmt->execute([$idPedido]);
            if ($stmt->rowCount() > 0) {
                echo '<thead>';
                echo '<tr>';
                echo "<th>Exame</th>";
                echo "<th>Situação</th>";
                echo "<th>Data e Hora de Liberação</th>";
                echo "<th>Laudo</th>";
                echo "</tr>";
                echo "</thead>";
                echo "<tbody>";
                foreach ($stmt as $linha) {
                    $idPedido = $linha["numPedido"];
                    $idExame = $linha["numExame"];
                    $dataLiberado = $linha["dataLiberado"];
                    if ($dataLiberado == "0000-00-00 00:00:00")
                        $dataLiberado = "aguardando";
                    else
                        $dataLiberado = date('d-m-Y h:i',strtotime($dataLiberado)); //conversão para formato brasileiro de data
                        $liberado = $linha["status"];
                    echo "<td>" . $linha["exame"] . "</td>";
                    if($liberado == "liberado")
                        echo "<td><strong>" . $liberado . "</strong></td>";
                    else
                        echo "<td>" . $liberado . "</td>";
                    echo "<td>" . $dataLiberado . "</td>";
                    if ($linha["status"] == "em produção")
                        echo '<td> aguardando </td>';
                    else{
                        echo '<td> <button class="button is-link is-small fullwidth" onclick="exibeCampoLaudo('.$idPedido.','.$idExame.')">Visualizar</button>
                        </td>';
                    }
                    echo "</tr>";
                }
                echo '</tbody>';
                echo '</table>';
            }
        } catch (Exception $th) {
            echo $th->getMessage();
            exit;
        }
    }

    
?>

</body>

</html>
