<?php
/**
 * Nusantara Cargo Express (NCE)
 * Modern Sidebar & Topbar Template (Role-Aware)
 */
declare(strict_types=1);

$currentPage = $_GET['page'] ?? 'dashboard';
$user = current_user();
$isAdmin = is_admin();
?>
<!-- Sidebar Navigation -->
<aside class="nce-sidebar" id="sidebarMenu">
    <div class="brand-wrapper">
        <div class="brand-logo-badge p-0 overflow-hidden" style="background: transparent; box-shadow: none;">
            <img src="assets/images/logo-nce.svg" alt="NCE Logo" width="44" height="44" class="rounded-3 shadow-sm">
        </div>
        <div>
            <h6 class="brand-title"><?= APP_SHORT ?></h6>
            <p class="brand-subtitle"><?= APP_NAME ?></p>
        </div>
    </div>

    <!-- User Profile Snippet in Sidebar with Role Badge -->
    <div class="sidebar-user-card d-flex align-items-center gap-3">
        <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold shadow-sm" style="width: 40px; height: 40px; background: <?= $isAdmin ? 'linear-gradient(135deg, #f59e0b, #d97706)' : 'linear-gradient(135deg, #3b82f6, #6366f1)' ?>; font-size: 0.95rem;">
            <?= strtoupper(substr($user['user_namalengkap'] ?? 'U', 0, 1)) ?>
        </div>
        <div class="overflow-hidden">
            <div class="text-white fw-semibold text-truncate small">
                <?= htmlspecialchars($user['user_namalengkap'] ?? 'Petugas') ?>
            </div>
            <div class="mt-1 d-flex align-items-center gap-1">
                <?php if ($isAdmin): ?>
                    <span class="badge" style="background: rgba(245, 158, 11, 0.2); color: #fbbf24; border: 1px solid rgba(245, 158, 11, 0.4); font-size: 0.68rem; padding: 2px 6px;">
                        <i class="bi bi-shield-fill-check me-1"></i>ADMINISTRATOR
                    </span>
                <?php else: ?>
                    <span class="badge" style="background: rgba(59, 130, 246, 0.2); color: #93c5fd; border: 1px solid rgba(59, 130, 246, 0.4); font-size: 0.68rem; padding: 2px 6px;">
                        <i class="bi bi-person-badge-fill me-1"></i>OPERATOR LOKET
                    </span>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <ul class="nav flex-column mb-auto">
        <li class="nav-section-title">Menu Utama</li>
        <li class="nav-item">
            <a href="index.php?page=dashboard" class="nav-link <?= $currentPage === 'dashboard' ? 'active' : '' ?>">
                <i class="bi bi-grid-fill"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="index.php?page=transaksi" class="nav-link <?= in_array($currentPage, ['transaksi', 'transaksi_cetak']) ? 'active' : '' ?>">
                <i class="bi bi-receipt-cutoff"></i>
                <span>Transaksi Kargo</span>
            </a>
        </li>

        <li class="nav-section-title">Data Master</li>
        <li class="nav-item">
            <a href="index.php?page=pelanggan" class="nav-link <?= $currentPage === 'pelanggan' ? 'active' : '' ?>">
                <i class="bi bi-people-fill"></i>
                <span>Data Pengirim</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="index.php?page=layanan" class="nav-link <?= $currentPage === 'layanan' ? 'active' : '' ?>">
                <i class="bi bi-tags-fill"></i>
                <span>Paket Layanan <?= !$isAdmin ? '<span class="badge bg-secondary-subtle text-secondary" style="font-size: 0.65rem;">Lihat</span>' : '' ?></span>
            </a>
        </li>

        <?php if ($isAdmin): ?>
            <!-- Menu Khusus Administrator -->
            <li class="nav-section-title">Hak Akses Admin</li>
            <li class="nav-item">
                <a href="index.php?page=users" class="nav-link <?= $currentPage === 'users' ? 'active' : '' ?>">
                    <i class="bi bi-shield-lock-fill text-warning"></i>
                    <span>Kelola Petugas</span>
                </a>
            </li>
        <?php endif; ?>
    </ul>

    <!-- Logout Button -->
    <div class="p-3 border-top" style="border-color: rgba(255,255,255,0.06) !important;">
        <a href="index.php?page=logout" class="nav-link text-danger d-flex align-items-center gap-2 p-2 rounded-3" onclick="return confirm('Apakah Anda yakin ingin keluar dari sistem?')">
            <i class="bi bi-box-arrow-right"></i>
            <span>Keluar (Logout)</span>
        </a>
    </div>
</aside>

<!-- Main Wrapper -->
<div class="nce-main">
    <!-- Navbar Header -->
    <header class="nce-topbar d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-3">
            <button class="btn btn-light d-lg-none border shadow-sm p-2" id="mobileMenuToggle" title="Buka Menu">
                <i class="bi bi-list fs-5"></i>
            </button>
            <div>
                <h5 class="mb-0 fw-bold text-dark tracking-tight"><?= htmlspecialchars($pageTitle ?? 'Dashboard') ?></h5>
                <small class="text-muted d-none d-sm-block"><?= htmlspecialchars($pageSubtitle ?? 'Sistem Pengiriman Kargo Terpadu') ?></small>
            </div>
        </div>

        <div class="d-flex align-items-center gap-2">
            <!-- Role indicator in Navbar -->
            <?php if ($isAdmin): ?>
                <span class="badge bg-warning-subtle text-dark border border-warning px-3 py-2 rounded-pill fw-semibold d-none d-md-inline-block">
                    <i class="bi bi-shield-lock-fill text-warning me-1"></i> Mode Admin
                </span>
            <?php else: ?>
                <span class="badge bg-primary-subtle text-primary border border-primary px-3 py-2 rounded-pill fw-semibold d-none d-md-inline-block">
                    <i class="bi bi-person-badge-fill me-1"></i> Mode Operator Loket
                </span>
            <?php endif; ?>

            <span class="badge bg-light text-secondary border px-3 py-2 rounded-pill font-monospace d-none d-sm-inline-block">
                <i class="bi bi-calendar3 me-1"></i> <?= date('d M Y') ?>
            </span>
            <a href="index.php?page=transaksi&act=tambah" class="btn btn-sm btn-primary rounded-pill px-3 py-2 fw-semibold shadow-sm d-flex align-items-center gap-1">
                <i class="bi bi-plus-lg"></i>
                <span class="d-none d-sm-inline">Input Resi Kargo</span>
            </a>
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="content-body">