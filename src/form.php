<?php
// form.php

// Include necessary files
require_once 'includes/db.php';
require_once 'includes/env.php';
require_once 'includes/mail.php';

// --- Random data for development/demo ---
function randomName() {
    $first = ['Jan', 'Piet', 'Klaas', 'Marie', 'Sanne', 'Lisa', 'Tom', 'Eva'];
    $last = ['Jansen', 'de Vries', 'Bakker', 'Visser', 'Smit', 'Meijer', 'Mulder', 'Bos'];
    return $first[array_rand($first)] . ' ' . $last[array_rand($last)];
}
function randomEmail($name) {
    $domains = ['example.com', 'testmail.nl', 'mailinator.com'];
    $user = strtolower(str_replace(' ', '.', $name));
    return $user . rand(1,99) . '@' . $domains[array_rand($domains)];
}
function randomZip() {
    $numbers = str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT);
    $letters = chr(rand(65,90)) . chr(rand(65,90));
    return $numbers . $letters;
}
function randomNumber() {
    return rand(1, 99);
}

$randomNameOne = randomName();
$randomNameTwo = randomName();
$randomEmailOne = randomEmail($randomNameOne);
$randomEmailTwo = randomEmail($randomNameTwo);
$randomZip = randomZip();
$randomNumber = randomNumber();

// Initialize variables
$applicationData = '';
$neighborOneName = '';
$neighborOneEmail = '';
$neighborOneFollowUp = false;
$neighborTwoName = '';
$neighborTwoEmail = '';
$neighborTwoFollowUp = false;
$zipcode = '';
$houseNumber = '';
$addition = '';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $applicationData = $_POST['applicationData'];
    $neighborOneName = $_POST['neighborOneName'];
    $neighborOneEmail = $_POST['neighborOneEmail'];
    $neighborOneFollowUp = isset($_POST['neighborOneFollowUp']) ? 1 : 0;
    $neighborTwoName = $_POST['neighborTwoName'];
    $neighborTwoEmail = $_POST['neighborTwoEmail'];
    $neighborTwoFollowUp = isset($_POST['neighborTwoFollowUp']) ? 1 : 0;
    $zipcode = $_POST['zipcode'];
    $houseNumber = $_POST['houseNumber'];
    $addition = $_POST['addition'];

    $neighborOneEmail = filter_var($_POST['neighborOneEmail'], FILTER_VALIDATE_EMAIL);
    if (!$neighborOneEmail) {
        $errorMsg = "Ongeldig e-mailadres voor buur 1.";
    }

    $houseNumber = filter_var($_POST['houseNumber'], FILTER_VALIDATE_INT);
    if ($houseNumber === false) {
        $errorMsg = "Ongeldig huisnummer.";
    }

    // Generate unique confirmation tokens BEFORE inserting neighbors
    $tokenOne = bin2hex(random_bytes(16));
    $tokenTwo = bin2hex(random_bytes(16));

    $conn = getDbConnection();

    // Insert neighbors with tokens
    $stmt = $conn->prepare("INSERT INTO Neighbors (Name, Email, FollowUp, ConfirmationToken) VALUES (?, ?, ?, ?)");
    $stmt->execute([$neighborOneName, $neighborOneEmail, $neighborOneFollowUp, $tokenOne]);
    $neighborOneId = $conn->lastInsertId();

    $stmt->execute([$neighborTwoName, $neighborTwoEmail, $neighborTwoFollowUp, $tokenTwo]);
    $neighborTwoId = $conn->lastInsertId();

    // Insert application
    $stmt = $conn->prepare("INSERT INTO Applications (ApplicationData, NeighborOneId, NeighborTwoId, Zipcode, HouseNumber, Addition) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$applicationData, $neighborOneId, $neighborTwoId, $zipcode, $houseNumber, $addition]);
    $applicationId = $conn->lastInsertId();

    unset($stmt);

    // Build confirmation and objection URLs
    $linkOneConfirm = BASE_URL . "/confirm.php?neighbor=" . urlencode($neighborOneId) . "&token=" . urlencode($tokenOne);
    $linkTwoConfirm = BASE_URL . "/confirm.php?neighbor=" . urlencode($neighborTwoId) . "&token=" . urlencode($tokenTwo);
    $linkOneObjection = BASE_URL . "/objection.php?neighbor=" . urlencode($neighborOneId) . "&token=" . urlencode($tokenOne);
    $linkTwoObjection = BASE_URL . "/objection.php?neighbor=" . urlencode($neighborTwoId) . "&token=" . urlencode($tokenTwo);

    // Send confirmation mails using mail.php logic
    confirmation_mail($neighborOneEmail, $neighborOneName, $linkOneConfirm, $linkOneObjection);
    confirmation_mail($neighborTwoEmail, $neighborTwoName, $linkTwoConfirm, $linkTwoObjection);

    $successMsg = "Aanmelding succesvol! Uw buren ontvangen een bevestigingsmail.";
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'includes/head.php'; ?>
</head>
<body>
    <?php include 'includes/nav.php'; ?>

    <?php if (isset($successMsg)) echo "<p>$successMsg</p>"; ?>
    <?php if (isset($errorMsg)) echo "<p>$errorMsg</p>"; ?>
    <h1>Apply for a Sticker</h1>
    <form method="post" action="">
        <div>
            <h2>Application</h2>
            <label>
                <input type="radio" name="applicationData" value="Check 1" required> Check 1
            </label>
            <label>
                <input type="radio" name="applicationData" value="Check 2"> Check 2
            </label>
            <label>
                <input type="radio" name="applicationData" value="Check 3"> Check 3
            </label>
        </div>
        <div>
            <h2>Neighbor 1</h2>
            <label>Name: <input type="text" name="neighborOneName" required value="<?php echo $randomNameOne ?>"></label>
            <label>Email: <input type="email" name="neighborOneEmail" required value="<?php echo $randomEmailOne ?>"></label>
            <label>Approve for follow-up: <input type="checkbox" name="neighborOneFollowUp"></label>
        </div>
        <div>
            <h2>Neighbor 2</h2>
            <label>Name: <input type="text" name="neighborTwoName" required value="<?php echo $randomNameTwo ?>"></label>
            <label>Email: <input type="email" name="neighborTwoEmail" required value="<?php echo $randomEmailTwo ?>"></label>
            <label>Approve for follow-up: <input type="checkbox" name="neighborTwoFollowUp"></label>
        </div>
        <div>
            <h2>Address</h2>
            <label>Zipcode: <input type="text" name="zipcode" required value="<?php echo $randomZip ?>"></label>
            <label>House Number: <input type="text" name="houseNumber" required value="<?php echo $randomNumber ?>"></label>
            <label>Addition: <input type="text" name="addition"></label>
        </div>
        <button type="submit">Submit</button>
    </form>
    <?php include 'includes/footer.php'; ?>
</body>
</html>