<?php

require_once (__DIR__ . "/../config/Conexao.php");

class Mensagem
{
    public static function enviarMensagem($idUsuarioSender, $idUsuarioReciever, $mensagem)
    {
        try {
            $conexao = Conexao::getConexao();
            $stmt = $conexao->prepare("INSERT INTO chat (idRemetente, idDestinatario, conteudo) VALUES (?, ?, ?)");
            $stmt->execute([$idUsuarioSender, $idUsuarioReciever, $mensagem]);

            return $stmt->rowCount() === 1;
        } catch (Exception $e) {
            return false;
        }
    }
    
    public static function retornarMensagensChat($idUsuarioSender, $idUsuarioReciever)
    {
        try {
            $conexao = Conexao::getConexao();
            $stmt = $conexao->prepare("SELECT * FROM chat WHERE (idRemetente = ? AND idDestinaraio = ?) OR (idDestinatario = ? AND idRemetente = ?) ORDER BY data_envio");
            $stmt->execute([$idUsuarioSender, $idUsuarioReciever, $idUsuarioReciever, $idUsuarioSender]);
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }
}

?>
