<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../includes/env.php';
require_once '../includes/db.php';

// --- Simple password protection ---
session_start();

$admin_password = defined('ADMIN_PASSWORD') ? ADMIN_PASSWORD : null;

if (!isset($_SESSION['admin_logged_in'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['password'])) {
        if ($_POST['password'] === $admin_password) {
            $_SESSION['admin_logged_in'] = true;
            // No redirect, just continue to show the admin page
        } else {
            $error = "Wachtwoord onjuist.";
        }
    }
    if (!isset($_SESSION['admin_logged_in'])) {
    ?>
    <!DOCTYPE html>
    <html lang="nl">
    <head>
        <meta charset="UTF-8">
        <title>Admin Login</title>
        <style>
            body { font-family: sans-serif; background: #f7f7f7; }
            .login-box { max-width: 320px; margin: 100px auto; background: #fff; padding: 32px; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,0.07);}
            input[type="password"] { width: 100%; padding: 8px; margin-bottom: 12px; }
            button { width: 100%; padding: 8px; }
            .error { color: #c00; margin-bottom: 12px; }
        </style>
    </head>
    <body>
        <div class="login-box">
            <h2>Admin login</h2>
            <?php if (isset($error)) echo "<div class='error'>$error</div>"; ?>
            <form method="post">
                <input type="password" name="password" placeholder="Wachtwoord" required>
                <button type="submit">Inloggen</button>
            </form>
        </div>
    </body>
    </html>
    <?php
    exit();
    }
}

// --- Handle logout ---
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit();
}

// --- Handle toggle StickerSent ---
$conn = getDbConnection();
if (isset($_GET['toggle']) && is_numeric($_GET['toggle'])) {
    $id = intval($_GET['toggle']);
    // Toggle StickerSent
    $conn->query("UPDATE Applications SET StickerSent = NOT StickerSent WHERE Id = $id");
    header("Location: index.php");
    exit();
}

// --- Handle delete ---
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM Applications WHERE Id = ?");
    $stmt->execute([$id]);
    header("Location: index.php");
    exit();
}
// --- Handle anonmize ---
if (isset($_GET['anonimize']) && is_numeric($_GET['anonimize'])) {
    $id = intval($_GET['anonimize']);
    $stmt = $conn->prepare("SELECT Zipcode FROM Applications WHERE Id = ?");
    $stmt->execute([$id]);
    $zipcode = $stmt->fetchColumn();
    $newZipcode = substr($zipcode, 0, 4);
    $stmt = $conn->prepare("UPDATE Applications SET Street = '', HouseNumber = 0, Addition = '', Zipcode = ? WHERE Id = ?");
    $stmt->execute([$newZipcode, $id]);
    header("Location: index.php");
    exit();
}

// --- Fetch all applications, newest first ---
$result = $conn->query("SELECT Id, Street, HouseNumber, Addition, Zipcode, StickerSent FROM Applications ORDER BY Id DESC");

?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Admin - Aanvragen</title>
    <style>
        body { font-family: sans-serif; background: #f7f7f7; }
        .logout-btn {
            position: absolute;
            top: 24px;
            right: 32px;
            background: #eee;
            color: #333;
            border: none;
            border-radius: 6px;
            padding: 8px 18px;
            font-size: 1em;
            cursor: pointer;
            text-decoration: none;
        }
        table { border-collapse: collapse; width: 100%; background: #fff; margin: 32px auto; max-width: 900px; }
        th, td { padding: 10px 8px; border: 1px solid #eee; text-align: left; }
        th { background: #f0f0f0; }
        tr:nth-child(even) { background: #fafafa; }
        .toggle-btn, .delete-btn, .anon-btn { padding: 4px 10px; border-radius: 6px; border: none; cursor: pointer; }
        .sent { background: #c6f7d0; color: #1a7f37; }
        .notsent { background: #ffe0e0; color: #a00; }
        .delete-btn { background: #f8d7da; color: #a00; margin-left: 8px; }
        .anon-btn { background: rgba(217, 227, 255, 1); color: rgba(0, 51, 170, 1); margin-left: 8px; }
    </style>
    <script>
        function confirmDelete(id) {
            if (confirm('Weet je zeker dat je deze aanvraag wilt verwijderen?')) {
                window.location = '?delete=' + id;
            }
        }
        function anonimize(id) {
            if (confirm('Weet je zeker dat je deze aanvraag wilt anonimiseren?')) {
                window.location = '?anonimize=' + id;
            }
        }
    </script>
</head>
<body>
    <a href="?logout=1" class="logout-btn">Log uit</a>
    <h1 style="text-align:center;">Sticker aanvragen (admin)</h1>
    <table>
        <tr>
            <th>ID</th>
            <th>Adres</th>
            <th>Postcode</th>
            <th>Sticker verstuurd?</th>
            <th>Actie</th>
            <th>Anonimiseren</th>
            <th>Verwijderen</th>
        </tr>
        <?php while ($row = $result->fetch(PDO::FETCH_ASSOC)): ?>
        <tr>
            <td>
                <?php echo htmlspecialchars($row['Id']); ?>
            </td>
            <td>
                <?php echo htmlspecialchars($row['Street']); ?> 
                <?php echo htmlspecialchars($row['HouseNumber']); ?>
                <?php echo htmlspecialchars($row['Addition']); ?>
            </td>
            <td><?php echo htmlspecialchars($row['Zipcode']); ?></td>
            <td>
                <?php if ($row['StickerSent']): ?>
                    <span class="sent">Ja</span>
                <?php else: ?>
                    <span class="notsent">Nee</span>
                <?php endif; ?>
            </td>
            <td>
                <a href="?toggle=<?php echo $row['Id']; ?>" class="toggle-btn">
                    <?php echo $row['StickerSent'] ? 'Markeer als niet verstuurd' : 'Markeer als verstuurd'; ?>
                </a>
            </td>
            <td>
                <button type="button" class="anon-btn" onclick="anonimize(<?php echo $row['Id']; ?>)">Anonimiseren</button>
            </td>
            <td>
                <button type="button" class="delete-btn" onclick="confirmDelete(<?php echo $row['Id']; ?>)">Verwijder</button>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
<?php
// $conn->close();
?>