<?php
/**
 * Nusantara Cargo Express (NCE)
 * Header Template Modern
 */
declare(strict_types=1);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? APP_NAME) ?> &bull; <?= APP_SHORT ?></title>
    
    <!-- Favicon NCE -->
    <link rel="icon" type="image/svg+xml" href="assets/images/logo-nce.svg">
    
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS & Bootstrap Icons CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Custom Style Modern NCE -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="nce-layout">
<div class="mobile-backdrop" id="mobileBackdrop"></div>