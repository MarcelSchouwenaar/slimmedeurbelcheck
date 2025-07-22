<?php
// tests.php - Simple diagnostics for mail and database

include 'includes/env.php';
include 'includes/db.php';

echo "<h1>Doe de Check - Testpagina</h1>";

// Test 1: Database connection
echo "<h2>Databaseverbinding</h2>";
try {
    $conn = getDbConnection();
    if ($conn instanceof PDO) {
        echo "<p style='color:green;'>✅ Verbinding met database succesvol!</p>";
        // PDO closes automatically, but you can unset to be explicit
        unset($conn);
    } else {
        echo "<p style='color:red;'>❌ Verbinding met database mislukt: onbekend probleem.</p>";
    }
} catch (Exception $e) {
    echo "<p style='color:red;'>❌ Databasefout: " . htmlspecialchars($e->getMessage()) . "</p>";
}

// Test 2: E-mail verzenden
echo "<h2>E-mail verzenden</h2>";
$testMail = 'marcelschouwenaar@gmail.com';
$subject = 'Testmail van Doe de Check';
$message = "Dit is een testmail verzonden op " . date('Y-m-d H:i:s');
$headers = "From: info@marcelschouwenaar.nl\r\n";

if (mail($testMail, $subject, $message, $headers)) {
    echo "<p style='color:green;'>✅ Testmail succesvol verzonden naar $testMail (controleer je mail.log of mailbox).</p>";
} else {
    echo "<p style='color:red;'>❌ Testmail kon niet worden verzonden.</p>";
}

?>