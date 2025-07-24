<?php
require_once 'includes/db.php';
require_once 'includes/env.php';
require_once 'includes/mail.php';

$pageTitle = "Bevestiging";

ob_start();

if (isset($_GET['neighbor']) && isset($_GET['token'])) {
    $neighborId = $_GET['neighbor'];
    $token = $_GET['token'];

    $conn = getDbConnection();

    // Verify token for this neighbor (only NeighborOne is allowed to confirm)
    $stmt = $conn->prepare("SELECT Id, Name, Email, ConfirmationToken FROM Neighbors WHERE Id = ? LIMIT 1");
    $stmt->execute([$neighborId]);
    $neighbor = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$neighbor || $neighbor['ConfirmationToken'] !== $token) {
        $message = "<p>Ongeldige of verlopen bevestigingslink.</p>";
    } else {
        // Find the application for this neighbor as NeighborOne
        $stmt = $conn->prepare("SELECT Id, NeighborOneId, NeighborOneApproval FROM Applications WHERE NeighborOneId = ? LIMIT 1");
        $stmt->execute([$neighborId]);
        $application = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$application) {
            $message = "<p>Geen aanvraag gevonden.</p>";
        } else {
            // Check if already confirmed
            if ($application['NeighborOneApproval'] !== null) {
                $message = "<p>Deze aanvraag is al bevestigd en wordt verwerkt. U ontvangt de sticker zo snel mogelijk.</p>";
            } else {
                // Update approval for this neighbor
                $stmt = $conn->prepare("UPDATE Applications SET NeighborOneApproval = 1 WHERE Id = ?");
                $stmt->execute([$application['Id']]);

                // Send approval mail (sticker will be sent)
                approval_mail($neighbor['Email'], $neighbor['Name']);

                $message = "<p>Uw bevestiging is geregistreerd. U ontvangt de sticker zo snel mogelijk.</p>";
            }
        }
    }
    unset($conn);
} else {
    $message = "<p>Ongeldige aanvraag.</p>";
}

ob_end_clean();
include 'includes/head.php';
include 'includes/nav.php';
?>

<main>
    <h1>Bevestiging</h1>
    <?php echo $message; ?>
</main>

<?php include 'includes/footer.php'; ?>