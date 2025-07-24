<?php
// objection.php

// Include necessary files
require_once 'includes/env.php';
require_once 'includes/db.php';
require_once 'includes/mail.php';

// Start session to store feedback
session_start();

// Check if GET parameters are set
if (isset($_GET['neighbor']) && isset($_GET['token'])) {
    $neighborId = $_GET['neighbor'];
    $token = $_GET['token'];

    $conn = getDbConnection();

    // Verify token for this neighbor
    $stmt = $conn->prepare("SELECT Id, Name, Email, ConfirmationToken FROM Neighbors WHERE Id = ? LIMIT 1");
    $stmt->execute([$neighborId]);
    $neighbor = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$neighbor || $neighbor['ConfirmationToken'] !== $token) {
        $message = "<p>Ongeldige of verlopen bezwaarlink.</p>";
    } else {
        // Find the application for this neighbor
        $stmt = $conn->prepare("SELECT Id, NeighborOneId, NeighborTwoId, NeighborOneApproval, NeighborTwoApproval FROM Applications WHERE NeighborOneId = ? OR NeighborTwoId = ? LIMIT 1");
        $stmt->execute([$neighborId, $neighborId]);
        $application = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$application) {
            $message = "<p>Geen aanvraag gevonden voor deze buur.</p>";
        } else {
            // Check if both approvals have already been set (either 0 or 1)
            if (
                $application['NeighborOneApproval'] !== null &&
                $application['NeighborTwoApproval'] !== null
            ) {
                $message = "<p>Deze aanvraag is al volledig verwerkt en kan niet opnieuw worden bevestigd of geweigerd.</p>";
            } else {
                // Handle objection form submission
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $feedback = $_POST['feedback'];
                    $shareFeedback = isset($_POST['share_feedback']) ? 1 : 0;

                    if ($application['NeighborOneId'] == $neighborId) {
                        $stmt = $conn->prepare("UPDATE Applications SET NeighborOneApproval = 0, NeighborOneFeedback = ? WHERE Id = ?");
                        $stmt->execute([$shareFeedback ? $feedback : "[private]$feedback[private]", $application['Id']]);
                    } else {
                        $stmt = $conn->prepare("UPDATE Applications SET NeighborTwoApproval = 0, NeighborTwoFeedback = ? WHERE Id = ?");
                        $stmt->execute([$shareFeedback ? $feedback : "[private]$feedback[private]", $application['Id']]);
                    }

                    // Send Mail 2: Objection to both neighbors
                    $stmt = $conn->prepare("SELECT Name, Email FROM Neighbors WHERE Id IN (?, ?)");
                    $stmt->execute([$application['NeighborOneId'], $application['NeighborTwoId']]);
                    $neighbors = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    // Fetch feedbacks for rejection_mail
                    $stmt = $conn->prepare("SELECT NeighborOneFeedback, NeighborTwoFeedback FROM Applications WHERE Id = ?");
                    $stmt->execute([$application['Id']]);
                    $feedbacks = $stmt->fetch(PDO::FETCH_ASSOC);

                    foreach ($neighbors as $n) {
                        // Only show public feedback
                        $feedbackToSend = '';
                        if ($n['Id'] == $application['NeighborOneId']) {
                            $feedbackToSend = $feedbacks['NeighborOneFeedback'];
                        } else {
                            $feedbackToSend = $feedbacks['NeighborTwoFeedback'];
                        }
                        rejection_mail($n['Email'], $n['Name'], $feedbackToSend);
                    }

                    $message = "<p>Uw bezwaar is geregistreerd. De aanvraag kan niet worden goedgekeurd.</p>";
                } else {
                    // Show objection form
                    $showForm = true;
                }
            }
        }
    }
    unset($conn);
} else {
    $message = "<p>Ongeldige aanvraag.</p>";
}

include 'includes/head.php';
include 'includes/nav.php';
?>

<main>
    <h1>Bezwaar maken</h1>
    <?php
    if (isset($message)) {
        echo $message;
    }
    if (isset($showForm) && $showForm): ?>
        <form method="POST">
            <div>
                <label for="feedback">Uw feedback:</label>
                <textarea id="feedback" name="feedback" required></textarea>
            </div>
            <div>
                <label>
                    <input type="checkbox" name="share_feedback"> Deel deze feedback met de andere buur
                </label>
            </div>
            <button type="submit">Verzend bezwaar</button>
        </form>
    <?php endif; ?>
</main>

<?php include 'includes/footer.php'; ?>