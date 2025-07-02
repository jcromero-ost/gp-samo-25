<?php
    $currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $isLoginPage = $currentPath === BASE_URL . '/login';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <?php include __DIR__ . '/components/head.php'; ?>
</head>
<body>
    <?php include __DIR__ . '/../session.php'; ?>
    <?php include __DIR__ . '/components/nav.php'; ?>
    <?php
        $currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $isLoginPage = $currentPath === BASE_URL . '/login';

        if (!$isLoginPage):
            include __DIR__ . '/components/alerts.php';
        endif;
    ?>
    <?php include $view; ?>
    <?php if (!$isLoginPage): ?>
        <?php include __DIR__ . '/components/footer.php'; ?>
    <?php endif; ?>
</body>
</html>
