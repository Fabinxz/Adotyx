<?php
session_start();

require_once "./config/utils.php"; // Ajuste o caminho conforme necessário
require_once "./model/Usuario.php"; // Ajuste o caminho conforme necessário

$targetDir = "./uploads/profile-pictures/";

function returnJson($code, $msg) {
    header('Content-Type: application/json');
    http_response_code($code);
    echo json_encode(['msg' => $msg]);
    exit;
}

// Verifica se foi enviado um arquivo
if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
    http_response_code(400);
    echo json_encode(['msg' => 'Erro ao enviar a foto de perfil']);
    exit;
}

// Obtém o ID do usuário logado (substitua conforme a sua lógica de autenticação)
$idUsuario = idUsuarioLogado(); // Implemente sua lógica para obter o ID do usuário

// Gera um nome único para o arquivo com o ID do usuário na frente
$fileName = $idUsuario . '_' . uniqid('profile_') . '_' . basename($_FILES['file']['name']);
$uploadPath = $targetDir . $fileName;

// Remove a imagem anterior do usuário, se existir
$oldImagePath = glob($targetDir . $idUsuario . '_profile_*');
if ($oldImagePath) {
    unlink($oldImagePath[0]); // Remove apenas a primeira imagem encontrada (deve ser apenas uma)
}

// Move o arquivo do diretório temporário para o diretório de upload
if (!move_uploaded_file($_FILES['file']['tmp_name'], $uploadPath)) {
    http_response_code(500);
    echo json_encode(['msg' => 'Erro ao salvar a foto de perfil']);
    exit;
}

// try {
//     $image = new \claviska\SimpleImage();
//     $image->fromFile($uploadPath)->bestFit(400, 400)->toFile($uploadPath);
// } catch (Exception $e) {
//     http_response_code(500);
//     echo json_encode(['msg' => 'Erro ao redimensionar a imagem: ' . $e->getMessage()]);
//     exit;
// }
// Salva o caminho da imagem no banco de dados usando a classe Usuario
$res = Usuario::salvarFotoPerfil($idUsuario, $uploadPath);

if ($res) {
    // Retorna o caminho da foto de perfil para ser utilizado na interface
    echo json_encode(['msg' => 'Foto de perfil enviada e atualizada com sucesso.', 'foto_perfil' => $uploadPath]);
} else {
    http_response_code(500);
    echo json_encode(['msg' => 'Erro ao atualizar a foto de perfil na base de dados.']);
}
?>
