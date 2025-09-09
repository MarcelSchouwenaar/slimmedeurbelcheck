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

    // Verify token for this neighbor
    $stmt = $conn->prepare("SELECT Id, Name, Email, ConfirmationToken FROM Neighbors WHERE Id = ? LIMIT 1");
    $stmt->execute([$neighborId]);
    $neighbor = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$neighbor || $neighbor['ConfirmationToken'] !== $token) {
        $message = "<p>Ongeldige of verlopen bevestigingslink.</p>";
    } else {
        // Find the application(s) for this neighbor
        $stmt = $conn->prepare("SELECT Id, NeighborOneId, NeighborTwoId, NeighborOneApproval, NeighborTwoApproval FROM Applications WHERE NeighborOneId = ? OR NeighborTwoId = ? LIMIT 1");
        $stmt->execute([$neighborId, $neighborId]);
        $application = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$application) {
            $message = "<p>Geen aanvraag gevonden voor deze buur.</p>";
        } else {
            // Check if both approvals have already been set (not NULL)
            if (
                $application['NeighborOneApproval'] !== null &&
                $application['NeighborTwoApproval'] !== null
            ) {
                // Both neighbors have responded (either 0 or 1)
                if ($application['NeighborOneApproval'] == 1 && $application['NeighborTwoApproval'] == 1) {
                    $message = "<p>Beide buren hebben bevestigd. De aanvraag is goedgekeurd!</p>";
                    // Send Mail 3: Approved
                    // Fetch both neighbors' info
                    $stmt = $conn->prepare("SELECT Name, Email FROM Neighbors WHERE Id IN (?, ?)");
                    $stmt->execute([$application['NeighborOneId'], $application['NeighborTwoId']]);
                    $neighbors = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($neighbors as $n) {
                        approval_mail($n['Email'], $n['Name']);
                    }
                } else {
                    $message = "<p>De aanvraag is niet goedgekeurd omdat één van de buren bezwaar heeft gemaakt.</p>";
                    // Send Mail 2: Objection
                    $stmt = $conn->prepare("SELECT Name, Email FROM Neighbors WHERE Id IN (?, ?)");
                    $stmt->execute([$application['NeighborOneId'], $application['NeighborTwoId']]);
                    $neighbors = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    // Optionally fetch feedback for rejection_mail
                    $stmt = $conn->prepare("SELECT NeighborOneFeedback, NeighborTwoFeedback FROM Applications WHERE Id = ?");
                    $stmt->execute([$application['Id']]);
                    $feedbacks = $stmt->fetch(PDO::FETCH_ASSOC);
                    foreach ($neighbors as $n) {
                        // Show only public feedback
                        $feedback = '';
                        if ($n['Name'] == $neighbor['Name']) {
                            // Skip sending rejection to the neighbor who just confirmed
                            continue;
                        }
                        if ($application['NeighborOneId'] == $neighborId) {
                            $feedback = $feedbacks['NeighborOneFeedback'];
                        } else {
                            $feedback = $feedbacks['NeighborTwoFeedback'];
                        }
                        rejection_mail($n['Email'], $n['Name'], $feedback);
                    }
                }
            } else {
                // Update approval for this neighbor
                if ($application['NeighborOneId'] == $neighborId) {
                    $stmt = $conn->prepare("UPDATE Applications SET NeighborOneApproval = 1 WHERE Id = ?");
                } else {
                    $stmt = $conn->prepare("UPDATE Applications SET NeighborTwoApproval = 1 WHERE Id = ?");
                }
                $stmt->execute([$application['Id']]);

                // Check if both neighbors have approved or objected
                $stmt = $conn->prepare("SELECT NeighborOneApproval, NeighborTwoApproval FROM Applications WHERE Id = ?");
                $stmt->execute([$application['Id']]);
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($row['NeighborOneApproval'] !== null && $row['NeighborTwoApproval'] !== null) {
                    if ($row['NeighborOneApproval'] == 1 && $row['NeighborTwoApproval'] == 1) {
                        $message = "<p>Beide buren hebben bevestigd. De aanvraag is goedgekeurd!</p>";
                        // Send Mail 3: Approved
                        $stmt = $conn->prepare("SELECT Name, Email FROM Neighbors WHERE Id IN (?, ?)");
                        $stmt->execute([$application['NeighborOneId'], $application['NeighborTwoId']]);
                        $neighbors = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($neighbors as $n) {
                            approval_mail($n['Email'], $n['Name']);
                        }
                    } else {
                        $message = "<p>De aanvraag is niet goedgekeurd omdat één van de buren bezwaar heeft gemaakt.</p>";
                        // Send Mail 2: Objection
                        $stmt = $conn->prepare("SELECT Name, Email FROM Neighbors WHERE Id IN (?, ?)");
                        $stmt->execute([$application['NeighborOneId'], $application['NeighborTwoId']]);
                        $neighbors = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        $stmt = $conn->prepare("SELECT NeighborOneFeedback, NeighborTwoFeedback FROM Applications WHERE Id = ?");
                        $stmt->execute([$application['Id']]);
                        $feedbacks = $stmt->fetch(PDO::FETCH_ASSOC);
                        foreach ($neighbors as $n) {
                            if ($n['Name'] == $neighbor['Name']) {
                                continue;
                            }
                            if ($application['NeighborOneId'] == $neighborId) {
                                $feedback = $feedbacks['NeighborOneFeedback'];
                            } else {
                                $feedback = $feedbacks['NeighborTwoFeedback'];
                            }
                            rejection_mail($n['Email'], $n['Name'], $feedback);
                        }
                    }
                } else {
                    $message = "<p>Uw bevestiging is geregistreerd. We wachten nog op de andere buur.</p>";
                }
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