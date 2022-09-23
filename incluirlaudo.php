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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>    
</head>
<style>
    .fullwidth{
        width: 30%;
        margin-bottom: 5px;
        margin-left: 7px;
    }

    .rightbutton{
        float: right;
        margin-left: 7px;
    }
    
    .table,.td{
        align-items: center;
        
        justify-content: center;
        text-align: center;
        vertical-align: middle;
    }
</style>

<script>
        //GERO TABELA COM OS EXAMES DO PEDIDO SELECIONADO
        function exibeExamesPedido(idPedido){
            $.getJSON("geratabelaexamespedido.php?id=" + idPedido,function(dados){
                if (dados.length == 0){
                    toastr.warning("Não existem exames nesse pedido!");
                }

                //REMOVO A TABELA PARA GERAR ELA DO ZERO
                var listaExamesPedido = document.getElementById('tabelaExamesPedido');
                listaExamesPedido.parentNode.removeChild(listaExamesPedido);
                
                //GERO NOVAMENTE A TABELA
                const tabela = document.createElement('table');
                tabela.setAttribute("class","table table is-fullwidth is-hoverable");
                tabela.setAttribute("id","tabelaExamesPedido");

                const thead = document.createElement('thead');
                const cab = ['Exame', 'Situação','Data e Hora de Liberação','Laudo'];
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
                        var dataLiberado = dados[i].dataLiberado; //converter para formato brasileiro
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
                        
                        if(status == "em produção") //Se o exame estiver em produção, será possível incluir o resultado
                            cols += '<td> <button class="button is-primary is-small fullwidth" onclick="exibeCampoLaudo('+numPedido+','+numExame+')">Incluir</button></td>';
                        //Se o exame estiver liberado, será possível visualizar ou pagar o laudo
                        else{
                            cols += '<td><button class="button is-link is-small fullwidth" onclick="exibeCampoLaudo('+numPedido+','+numExame+')">Visualizar</button><br><button class="button is-danger is-small fullwidth" onclick="apagaLaudo('+numPedido+','+numExame+')">Excluir</button></td>';
                        }
                        newRow.append(cols);
                        $("#tabelaExamesPedido").append(newRow);
                    }
                });

            document.getElementById("tabelaPedidos").style.display = "none";
            document.getElementById("tabelaExamesPedido").style.display = "";
            document.getElementById("formLaudo").style.display = "none";
            document.getElementById("titulo").innerHTML = "Selecione um exame para laudar ou visualizar o resultado";
            $("#buttonRetornaTabelaPedidos").show();
        }

        function retornarTabelaPedidos(){
            $("#buttonRetornaTabelaPedidos").hide();
            document.getElementById("tabelaPedidos").style.display = "";
            document.getElementById("tabelaExamesPedido").style.display = "none";
            document.getElementById("formLaudo").style.display = "none";
            document.getElementById("titulo").innerHTML = "Escolha um pedido para incluir os resultados de exames";
            document.getElementById("titulo").style.display = "";
        }

        function retornarTabelaExames(){
            $("#buttonRetornaTabelaPedidos").show();
            document.getElementById("tabelaPedidos").style.display = "none";
            document.getElementById("tabelaExamesPedido").style.display = "";
            document.getElementById("formLaudo").style.display = "none";
            document.getElementById("titulo").innerHTML = "Selecione um exame para laudar ou visualizar o resultado";
            document.getElementById("titulo").style.display = "";
        }

        function exibeCampoLaudo(idPedido,idExame){
            $("#buttonRetornaTabelaPedidos").hide();
            document.getElementById("tabelaPedidos").style.display = "none";
            document.getElementById("tabelaExamesPedido").style.display = "none";
            document.getElementById("titulo").style.display = "none";
            document.getElementById("formLaudo").style.display = "";
            var botaoSalvar = document.getElementById("saveButton");
            
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
                if(laudo.value != ""){
                    laudo.readOnly = true;
                    botaoSalvar.style.display = 'none';
                }else{
                    laudo.readOnly = false;
                    botaoSalvar.style.display = '';
                }
            });
        }

        function apagaLaudo(idPedido,idExame){
            if (confirm("Deseja remover este laudo do pedido "+idPedido+"?")){
                $.getJSON("apagalaudo.php?idPedido=" + idPedido + "&idExame=" + idExame,
                function(dados){
                    exibeExamesPedido(idPedido);
                    toastr.success("Laudo removido com sucesso!");
                });
                
            }
        }

        $(document).ready(function() {
            $('#buttonRetornaTabelaPedidos').hide();
        });

    </script>

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
            <h4 class="title is-4" id="titulo">Escolha um pedido para incluir os resultados de exames</h4>
        </div>
    </header>

    <main class="principal">
        
        <!-- TABELA DE PEDIDOS -->
        <div class="column is-1"></div>
        <div class="has-text-centered">
            <div class="column is-half is-offset-one-quarter" >
            <table class="table is-fullwidth is-hoverable" id="tabelaPedidos">
                <?php geraTabelaPedidos() ?>
            </table>
            </div>
        </div>

        <!-- TABELA EXAMES DO PEDIDO -->
        <div class="has-text-centered">
            <div class="column is-half is-offset-one-quarter">
            <button type="button" id="buttonRetornaTabelaPedidos" class="button is-link is-small" onclick="retornarTabelaPedidos()">Voltar</button>
            <table class="table is-fullwidth is-hoverable" id="tabelaExamesPedido" >
            </table>
            </div>
        </div>

        <!-- CAMPO LAUDO -->
        <div class="column is-1"></div>
        <div class="column is-half is-offset-one-quarter">
        <form class="box" method="POST" id="formLaudo" style="display: none"> 
            <input type="hidden" id="idPedido" name="idPedido">
            <input type="hidden" id="idExame" name="idExame">    
            <button type="submit" id="saveButton" class="button is-success is-small rightbutton">Salvar</button>
            <button type="button" class="button is-link is-small rightbutton" onclick="retornarTabelaExames()">Voltar para lista de exames</button>
            <label id="labelPedido" class="label"></label>
            <label id="labelPaciente" class="label"></label>

            <div class="column is-1"></div>
                <label id="labelExame" class="label"></label>
                <div class="field">
                    <div class="control">
                    <textarea class="textarea" maxlength="800" style="resize: none" id="laudo" name="laudo" placeholder="Digite o resultado do exame" required></textarea>
                    </div>
                </div>
        </form>


    </main>

    <?php include "footer.php" ?>

    <?php
    require_once "database.php";

    function geraTabelaPedidos(){
        require_once "database.php";
        try {
            $conexao = Conexao::getConexao();
            $stmt = $conexao->prepare('SELECT pedido.id as numpedido, paciente.nomeCompleto as nome, pedido.dataSolicitacao as data from paciente inner join pedido on pedido.idPaciente = paciente.id');
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                echo '<thead>';
                echo '<tr>';
                echo "<th>Pedido</th>";
                echo "<th>Paciente</th>";
                echo "<th>Data e Hora de Solicitação</th>";
                echo "<th>Exames</th>";
                echo "</tr>";
                echo "</thead>";
                echo "<tbody>";
                foreach ($stmt as $linha) {
                    $idPedido = $linha["numpedido"];
                    $dataPedido = $linha["data"];
                    $dataPedido = date('d-m-Y h:i',strtotime($dataPedido)); //conversão para formato brasileiro de data
                    echo "<td>" . $idPedido . "</td>";
                    echo "<td>" . $linha["nome"] . "</td>";
                    echo "<td>" . $dataPedido . "</td>";
                    echo '<td> <button class="button is-link is-small" onclick="exibeExamesPedido('.$idPedido.')">Detalhar</button></td>';
                    echo "</tr>";
                }
                echo '</tbody>';
                echo '</table>';
            }else{
                echo '<script type="text/javascript">toastr.warning("Não existem pedidos de exames!")</script>';
            }
        } catch (Exception $th) {
            echo $th->getMessage();
            exit;
        }
    }

    if( isset($_POST["laudo"]) or !empty($_POST["laudo"]) ){
        $laudo = $_POST["laudo"];
        $idPedido = $_POST["idPedido"];
        $idExame = $_POST["idExame"];
        $idAnalista = $_SESSION["idUser"];
        $userClass = $_SESSION['tipoUsuario'];
        $status = "liberado";
        $dataLiberado = (new DateTime())->format('Y-m-d H:i:s');
        if($userClass == 'ana'){
            try {
                require_once "database.php";
                $conexao = Conexao::getConexao();
                $stmt = $conexao->prepare('UPDATE examespedido set idAnalista=?, situacao=?, laudo=?,dataHoraRealizacao=? where idPedido = ? and idExame = ?');
                $stmt->execute([$idAnalista,$status,$laudo,$dataLiberado,$idPedido,$idExame]);
                if ($stmt->rowCount() > 0){
                    echo "<meta HTTP-EQUIV='refresh' CONTENT='3'>";
                    echo '<script type="text/javascript">toastr.success("Exame laudado com sucesso!")</script>';
                }
                else{
                    echo "<meta HTTP-EQUIV='refresh' CONTENT='3'>";
                    echo '<script type="text/javascript">toastr.warning("Houve algum problema na inclusão do laudo!")</script>';
                }
            } catch (Exception $th) {
                echo json_encode(array("msg" => $th->getMessage()));
                exit;
            }
        }else{
            echo "<meta HTTP-EQUIV='refresh' CONTENT='3'>";
            echo '<script type="text/javascript">toastr.warning("Somente analistas podem incluir laudos!")</script>';
        }
    }
    
    
    
?>

</body>


