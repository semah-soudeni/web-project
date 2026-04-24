<?php
$pageTitle = $pageTitle ?? 'INSAT Clubs';
$activePage = $activePage ?? '';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
    <?php if (!empty($extraCss)): ?>
        <?php foreach ($extraCss as $css): ?>
            <link rel="stylesheet" href=" <?= escapeText($css) ?>">
        <?php endforeach; ?>
    <?php endif; ?>
    <title><?= escapeText($pageTitle) ?></title>
</head>

<body>
    <nav class="navigation">
        <div class="nav-container">
            <div class="nav-menu">
                <a href="<?= BASE_URL ?>index.php" class="nav-link <?= $activePage === 'clubs' ? 'active' : '' ?>">Clubs</a>
                <a href="<?= BASE_URL ?>pages/events.php" class="nav-link <?= $activePage === 'events' ? 'active' : '' ?>">Events</a>
                <a href="<?= BASE_URL ?>pages/map.php" class="nav-link <?= $activePage === 'map' ? 'active' : '' ?>">Map</a>
                <?php if (isAdmin()): ?>
                    <a href="<?= BASE_URL ?>pages/admin.php" class="nav-link <?= $activePage === 'admin' ? 'active' : '' ?>">Admin Dashboard</a>
                <?php endif; ?>
            </div>
            <div class="nav-login">
                <?php if ($isLoggedIn): ?>
                    <form action="<?= BASE_URL ?>backend/logout.php" method="POST" style="display:flex; align-items:center; gap:20px;">
                        <span style="font-weight:600;">Hi, <?= escapeText($displayName) ?></span>
                        <button type="submit" class="signin-btn">Sign Out</button>
                    </form>
                <?php else: ?>
                    <a href="<?= BASE_URL ?>pages/signin.php" class="signin-btn">Sign In</a>
                    <a href="<?= BASE_URL ?>pages/signup.php" class="signup-btn">Sign Up</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>