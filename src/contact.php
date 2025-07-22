<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars(trim($_POST["name"]));
    $email = htmlspecialchars(trim($_POST["email"]));
    $message = htmlspecialchars(trim($_POST["message"]));

    // Validate input
    if (!empty($name) && filter_var($email, FILTER_VALIDATE_EMAIL) && !empty($message)) {
        $to = "info@marcelschouwenaar.nl";
        $subject = "Contact Form Submission from " . $name;
        $body = "Name: $name\nEmail: $email\nMessage:\n$message";
        $headers = "From: $email";

        // Send email to the specified address
        if (mail($to, $subject, $body, $headers)) {
            // Send confirmation email to the sender
            $confirmationSubject = "Thank you for contacting us";
            $confirmationBody = "Dear $name,\n\nThank you for your message. We will get back to you shortly.\n\nYour message:\n$message";
            mail($email, $confirmationSubject, $confirmationBody, "From: info@marcelschouwenaar.nl");

            $notification = "Thank you for your message. We will get back to you shortly.";
        } else {
            $notification = "There was an error sending your message. Please try again later.";
        }
    } else {
        $notification = "Please fill in all fields correctly.";
    }
}
?>


<?php include 'includes/head.php'; ?>

    <?php include 'includes/nav.php'; ?>
    <div class="container">
        <h1>Contact Us</h1>
        <form action="contact.php" method="post">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="message">Message:</label>
                <textarea id="message" name="message" required></textarea>
            </div>
            <button type="submit">Send Message</button>
        </form>
    </div>
    <?php include 'includes/footer.php'; ?>
</body>
</html>