<?php
// This file sets up the database and environment configuration.
// It can only be visited once per environment (localhost/production).

require_once 'includes/env.php';
require_once 'includes/db.php';

// Function to create the database tables
function setupDatabase($conn) {
    // SQL to create Neighbors table (now with ConfirmationToken)
    $sql1 = "CREATE TABLE IF NOT EXISTS Neighbors (
        Id INT(11) AUTO_INCREMENT PRIMARY KEY,
        Name VARCHAR(255) NOT NULL,
        Email VARCHAR(255) NOT NULL,
        FollowUp BOOLEAN DEFAULT FALSE,
        ConfirmationToken VARCHAR(64) DEFAULT NULL
    )";

    // SQL to create Applications table
    $sql2 = "CREATE TABLE IF NOT EXISTS Applications (
        Id INT(11) AUTO_INCREMENT PRIMARY KEY,
        ApplicationData VARCHAR(255) NOT NULL,
        NeighborOneId INT(11),
        NeighborTwoId INT(11),
        NeighborOneApproval BOOLEAN,
        NeighborTwoApproval BOOLEAN,
        NeighborOneFeedback VARCHAR(255) DEFAULT NULL,
        NeighborTwoFeedback VARCHAR(255) DEFAULT NULL,
        Zipcode VARCHAR(10) NOT NULL,
        Street VARCHAR(255) NOT NULL,
        HouseNumber VARCHAR(10) NOT NULL,
        Addition VARCHAR(50) DEFAULT NULL,
        FOREIGN KEY (NeighborOneId) REFERENCES Neighbors(Id),
        FOREIGN KEY (NeighborTwoId) REFERENCES Neighbors(Id),
        StickerSent BOOLEAN DEFAULT FALSE
    )";

    // Execute the queries
    if ($conn->query($sql1) === TRUE && $conn->query($sql2) === TRUE) {
        echo "Database setup completed successfully.";
    } else {
        echo "Error setting up database: " . $conn->error;
    }
}

// Check if the script is being run in the correct environment
if ($_SERVER['SERVER_NAME'] === 'localhost' || $_SERVER['SERVER_NAME'] === 'slimmedeurbelcheck.nl') {
    // Create database connection
    // Use the constants defined in env.php
    echo DB_HOST . ", " . DB_USER . ", " . DB_PASS . ", " . DB_NAME . ", " . DB_PORT;
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // --- Check if already installed ---
    $check = $conn->query("SHOW TABLES LIKE 'Applications'");
    if ($check && $check->num_rows > 0) {
        echo "<br>Database is already installed. Exiting.";
        $conn->close();
        exit();
    }

    // Call the setup function
    setupDatabase($conn);

    // Close the connection
    $conn->close();
} else {
    echo "This script can only be run in a local or production environment.";
}
?>