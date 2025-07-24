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
    $zips = [3181, 3197, 3198, 3150, 3151, 3190, 3191, 3192, 3193, 3194, 3199, 3195, 3000, 3001, 3002, 3003, 3004, 3005, 3006, 3007, 3008, 3009, 3011, 3012, 3013, 3014, 3015, 3016, 3021, 3022, 3023, 3024, 3025, 3026, 3027, 3028, 3029, 3031, 3032, 3033, 3034, 3035, 3036, 3037, 3038, 3039, 3041, 3042, 3043, 3044, 3045, 3046, 3047, 3050, 3051, 3052, 3053, 3054, 3055, 3056, 3059, 3061, 3062, 3063, 3064, 3065, 3066, 3067, 3068, 3069, 3071, 3072, 3073, 3074, 3075, 3076, 3077, 3078, 3079, 3081, 3082, 3083, 3084, 3085, 3086, 3087, 3088, 3089, 3196];
    $letters = ['AA', 'AB', 'AC'];
    $zip = $zips[array_rand($zips)];
    $letter = $letters[array_rand($letters)];
    return $zip . ' ' . $letter;
}
function randomNumber() {
    return rand(1, 5);
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
$street = ''; // <-- Added
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
    $street = $_POST['street']; // <-- Added
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

    // Insert application (now also storing street)
    $stmt = $conn->prepare("INSERT INTO Applications (ApplicationData, NeighborOneId, NeighborTwoId, Zipcode, Street, HouseNumber, Addition) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$applicationData, $neighborOneId, $neighborTwoId, $zipcode, $street, $houseNumber, $addition]);
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
<?php include 'includes/head.php'; ?>
<?php include 'includes/nav.php'; ?>
<main>
    <?php if (isset($successMsg)) echo "<p>$successMsg</p>"; ?>
    <?php if (isset($errorMsg)) echo "<p>$errorMsg</p>"; ?>
    <article>
        <h1>Check met een Goed Gesprek</h1>
        <p class="lead">Zijn jullie er klaar voor?</p>
    </article>
    <form method="post" action="POST">
        <div class="form-group">
            <input type="radio" id="check1" name="applicationData" value="Check 1" required>
            <label for="check1">Check 1</label>
            <input type="radio" id="check2" name="applicationData" value="Check 2">
            <label for="check2">Check 2</label>
            <input type="radio" id="check3" name="applicationData" value="Check 3">
            <label for="check3">Check 3</label>
        </div>
        <div class="form-section">
            <h2>Buur 1</h2>
            <div class="form-group">
                <label for="neighborOneName">Naam<span class="form-field-required">*</span></label>
                <input type="text" id="neighborOneName" name="neighborOneName" required value="<?php echo $randomNameOne ?>">
            </div>
            <div class="form-group">
                <label for="neighborOneEmail">Email<span class="form-field-required">*</span></label>
                <input type="email" id="neighborOneEmail" name="neighborOneEmail" required value="<?php echo $randomEmailOne ?>">
            </div>
            <div class="form-group form-group-checkbox">
                <label for="neighborOneFollowUp"><input type="checkbox" id="neighborOneFollowUp" name="neighborOneFollowUp">
                U mag mij later benaderen voor onderzoek.</label>
            </div>
        </div>
        <div class="form-section">
            <h2>Buur 2</h2>
            <div class="form-group">
                <label for="neighborTwoName">Naam<span class="form-field-required">*</span></label>
                <input type="text" id="neighborTwoName" name="neighborTwoName" required value="<?php echo $randomNameTwo ?>">
            </div>
            <div class="form-group">
                <label for="neighborTwoEmail">Email<span class="form-field-required">*</span></label>
                <input type="email" id="neighborTwoEmail" name="neighborTwoEmail" required value="<?php echo $randomEmailTwo ?>">
            </div>
            <div class="form-group form-group-checkbox">
                <label for="neighborTwoFollowUp"><input type="checkbox" id="neighborTwoFollowUp" name="neighborTwoFollowUp">
                U mag mij later benaderen voor onderzoek.</label>
            </div>
        </div>
        <div class="form-section">
            <h2>Waar moet de sticker naar toe?</h2>
            <div class="form-group">
                <label for="zipcode">Postcode<span class="form-field-required">*</span></label>
                <input type="text" id="zipcode" name="zipcode" required value="">
                <!-- <?php echo $randomZip ?> -->
            </div>
            <div class="form-group">
                <label for="street">Straat<span class="form-field-required">*</span></label>
                <input type="text" id="street" name="street" required value="">
                <!-- <?php echo $randomNumber ?> -->
            </div>
            <div class="form-group">
                <label for="houseNumber">Huisnummer<span class="form-field-required">*</span></label>
                <input type="text" id="houseNumber" name="houseNumber" required value="">
                <!-- <?php echo $randomNumber ?> -->
            </div>
            <div class="form-group">
                <label for="addition">Toevoeging</label>
                <input type="text" id="addition" name="addition" value="">
            </div>
            
        </div>
        <div class="form-section">
            <h2>Bevestig uw aanvraag</h2>
            <p>U ontvangt een bevestigingsmail met daarin de details van uw aanvraag en de mogelijkheid om bezwaar te maken.</p>
            <p>Door op <strong>Verzend</strong> te klikken, gaat u akkoord met de verwerking van uw gegevens zoals beschreven in onze <a href="privacy.php">privacyverklaring</a>.</p>
            <button type="submit">Verzend</button>
        </div>
        
    </form>
</main>

<?php include 'includes/footer.php'; ?>
