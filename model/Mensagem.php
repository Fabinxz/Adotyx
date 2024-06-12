<?php

require_once (__DIR__ . "/../config/Conexao.php");

class Mensagem
{
    public static function enviarMensagem($idUsuarioSender, $idUsuarioReciever, $mensagem)
    {
        try {
            $conexao = Conexao::getConexao();
            $stmt = $conexao->prepare("INSERT INTO ChatMessages (sender_id, receiver_id, mensagem) VALUES (?, ?, ?)");
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
            $stmt = $conexao->prepare("SELECT * FROM ChatMessages WHERE (sender_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?) ORDER BY data_envio");
            $stmt->execute([$idUsuarioSender, $idUsuarioReciever, $idUsuarioReciever, $idUsuarioSender]);
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }
}

?>
