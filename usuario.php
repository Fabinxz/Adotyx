<?php
require_once "./config/utils.php";
require_once "./config/verbs.php";
require_once "./config/header.php";
require_once "./model/Mensagem.php";
require_once "./model/Usuario.php";
if (isMetodo("GET")) {
    try {
        if (parametrosValidos($_GET, ["id"])) {
            $filmeId = $_GET["id"];
            $filme = Filme::getFilmePorId($filmeId);

            if (!$filme) {
                output(404, ["msg" => "Filme não encontrado"]);
            }

            output(200, $filme);
        } else {
            $usuario = Usuario::listarUsuarios();
            output(200, $usuario);
        }
    } catch (Exception $e) {
        output($e->getCode(), ["msg" => $e->getMessage()]);
    }


}