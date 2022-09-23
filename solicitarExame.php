<?php include "session.php";
$logged = $_SESSION['logged'] ?? NULL;
$tipoUsuario = $_SESSION['tipoUsuario'] ?? NULL;
if (!$logged)die;
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
    <script src="js/scripts.js"></script>
    <style>
        .add{
            margin-top: 7px;
        }

    </style>
    <script>
    function criarLinhaTabela(exameSelecionado, idExame) {
        let tr = $("<tr>");
        tr.append($("<td>").text(exameSelecionado));
        tr.append($("<button>").text("Deletar").attr("class", "button is-warning is-small del"));
        let td = $("<td>");
        tr.attr("id", idExame);
        tr.append(td);
        $("tbody").append(tr);
    }

    function verificarPermissao(idExame) {
        $.ajax({
            url: "verificarPermissao.php",
            method: "GET",
            dataType: "JSON",
            data: {
                idPedido: $("input[name=idPedido]").val(),
                idExame: idExame
            },
            success: function(dadosRecebidos) {

                if (dadosRecebidos["permissaoNegada"])
                    toastr.warning("Você não tem permissão para solicitar exames de alto custo.");

                if (dadosRecebidos["limiteMax"])
                    toastr.warning("Você não pode adicionar mais exames. Clique em Confirmar Pedido para finalizar o pedido");

                if (dadosRecebidos["exameExistente"])
                    toastr.warning("Exame já adicionado no pedido!");

                if (dadosRecebidos["permitido"]) {
                    toastr.success("Exame adicionado com sucesso!");
                    let exameSelecionado = dadosRecebidos["nomeExame"];
                    criarLinhaTabela(exameSelecionado, idExame);
                    $("#btnConfirmarPedido").attr("disabled", false);
                }
            },
            error: function(a, b, c) {
                toastr.warning("Não foi possível registrar o exame. Verifique se o campo Exames está preenchido.");
            }
        });
    }

    function pedidoCadastrado(dadosRecebidos) {
        $("input[id=nomePaciente]").val(dadosRecebidos["nomePaciente"]);
        $("textarea[id=hipotese]").val(dadosRecebidos["hipoteseCadastrada"]);
        $("input[name=idPedido]").val(dadosRecebidos["idPedido"]);
    }

    function registrarPedido(idPaciente) {
        let urlId = "registrarPedido.php?idPaciente=" + idPaciente;
        $.ajax({
            url: urlId,
            method: "GET",
            dataType: "JSON",
            data: {
                descricaoHipotese: $("textarea[name=descricaoHipotese]").val()
            },
            success: function(dadosRecebidos) {
                if (dadosRecebidos)
                    toastr.success("Pedido gerado, adicione os exames desejados!");
                pedidoCadastrado(dadosRecebidos);
            },
            error: function(a, b, c) {
                toastr.warning("Não foi possível registrar o pedido. Verifique se os campos estão preenchidos.");
            }
        });
    }

    function deletarExamesPedido(idPedido) {
        let urlId = "deletarExamesPedido.php?idPedido=" + idPedido;
        $.ajax({
            url: urlId,
            method: "GET",
            dataType: "JSON",
            success: function(dadosRecebidos) {},
            error: function(a, b, c) {
                toastr.warning("Não foi possível deletar os exames do pedido.");
            }
        });
    }

    function deletarPedido(idPedido) {
        let urlId = "deletarPedido.php?idPedido=" + idPedido;
        $.ajax({
            url: urlId,
            method: "GET",
            dataType: "JSON",
            success: function(dadosRecebidos) {
                $("tbody").empty();
                $("input[id=nomePaciente]").val("");
                $("textarea[id=hipotese]").val("");
                alert("Pedido cancelado. Você será redirecionado para página inicial");
                window.location.assign("index.php");
            },
            error: function(a, b, c) {
                toastr.warning("Não foi possível deletar o pedido.");
            }
        });
    }

    function deletarExamesEspecifico(idPedido, trExame) {
        let idExame = trExame.attr("id");
        let urlId = "deletarExamesEspecifico.php?idPedido=" + idPedido;
        $.ajax({
            url: urlId,
            method: "GET",
            dataType: "JSON",
            data: {
                idExame: idExame
            },
            success: function(dadosRecebidos) {
                trExame.remove();
                if (dadosRecebidos["contador"] == 0)
                    $("#btnConfirmarPedido").attr("disabled", true);
            },
            error: function(a, b, c) {
                toastr.warning("Não foi possível deletar os exames do pedido.");
            }
        });
    }

    function treatButton(buttons) {
        let buttonContent = buttons.text();
        let idPedido = $("input[name=idPedido]").val();
        let idExame = $("select[name=exame]").val();
        let idPaciente = $("select[name=paciente]").val();
        let trExame = buttons.parent();
        switch (buttonContent) {
            case "Adicionar":
                verificarPermissao(idExame);
                break;
            case "Cancelar Pedido":
                deletarExamesPedido(idPedido);
                deletarPedido(idPedido);
                break;
            case "Confirmar Pedido":
                alert("Pedido confirmado. Você será redirecionado para página inicial");
                window.location.assign("index.php");
                break;
            case "Deletar":
                deletarExamesEspecifico(idPedido, trExame);
                break;
            case "Registrar Pedido":
                $("#cadastrarPedido").hide();
                $("#pedidoCadastrado").show();
                registrarPedido(idPaciente);
                break;
        }
    }

    function focusTextArea(){
        $("#hipotese").focus();
    }

    $(document).ready(function() {
        $("#pedidoCadastrado").hide();
        $(document).on("click", "button", function(e) {
            treatButton($(this));
        });
    });
    </script>
