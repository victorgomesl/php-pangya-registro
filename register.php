<?php
require 'vendor/autoload.php';
use Firebase\JWT\JWT;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'config.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$nome = $data['nome'];
$sobrenome = $data['sobrenome'];
$email = $data['email'];
$senha = password_hash($data['senha'], PASSWORD_BCRYPT);
$loginId = $data['loginId'] ?? uniqid();
$ipDoUsuario = $_SERVER['REMOTE_ADDR'] ?: '127.0.0.1';

$recaptchaUrl = 'https://www.google.com/recaptcha/api/siteverify';
$recaptchaSecret = $recaptchaConfig['secret_key'];
$response = file_get_contents($recaptchaUrl . '?secret=' . $recaptchaSecret . '&response=' . $recaptchaResponse);
$responseKeys = json_decode($response, true);

if (!$responseKeys['success']) {
    echo json_encode(['message' => 'Falha na verificação do reCAPTCHA.']);
    exit;
}

try {
    // Verifica se o e-mail já está em uso
    $emailCheckQuery = "SELECT email FROM pangya.contas_beta WHERE email = ?";
    $emailCheckStmt = sqlsrv_query($conn, $emailCheckQuery, array($email));
    if (sqlsrv_has_rows($emailCheckStmt)) {
        echo json_encode(['message' => 'Email já cadastrado.']);
        exit;
    }

    // Parâmetros da procedure
    $procedureParams = [
        array(&$nome, SQLSRV_PARAM_IN),
        array(&$sobrenome, SQLSRV_PARAM_IN),
        array(&$email, SQLSRV_PARAM_IN),
        array(&$loginId, SQLSRV_PARAM_IN),
        array(&$senha, SQLSRV_PARAM_IN),
        array(&$ipDoUsuario, SQLSRV_PARAM_IN)
    ];

    $procedureQuery = "{call pangya.ProcMakeUserBeta(?, ?, ?, ?, ?, ?)}";
    $stmt = sqlsrv_query($conn, $procedureQuery, $procedureParams);

    if ($stmt === false) {
        $errors = sqlsrv_errors();
        throw new Exception('Erro ao executar a procedure: ' . json_encode($errors));
    }

    // Recuperando o UID
    $UIDQuery = "SELECT UID FROM pangya.account WHERE ID = ?";
    $UIDStmt = sqlsrv_query($conn, $UIDQuery, array($loginId));
    if ($UIDStmt === false || !sqlsrv_has_rows($UIDStmt)) {
        throw new Exception('Erro ao buscar UID: ' . json_encode(sqlsrv_errors()));
    }
    $UIDResult = sqlsrv_fetch_array($UIDStmt, SQLSRV_FETCH_ASSOC);
    $UID = $UIDResult['UID'];

    // Criando token JWT
    $key = 'chave_secreta';
    $payload = ['UID' => $UID, 'email' => $email, 'exp' => strtotime('+1 day')];
    $jwt = JWT::encode($payload, $key);
    $confirmLink = "http://localhost:8080/confirm.php?token=$jwt"; // Aplicar o dominio correto no lugar de localhost:8080

    // Configuração e envio do e-mail usando PHPMailer
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = $mailConfig['SMTP'];
    $mail->SMTPAuth = true;
    $mail->Username = $mailConfig['username'];
    $mail->Password = $mailConfig['password'];
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = $mailConfig['smtp_port'];
    $mail->setFrom($mailConfig['from'], 'Pangya Registration');
    $mail->addAddress($email);
    $mail->Subject = "Confirmação de cadastro";
    $mail->Body = "Clique no link para confirmar seu email: $confirmLink";

    if (!$mail->send()) {
        throw new Exception('Erro ao enviar email de confirmação: ' . $mail->ErrorInfo);
    }

    echo json_encode(['message' => 'Cadastro realizado com sucesso! Email de confirmação enviado.']);
} catch (Exception $e) {
    echo json_encode(['message' => 'Erro ao cadastrar usuário', 'error' => $e->getMessage()]);
}
