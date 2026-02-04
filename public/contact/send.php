<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "SEND.PHP LOADED\n";

require __DIR__ . '/../../_config/mail.php';

echo "MAIL CONFIG LOADED\n";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(403);
    echo "Forbidden";
    exit;
}

echo "POST RECEIVED\n";

$name    = trim($_POST["name"] ?? "");
$email   = trim($_POST["email"] ?? "");
$subject = trim($_POST["subject"] ?? "");
$message = trim($_POST["message"] ?? "");

if (!$name || !$email || !$subject || !$message) {
    echo "Missing fields";
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "Invalid email";
    exit;
}

try {
    $mail = cosigo_mailer();
    echo "MAILER CREATED\n";

    $mail->setFrom('sales@cosigo.io', 'Cosigo Nixa');
    $mail->addAddress('sales@cosigo.io');
    $mail->addReplyTo($email, $name);

    $mail->Subject = "[Cosigo Nixa] " . $subject;
    $mail->Body =
        "Satellite: Nixa\n\n" .
        "Name: $name\n" .
        "Email: $email\n\n" .
        $message;

    echo "SENDING...\n";
    $mail->send();

    echo "OK";

} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage();
}