</head>
<style>
.is-disable {
    pointer-events: none;
    color: lightgray;
}

.hidden {
    style: "display: none";
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
            <h4 class="title is-4">Solicitar Exame</h4>
        </div>
    </header>

    <?php
        if ($tipoUsuario != "med"){
            echo '<h4 style="text-align: center" class="title is-4">ACESSO EXCLUSIVO PARA MÉDICOS!</h4>';
            die;
        }
    ?>


    <main>
        <!-- Área central da página -->
        <div class="column is-half is-offset-one-quarter">
            <div class="column is-centered">
                <section id="cadastrarPedido">
                    <form class="form" method="POST">
                        <fieldset>
                            <p>
                                <label for="paciente" class="label"><b>Paciente</b></label>
                                <div class="select is-link">
                                <select aria-label="nome" name="paciente" required onchange="focusTextArea()">
                                    <option disabled selected value>Selecione um paciente</option>
                                    <?php
                                    $lista = listarPaciente();
                                    $id0 = $lista[0]["id"];
                                    $nome0 = $lista[0]["nomeCompleto"];
                                    echo "<option value='$id0'>$nome0</option>";
                                    for ($i=1; $i<count($lista); $i++) {
                                        $idPaciente = $lista[$i]["id"];
                                        $nome = $lista[$i]["nomeCompleto"];
                                        echo "<option value='$idPaciente'>$nome</option>";
                                    }
                                    ?>
                                </select>
                                </div>
                            </p>
                            <p>
                                <b>Hipótese</b>
                                <br><textarea class="textarea" style="resize: none" rows="4" cols="60" id="hipotese" name="descricaoHipotese" maxlength="400"
                                    required></textarea>
                            </p>
                        </fieldset>
                    </form>
                    <button type="submit" class="button is-success" style="margin-top: 7px"
                        id="btnCadastrarPedido">Registrar Pedido</button>
                </section>

                <section id="pedidoCadastrado">
                    <h4 class="title is-4">Dados do Pedido</h4>
                    <form class="form" method="POST">
                        <fieldset>
                            <p>
                                <b>Paciente</b>
                                <input class="input" type="text" id="nomePaciente" name="nome" disabled>
                            </p>
                            <p>
                                <b>Hipótese</b>
                                <br><textarea class="textarea" style="resize: none" rows="4" cols="60" id="hipotese" name="descricaoHipoteseCadastrada"
                                    maxlength="400" disabled></textarea>
                            <p>
                                <label for="exame" class="form-label"><b>Exames</b></label>
                                <div class="select is-link is-small">
                                <select aria-label="exame" name="exame" required>
                                    <option disabled selected value>Selecione os exames</option>
                                    <?php
                                    $lista = listarExame();
                                    $id0 = $lista[0]["id"];
                                    $nome0 = $lista[0]["nome"];
                                    echo "<option value='$id0'>$nome0</option>";
                                    for ($i=1; $i<count($lista); $i++) {
                                        $idExame = $lista[$i]["id"];
                                        $nome = $lista[$i]["nome"];
                                        echo "<option value='$idExame'>$nome</option>";
                                    }
                                    ?>
                                    
                                </select>
                                </div>
                                
                            </p>
                            <input type="hidden" name="idPedido" value="">
                        </fieldset>
                    </form>
                    <button class="button is-info is-small add" id="adicionar">Adicionar</button>

                    <p>
                    <table class="table is-narrow">
                        <thead>
                            <tr>
                                <th>Exame</th>
                                <th>Ação</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                    </p>
                    <button class="button is-danger" style="margin-top: 7px" id="btnCancelarPedido">Cancelar Pedido</button>
                    <button class="button is-primary" style="margin-top: 7px" id="btnConfirmarPedido" disabled>Confirmar Pedido</button>
                </section>
            </div>
        </div>

    </main>

    <?php include "footer.php" ?>

</body>

<?php

function listarPaciente(){
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

function listarExame(){
    require_once "database.php";

    $lista = [];
    try {
        $conexao = Conexao::getConexao();

        $stmt = $conexao->prepare("SELECT * FROM exame ORDER BY nome");
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