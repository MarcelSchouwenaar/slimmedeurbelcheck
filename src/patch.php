<?php
// filepath: /Users/Marcel/Sites/sdc/php/doe-de-check-php/src/check_sticker_sent_column.php

require_once 'includes/db.php';

$conn = getDbConnection();

// Check if the column 'StickerSent' exists in 'Applications'
$result = $conn->query("SHOW COLUMNS FROM Applications LIKE 'StickerSent'");
if ($result && $result->rowCount() === 0) {
    // Column does not exist, so add it
    $alter = "ALTER TABLE Applications ADD COLUMN StickerSent BOOLEAN DEFAULT FALSE";
    if ($conn->query($alter) === TRUE) {
        echo "Column 'StickerSent' successfully added to Applications table.";
    } else {
        echo "Error adding column: " . $conn->error;
    }
} else {
    echo "Column 'StickerSent' already exists in Applications table.";
}
?>