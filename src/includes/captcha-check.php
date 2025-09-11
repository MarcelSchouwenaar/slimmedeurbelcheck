<?php
// filepath: /Users/Marcel/Sites/sdc/php/doe-de-check-php/src/includes/captcha-check.php
session_start();

header('Content-Type: application/json');

if (!isset($_GET['captcha'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'No captcha provided']);
    exit();
}

if (!isset($_SESSION['captcha'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'No captcha session']);
    exit();
}

if ($_GET['captcha'] == $_SESSION['captcha']) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Captcha mismatch']);
}
?>