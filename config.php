<?php

// Configurações do banco de dados
$serverName = "localhost\\SQLEXPRESS"; // IP,PORTA ou se o SQL Server está rodando na mesma máquina que o servidor web
$connectionOptions = array(
    "Database" => "pangya",       // Nome do banco de dados
    "Uid" => "pangya",            // Usuário do banco de dados
    "PWD" => "pangya",            // Senha do banco de dados
    "CharacterSet" => "UTF-8"     // Definindo o charset para UTF-8
);

// Configurações de e-mail
$mailConfig = array(
    "SMTP" => "smtp.???.com.br",            // Servidor SMPT
    "smtp_port" => 587,                     // Porta SMPT
    "username" => "???@pangyaex.com.br",    // Email
    "password" => "???",                    // Senha
    "from" => "no-reply@pangyaex.com.br"    // Enviado de
);

// Configurações do Google reCAPTCHA v2
$recaptchaConfig = array(
    "site_key" => "SUA_SITE_KEY_DO_RECAPTCHA",
    "secret_key" => "SUA_SECRET_KEY_DO_RECAPTCHA"
);

$conn = sqlsrv_connect($serverName, $connectionOptions);
if ($conn === false) {
    die(print_r(sqlsrv_errors(), true));
}
?>
