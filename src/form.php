<?php
// form.php

// Include necessary files
require_once 'includes/db.php';
require_once 'includes/env.php';
require_once 'includes/mail.php';
require_once 'includes/animations.php';

echo "<!-- Is Local: " . (IS_LOCAL ? "Yes" : "No") . "\n -->";

// --- Random data for development/demo ---

function randomZip() {
    if(!IS_LOCAL){ return ""; } // No random data in production
    $zips = [3181, 3197, 3198, 3150, 3151, 3190, 3191, 3192, 3193, 3194, 3199, 3195, 3000, 3001, 3002, 3003, 3004, 3005, 3006, 3007, 3008, 3009, 3011, 3012, 3013, 3014, 3015, 3016, 3021, 3022, 3023, 3024, 3025, 3026, 3027, 3028, 3029, 3031, 3032, 3033, 3034, 3035, 3036, 3037, 3038, 3039, 3041, 3042, 3043, 3044, 3045, 3046, 3047, 3050, 3051, 3052, 3053, 3054, 3055, 3056, 3059, 3061, 3062, 3063, 3064, 3065, 3066, 3067, 3068, 3069, 3071, 3072, 3073, 3074, 3075, 3076, 3077, 3078, 3079, 3081, 3082, 3083, 3084, 3085, 3086, 3087, 3088, 3089, 3196];
    $letters = ['AA', 'AB', 'AC'];
    $zip = $zips[array_rand($zips)];
    $letter = $letters[array_rand($letters)];
    return $zip . ' ' . $letter;
}
function randomNumber() {
    if(!IS_LOCAL){ return ""; } // No random data in production
    return rand(1, 5);
}

// $randomNameOne = randomName();
// $randomNameTwo = randomName();
// $randomEmailOne = randomEmail($randomNameOne);
// $randomEmailTwo = randomEmail($randomNameTwo);
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

    //This data was removed from the entry process
    $neighborOneName = "Niet gebruikt"; // <-- Placeholder for neighbor one
    $neighborOneEmail = "Niet gebruikt";
    $neighborOneFollowUp = 0;
    $neighborTwoName = "Niet gebruikt"; // <-- Placeholder for neighbor two
    $neighborTwoEmail = "Niet gebruikt";
    $neighborTwoFollowUp = 0;

    //only this data is being collected
    $zipcode = $_POST['zipcode'];
    $street = $_POST['street'];
    $houseNumber = $_POST['houseNumber'];
    $addition = $_POST['addition'];

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
    // $linkOneConfirm = BASE_URL . "/confirm.php?neighbor=" . urlencode($neighborOneId) . "&token=" . urlencode($tokenOne);
    // $linkTwoConfirm = BASE_URL . "/confirm.php?neighbor=" . urlencode($neighborTwoId) . "&token=" . urlencode($tokenTwo);
    // $linkOneObjection = BASE_URL . "/objection.php?neighbor=" . urlencode($neighborOneId) . "&token=" . urlencode($tokenOne);
    // $linkTwoObjection = BASE_URL . "/objection.php?neighbor=" . urlencode($neighborTwoId) . "&token=" . urlencode($tokenTwo);

    // Send confirmation mails using mail.php logic
    // confirmation_mail_without_objection($neighborOneEmail, $neighborOneName, $linkOneConfirm);
    // confirmation_mail($neighborTwoEmail, $neighborTwoName, $linkTwoConfirm, $linkTwoObjection);

    $successMsg = "Aanvraag succesvol! U ontvangt de sticker zo spoedig mogelijk thuis.";

    // --- Send confirmation email to info@slimmedeurbelcheck.nl ---
    $adminTo = 'info@slimmedeurbelcheck.nl';
    $adminSubject = "Nieuwe sticker aanvraag via slimmedeurbelcheck.nl";
    $adminBody = 
        "Er is een nieuwe sticker aanvraag ontvangen:\n\n" .
        "Straat: " . htmlspecialchars($street) . "\n" .
        "Huisnummer: " . htmlspecialchars($houseNumber) . "\n" .
        "Toevoeging: " . htmlspecialchars($addition) . "\n" .
        "Postcode: " . htmlspecialchars($zipcode) . "\n" .
        "Datum: " . date('Y-m-d H:i:s') . "\n";

    $adminHeaders = "From: Check met een Goed Gesprek <no-reply@slimmedeurbelcheck.nl>\r\n";
    $adminHeaders .= "Reply-To: no-reply@slimmedeurbelcheck.nl\r\n";
    $adminHeaders .= "Content-Type: text/plain; charset=UTF-8\r\n";

    if (!mail($adminTo, $adminSubject, $adminBody, $adminHeaders)) {
        error_log("Mail sending failed to $adminTo (admin notification)");
    }
}

