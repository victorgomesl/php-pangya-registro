<?php
require 'vendor/autoload.php';
use Firebase\JWT\JWT;
require 'config.php';

if (!isset($_GET['token'])) {
    header("Location: confirm_error.html");
    exit;
}

$token = $_GET['token'];
$key = 'chave_secreta';

try {
    $decoded = JWT::decode($token, $key, array('HS256'));
    $UID = $decoded->UID;

    $updateQuery = "UPDATE pangya.account SET IDState = 0 WHERE UID = ?";
    $params = array($UID);
    $updateStmt = sqlsrv_query($conn, $updateQuery, $params);

    if (!$updateStmt) {
        throw new Exception("Erro ao atualizar o status do usuário.");
    }

    header("Location: confirm_success.html");
    exit;
} catch (Exception $e) {
    header("Location: confirm_error.html");
    exit;
}
?>
