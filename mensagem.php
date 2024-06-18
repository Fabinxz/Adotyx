<?php
require_once "./config/utils.php";
require_once "./config/verbs.php";
require_once "./config/header.php";
require_once "./model/Mensagem.php";
require_once "./model/Usuario.php";



if (isMetodo("GET")) {
    try {
        if (parametrosValidos($_GET, ["idSender"])) {
            /*implementar futuramente*/
        } else {
            $usuario = Usuario::listarUsuarios();
            output(200, $usuario);
        }
    } catch (Exception $e) {
        output($e->getCode(), ["msg" => $e->getMessage()]);
    }



}

if (isMetodo("POST")) {
    try {
        if (parametrosValidos($_POST, ["idSender", "idReciever", "msg"])) {



            $idSender = $_POST["idSender"];
            $idReciever = $_POST["idReciever"];
            $msg = $_POST["msg"];
            $res = Mensagem::enviarMensagem($idSender, $idReciever, $msg);
            if (!$res) {
                throw new Exception("Erro ao enviar mensagem", 500);
            }
            output(200, ["confirmacao" => "Mensagem enviada com sucesso!"]);


        }

        if (parametrosValidos($_POST, ["idSender", "idReciever", "recuperarMensagem"])) {
        
        $idSender = $_POST["idSender"];
        $idReciever = $_POST["idReciever"];
        $res = Mensagem::retornarMensagensChat($idSender, $idReciever);
        output(200, $res);
        }

    } catch (Exception $e) {
        output($e->getCode(), ["confirmacao" => $e->getMessage()]);
    }


}