?>
<?php include 'includes/head.php'; ?>
<?php include 'includes/nav.php'; ?>
<?php 
    if (isset($successMsg) || isset($errorMsg)) {
        echo "<main>";

        if (isset($successMsg)) {
            echo "<img src='assets/buurvrouw-okay.png' alt='Success' style='max-width: 240px; display: block; margin: 0 auto;'>";
            echo "<div class='notification success'>" . htmlspecialchars($successMsg) . "</div>";
        }
        if (isset($errorMsg)) {
            echo "<div class='notification error'>" . htmlspecialchars($errorMsg) . "</div>";
        }
        echo "</main>";
        include 'includes/footer.php';
        
        exit();
    } 
?>
<main class="col3 check-form">
    <aside class="check-form-aside">
        <div>
            <h1>Check met een Goed Gesprek</h1>
            <p class="">Sta je klaar met je buur?</p>
        </div>
        <div class="check-form-progress">
            <ul>
                <li class="check-form-progress-item active">Buren buiten beeld</li>
                <li class="check-form-progress-item">Voorbijgangers</li>        
                <li class="check-form-progress-item">Focus op eigen terrein</li>        
                <li class="check-form-progress-item">Buren zijn akkoord</li>        
                <li class="check-form-progress-item">Contactgegevens</li>        
        </div>
    </aside>
    <form method="post" action="">
        <div class="form-card animate" id="step1">
            <div class="form-card-header">
                <?php echo get_animation(1); ?>
            </div>
            <div class ="form-card-body">
                <p>Staat de bel niet gericht op huizen of tuinen van buren? Of staan de privacyinstellingen van de bel aan?</p>
            </div>
            <div class="form-card-input">
                <div class="form-group">
                    <label for="applicationDataStepOneYes">
                        <input type="radio" id="applicationDataStepOneYes" name="applicationDataStepOne" value="Ja" required>
                        Ja, huizen en tuinen van buren zijn niet in beeld.
                    </label>
                    <label for="applicationDataStepOneNoBut">
                        <input type="radio" id="applicationDataStepOneNoBut" name="applicationDataStepOne" value="NeeMaar">
                        Nee, maar de buren hebben geen bezwaren
                    </label>
                    <label for="applicationDataStepOneNo">
                        <input type="radio" id="applicationDataStepOneNo" name="applicationDataStepOne" value="Nee">Nee
                    </label>
                </div>
            </div>
        </div>
        <div class="form-card" id="step2">
            <div class="form-card-header">
                 <?php echo get_animation(2); ?>
            </div>
            <div class ="form-card-body">
                <p>Zijn voorbijgangers zo min mogelijk in beeld? Of staan de privacyinstellingen van de bel aan?</p>
            </div>
            <div class="form-card-input">
                <div class="form-group">
                    <label for="applicationDataStepTwoYes">
                        <input type="radio" id="applicationDataStepTwoYes" name="applicationDataStepTwo" value="Ja" required>
                        Ja, voorbijgangers zijn niet in beeld.
                    </label>
                    <label for="applicationDataStepTwoNo">
                        <input type="radio" id="applicationDataStepTwoNo" name="applicationDataStepTwo" value="Nee">
                        Nee
                    </label>
                </div>
            </div>
        </div>
        <div class="form-card" id="step3">
            <div class="form-card-header">
                <?php echo get_animation(3); ?>
            </div>
            <div class ="form-card-body">
                <p>Zijn alleen het eigen huis en de tuin in beeld? Of bezittingen op de stoep?</p>
            </div>
            <div class="form-card-input">
                <div class="form-group">
                    <label for="applicationDataStepThreeYes">
                        <input type="radio" id="applicationDataStepThreeYes" name="applicationDataStepThree" value="Ja" required>
                        Ja, alleen het eigen huis en de tuin en bezittingen zijn in beeld.
                    </label>
                    <label for="applicationDataStepThreeNo">
                        <input type="radio" id="applicationDataStepThreeNo" name="applicationDataStepThree" value="Nee">
                        Nee
                    </label>
                </div>
            </div>
        </div>
        <div class="form-card" id="step4">
            <div class="form-card-header">
                <?php echo get_animation(4); ?>
            </div>
            <div class ="form-card-body">
                <p>Is uw buur tevreden met de instellingen van de bel?</p>
            </div>
            <div class="form-card-input">
                <div class="form-group">
                    <label for="applicationDataStepFourYes">
                        <input type="radio" id="applicationDataStepFourYes" name="applicationDataStepFour" value="Ja" required>
                        Ja, de buur is tevreden met de instellingen van de bel.
                    </label>
                    <label for="applicationDataStepFourNo">
                        <input type="radio" id="applicationDataStepFourNo" name="applicationDataStepFour" value="Nee">
                        Nee
                    </label>
                </div>
            </div>
        </div>  
        <div class="form-card" id="step5">
            <div class="form-card-input">
                <h2>Waar moet de sticker naar toe?</h2>
                <p>Wij geven prioriteit aan uw privacy en proberen daarom zo weinig mogelijk gegevens te verzamelen. U ontvangt daarom geen bevestigingsmail.</p>
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
                 <div class="form-group">
                    <label for="zipcode">Postcode<span class="form-field-required">*</span></label>
                    <input type="text" id="zipcode" name="zipcode" required value="">
                    <!-- <?php echo $randomZip ?> -->
                </div>

                
            </div>
            <div class="form-card-footer">
                <p>Door op <strong>Verzend</strong> te klikken, gaat u akkoord met de verwerking van uw gegevens zoals beschreven in onze <a href="privacy.php">privacyverklaring</a>.</p>
                <input type="hidden" name="applicationData">
                <button type="submit">Verzend</button>
            </div>
        </div>
        
    </form>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Existing code for ApplicationData
    const form = document.querySelector('form');
    if (!form) return;

    form.addEventListener('submit', function(e) {
        try {
            // Collect values
            const hiddenInput = form.querySelector('input[name="applicationData"]');
            const data = {
                applicationDataStepOne: form.querySelector('[name="applicationDataStepOne"]:checked')?.value || "",
                applicationDataStepTwo: form.querySelector('[name="applicationDataStepTwo"]:checked')?.value || "",
                applicationDataStepThree: form.querySelector('[name="applicationDataStepThree"]:checked')?.value || "",
                applicationDataStepFour: form.querySelector('[name="applicationDataStepFour"]:checked')?.value || ""
            };
            hiddenInput.value = JSON.stringify(data);
        } catch (err) {
            e.preventDefault();
            alert("Er is een fout opgetreden. Controleer aub of u alle stappen hebt ingevuld.");
        }
    });

    // Smooth scroll to next card-spacer on radio change
    const cards = Array.from(document.querySelectorAll('.form-card'));
    const defaultDisplayValue = cards[4].style.display;
    const progressListItem = document.querySelectorAll('.check-form-progress li');
    const cardsToAnimate = document.querySelectorAll('.form-card');

    cards[cards.length - 1].style.display = 'none';


    const observer = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            entry.target.classList.toggle('animate', entry.isIntersecting);
        });
    }, {threshold: ".25"});
    
    cardsToAnimate.forEach(card => observer.observe(card));

    cards.forEach((card, idx) => {

        const radios = card.querySelectorAll('input[type="radio"]');
        radios.forEach(radio => {
            function scrollToStep(id) {
                setTimeout(() => {
                    location.hash = '#' + id;
                }, 200);
            }
            radio.addEventListener('change', function() {
                // Scroll to the next spacer (not the next card)
                const nextStep = cards[idx + 1];
                
                progressListItem[idx].classList.remove('active');
                progressListItem[idx].classList.add('checked');
                progressListItem[idx + 1].classList.add('active');

                if(idx == 3){
                    if(this.value === 'Nee') {
                        alert("U heeft aangegeven dat de buur niet akkoord is met de instellingen van de bel. Dan kunt u helaas geen sticker aanvragen.");
                        cards[idx + 1].style.display = 'none';
                    } else {
                        cards[idx + 1].style.display = defaultDisplayValue;
                    }
                }
                
                if (nextStep) {
                    cards[idx + 1].scrollIntoView({ behavior: 'smooth', block: 'center' });
                } else {
                    const stepFive = document.getElementById('step5');
                    if (stepFive) {
                        cards[4].scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }   
                }
            });
        });
    });
});
</script>
<?php include 'includes/footer.php'; ?>
