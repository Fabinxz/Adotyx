<?php
session_start();
require_once "./config/utils.php";
require_once "./config/verbs.php";
require_once "./config/header.php";
require_once "./model/Mensagem.php";
require_once "./model/Usuario.php";

$idUsuario = idUsuarioLogado();

if (isMetodo("GET")) {
    try {
        if (parametrosValidos($_GET, ["idSender"])) {
            // Implementar futuramente
        } else {
            $usuarios = Usuario::listarUsuarios();
            output(200, $usuarios);
        }
    } catch (Exception $e) {
        output($e->getCode(), ["msg" => $e->getMessage()]);
    }
}

if (isMetodo("POST")) {
    try {
        if (parametrosValidos($_POST, ["idDestinatario", "msg"])) {
            $idSender = $idUsuario;
            $idDestinatario = $_POST["idDestinatario"];
            $msg = $_POST["msg"];
            $res = Mensagem::enviarMensagem($idSender, $idDestinatario, $msg);
            if (!$res) {
                throw new Exception("Erro ao enviar mensagem", 500);
            }
            output(200, ["confirmacao" => "Mensagem enviada com sucesso!"]);
        } elseif (parametrosValidos($_POST, ["idDestinatario", "recuperarMensagem"])) {
            $idSender = $idUsuario;
            $idDestinatario = $_POST["idDestinatario"];
            $res = Mensagem::retornarMensagensChat($idSender, $idDestinatario);
            output(200, $res);
        } else {
            throw new Exception("Parâmetros inválidos", 400);
        }
    } catch (Exception $e) {
        output($e->getCode(), ["msg" => $e->getMessage()]);
    }
}
?>
