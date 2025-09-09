<?php
/**
 * mail.php
 * Central mail logic for Check met een Goed Gesprek
 */

// Ensure SENDER_EMAIL and REPLY_TO_EMAIL are defined in env.php

function confirmation_mail($to, $name, $confirm_link, $objection_link) {
    $subject = "Bevestig jullie Check - Check met een Goed Gesprek";
    // Load template
    $template = file_get_contents(__DIR__ . '/../templates/mail1.html');
    $body = str_replace(
        ['{{name}}', '{{confirm_link}}', '{{objection_link}}'],
        [htmlspecialchars($name), $confirm_link, $objection_link],
        $template
    );
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    $headers .= "From: Check met een Goed Gesprek <" . SENDER_EMAIL . ">\r\n";
    $headers .= "Reply-To: " . REPLY_TO_EMAIL . "\r\n";
    if (!mail($to, $subject, $body, $headers)) {
        error_log("Mail sending failed to $to (confirmation_mail)");
    }
}

function confirmation_mail_without_objection($to, $name, $confirm_link) {
    $subject = "Bevestig jullie Check - Check met een Goed Gesprek";
    // Load template
    $template = file_get_contents(__DIR__ . '/../templates/mail0.html');
    $body = str_replace(
        ['{{name}}', '{{confirm_link}}'],
        [htmlspecialchars($name), $confirm_link],
        $template
    );
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    $headers .= "From: Check met een Goed Gesprek <" . SENDER_EMAIL . ">\r\n";
    $headers .= "Reply-To: " . REPLY_TO_EMAIL . "\r\n";
    if (!mail($to, $subject, $body, $headers)) {
        error_log("Mail sending failed to $to (confirmation_mail_without_objection)");
    }
}

function approval_mail($to, $name) {
    $subject = "Jullie sticker komt eraan! - Check met een Goed Gesprek";
    $template = file_get_contents(__DIR__ . '/../templates/mail3.html');
    $body = str_replace(
        ['{{name}}'],
        [htmlspecialchars($name)],
        $template
    );
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    $headers .= "From: Check met een Goed Gesprek <" . SENDER_EMAIL . ">\r\n";
    $headers .= "Reply-To: " . REPLY_TO_EMAIL . "\r\n";
    if (!mail($to, $subject, $body, $headers)) {
        error_log("Mail sending failed to $to (approval_mail)");
    }
}

function rejection_mail($to, $name, $feedback = null) {
    $subject = "Helaas, uw aanvraag kon niet worden verwerkt - Check met een Goed Gesprek";
    $template = file_get_contents(__DIR__ . '/../templates/mail2.html');
    // Handle feedback block
    if ($feedback && strpos($feedback, '[private]') === false) {
        $feedbackBlock = str_replace('{{feedback}}', nl2br(htmlspecialchars($feedback)), 
            '<div class="feedback-block"><strong>Feedback van uw buur:</strong><br>{{feedback}}</div>');
        $body = str_replace('{{#if feedback}}', $feedbackBlock, $template);
    } else {
        // Remove feedback block if not present or private
        $body = preg_replace('/\{\{#if feedback\}\}.*?\{\{\/if\}\}/s', '', $template);
    }
    $body = str_replace('{{name}}', htmlspecialchars($name), $body);
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    $headers .= "From: Check met een Goed Gesprek <" . SENDER_EMAIL . ">\r\n";
    $headers .= "Reply-To: " . REPLY_TO_EMAIL . "\r\n";
    if (!mail($to, $subject, $body, $headers)) {
        error_log("Mail sending failed to $to (rejection_mail)");
    }
}