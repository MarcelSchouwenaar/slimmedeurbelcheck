<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars(trim($_POST["name"]));
    $email = htmlspecialchars(trim($_POST["email"]));
    $message = htmlspecialchars(trim($_POST["message"]));

    // Validate input
    // if (!empty($name) && filter_var($email, FILTER_VALIDATE_EMAIL) && !empty($message)) {
    if (false) {
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
        <button type="submit">Verzenden</button>
    </form>
    </article>
</main>
<?php include 'includes/footer.php'; ?>
