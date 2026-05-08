<?php
require_once __DIR__ . '/../includes/init.php';
requireLogin();

$pageTitle  = 'Join a Club';
$activePage = 'clubs';

$sclub = $_GET['club'] ?? '';

$success = false;
$error   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $conn    = ConnexionBD::getInstance();
        $userId  = (int)$_SESSION['id'];
        $clubId  = (int)$_POST['club'];
        $role    = 'member'; // always member when self-registering

        // Check if already a member
        $check = $conn->prepare("
            SELECT 1 FROM memberships 
            WHERE user_id = ? AND club_id = ?
        ");
        $check->execute([$userId, $clubId]);

        if ($check->fetch()) {
            $error = 'You are already a member of this club.';
        } else {
            $stmt = $conn->prepare("
                INSERT INTO memberships (user_id, club_id, role)
                VALUES (:user_id, :club_id, :role)
            ");
            $stmt->execute([
                ':user_id' => $userId,
                ':club_id' => $clubId,
                ':role'    => $role,
            ]);
            $success = true;
        }
    } catch (Throwable $e) {
        $error = 'Something went wrong. Please try again.';
    }
}

require_once ROOT_PATH . '/views/header.php';
?>

<div class="reg-page">
    <div class="reg-card">
        <h1>Join a Club</h1>
        <p class="reg-subtitle">
            Joining as <strong><?= escapeText($displayName) ?></strong>
        </p>

        <?php if ($success): ?>
            <div class="alert-success">
                You have successfully joined the club!
            </div>
            <div class="reg-actions" style="margin-top:1rem;">
                <a class="reg-btn-ghost" href="<?= BASE_URL ?>index.php">Back to Clubs</a>
            </div>

        <?php else: ?>

            <?php if ($error): ?>
                <div class="alert-error"><?= escapeText($error) ?></div>
            <?php endif; ?>

            <form class="reg-form" action="" method="POST">

                <div class="reg-field">
                    <label for="club">Select Club</label>
                    <select id="club" name="club" required>
                        <option value="" disabled selected>Select a club…</option>
                        <option value="1" <?= $sclub === 'aero'  ? 'selected' : '' ?>>Aerobotix</option>
                        <option value="2" <?= $sclub === 'secu'  ? 'selected' : '' ?>>Securinets</option>
                        <option value="3" <?= $sclub === 'ieee'  ? 'selected' : '' ?>>IEEE</option>
                        <option value="4" <?= $sclub === 'acm'   ? 'selected' : '' ?>>ACM</option>
                        <option value="5" <?= $sclub === 'android' ? 'selected' : '' ?>>Android Club</option>
                        <option value="6" <?= $sclub === 'cim'   ? 'selected' : '' ?>>CIM</option>
                        <option value="7" <?= $sclub === 'theatro' ? 'selected' : '' ?>>Theatro</option>
                        <option value="8" <?= $sclub === 'cineradio' ? 'selected' : '' ?>>Club Ciné-Radio</option>
                        <option value="9" <?= $sclub === 'press' ? 'selected' : '' ?>>Insat Press</option>
                        <option value="10" <?= $sclub === 'lions' ? 'selected' : '' ?>>Lions Club</option>
                        <option value="11" <?= $sclub === 'enactus' ? 'selected' : '' ?>>Club Enactus</option>
                        <option value="12" <?= $sclub === 'jei'   ? 'selected' : '' ?>>Club JEI</option>
                        <option value="13" <?= $sclub === 'jci'   ? 'selected' : '' ?>>Club JCI</option>
                        <option value="14" <?= $sclub === 'chem'  ? 'selected' : '' ?>>Chem Club</option>
                        <option value="15" <?= $sclub === 'astro' ? 'selected' : '' ?>>Astro Club</option>
                        <option value="16" <?= $sclub === '3zero' ? 'selected' : '' ?>>3Zero Club</option>
                    </select>
                </div>

                <div class="reg-actions">
                    <button type="submit" class="reg-btn-primary">✓ Join Club</button>
                    <a class="reg-btn-ghost" href="<?= BASE_URL ?>index.php">← Back to Clubs</a>
                </div>

            </form>
        <?php endif; ?>

    </div>
</div>

<?php require_once ROOT_PATH . '/views/footer.php'; ?>