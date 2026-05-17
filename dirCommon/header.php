<?php
$pageTitle = $pageTitle ?? 'BiteBuddy';
$activePage = $activePage ?? '';
$basePath = $basePath ?? '';
$extraCss = $extraCss ?? [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    <link rel="stylesheet" href="<?php echo $basePath; ?>dirCommon/assets/css/common.css">
    <?php foreach ($extraCss as $cssFile): ?>
        <link rel="stylesheet" href="<?php echo htmlspecialchars($cssFile); ?>">
    <?php endforeach; ?>
</head>
<body>
    <header class="site-header">
        <div class="page-wrap header-inner">
            <a class="brand" href="<?php echo $basePath; ?>index.php">
                <span class="brand-icon">B</span>
                <span>BiteBuddy</span>
            </a>

            <nav class="main-nav" aria-label="Main navigation">
                <a class="<?php echo $activePage === 'browse' ? 'active' : ''; ?>" href="<?php echo $basePath; ?>Customer/view/browseResturants.php">Browse</a>
                <a class="<?php echo $activePage === 'offers' ? 'active' : ''; ?>" href="#">Offers</a>
                <a class="<?php echo $activePage === 'support' ? 'active' : ''; ?>" href="#">Support</a>
            </nav>

            <div class="header-actions">
                <a class="cart-link" href="#">Cart</a>
                <a class="login-link" href="<?php echo $basePath; ?>dirCommon/login.html">Login</a>
            </div>
        </div>
    </header>
