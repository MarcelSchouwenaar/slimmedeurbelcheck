<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate captcha first
    if (!isset($_SESSION['captcha'])) {
        $notification = "Captcha is verlopen of niet ingesteld. Probeer het opnieuw.";
    } elseif (!isset($_POST['captcha']) || $_POST['captcha'] != $_SESSION['captcha']) {
        $notification = "De ingevulde captcha is onjuist. Probeer het opnieuw.";
    } else {
        $name = htmlspecialchars(trim($_POST["name"]));
        $email = htmlspecialchars(trim($_POST["email"]));
        $message = htmlspecialchars(trim($_POST["message"]));

        // Validate input
        if (!empty($name) && filter_var($email, FILTER_VALIDATE_EMAIL) && !empty($message)) {
        // if (false) {
        $to = "info@slimmedeurbelcheck.nl";
        $subject = "Contact Form Submission from " . $name;
        $body = "Name: $name\nEmail: $email\nMessage:\n$message";
        $headers = "From: $email";

        // Send email to the specified address
        if (mail($to, $subject, $body, $headers)) {
            // Send confirmation email to the sender
            $confirmationSubject = "Dank voor uw bericht - Slimme Deurbel Check";
            $confirmationBody = "Beste $name,\n\nDank voor uw bericht. We zullen zo snel mogelijk contact met u opnemen. \n\nUw bericht:\n$message";
            mail($email, $confirmationSubject, $confirmationBody, "From: info@marcelschouwenaar.nl");

            $notification = "Dank voor uw bericht. We zullen zo snel mogelijk contact met u opnemen.";
        } else {
            $notification = "There was an error sending your message. Please try again later.";
        }
    } else {
        $notification = "Er is iets misgegaan. Kunt u het later nogmaals proberen?";
    }
    }
}
?>


<?php include 'includes/head.php'; ?>
<?php include 'includes/nav.php'; ?>
<main>
    <article>
    <h1>Contact</h1>
    <p class="lead">Heeft u vragen of opmerkingen? Vul het onderstaande formulier in en we nemen zo snel mogelijk contact met u op.</p> 
    <?php if (isset($notification)): ?>
        <div class="notification"><?php echo htmlspecialchars($notification); ?></div>
    <?php endif; ?>
    <form action="contact.php" method="post" class="contact-form">
        <div class="form-group">
            <label for="name">Uw naam</label>
            <input type="text" id="name" name="name" required>
        </div>
        <div class="form-group">
            <label for="email">Uw email</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="message">Uw bericht</label>
            <textarea id="message" name="message" required style="height: 300px;"></textarea>
        </div>
        <div class="form-group">
            <label for="captcha">Welke cijfers staan er in dit plaatje?<span class="form-field-required">*</span></label>
            <div>
                <img class="captcha" src="includes/captcha.php" width="160" height="48">
                <span title="Plaatje niet goed leesbaar? Klik hier voor een nieuw plaatje" class="captcha-new">Niet goed leesbaar.</span>
            </div>
            <input type="text" id="captcha" name="captcha" required value="">
        </div>
        <button type="submit">Verzenden</button>
    </form>
    </article>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('.contact-form');
    
    // Captcha refresh functionality
    const newCaptcha = form.querySelector('.captcha-new');
    newCaptcha.addEventListener('click', function() {
        const captchaImage = form.querySelector('.captcha');
        captchaImage.src = 'includes/captcha.php?' + Date.now();
    });

    // Form submission with captcha validation
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Validate captcha by sending the value to includes/captcha-check.php via GET
        const captchaInput = form.querySelector('[name="captcha"]');
        const captchaValue = encodeURIComponent(captchaInput.value);

        fetch('includes/captcha-check.php?captcha=' + captchaValue, {
            method: 'GET',
            credentials: 'same-origin'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // If captcha is valid, proceed with form submission
                form.submit();
            } else {
                alert("Ongeldige code. Probeer het opnieuw.");
                const captchaImage = form.querySelector('.captcha');
                captchaImage.src = 'includes/captcha.php?' + Date.now();
                captchaInput.value = '';
                captchaInput.focus();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert("Er is een fout opgetreden bij het valideren van de captcha. Probeer het opnieuw.");
        });
    });
});
</script>

<?php include 'includes/footer.php'; ?>
