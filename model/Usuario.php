<?php

require_once (__DIR__ . "/../config/Conexao.php");

class Usuario
{
    public static function cadastrarUsuario($username, $password, $email)
    {
        try {
            $conexao = Conexao::getConexao();
            $stmt = $conexao->prepare("INSERT INTO Usuarios (username, password, email) VALUES (?, ?, ?)");
            $stmt->execute([$username, $password, $email]);

            return $stmt->rowCount() === 1;
        } catch (Exception $e) {
            return false;
        }
    }
    
    public static function listarUsuarios()
    {
        try {
            $conexao = Conexao::getConexao();
            $stmt = $conexao->query("SELECT * FROM Users");
            
            // Retornar diretamente o array de usuários
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }
    
}

?>
