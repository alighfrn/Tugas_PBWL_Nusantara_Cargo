<?php
/**
 * Nusantara Cargo Express (NCE)
 * Dashboard Operasional Modern
 */

declare(strict_types=1);

require_login();

$pageTitle = 'Dashboard Operasional';
$pageSubtitle = 'Metrik performa kargo, logistik, dan riwayat transaksi terkini';

$pelangganModel = new Pelanggan();
$layananModel   = new Layanan();
$transaksiModel = new Transaksi();

// Ambil Statistik
$totalPelanggan = $pelangganModel->count();
$totalLayanan   = $layananModel->count();
$totalTransaksi = $transaksiModel->count();
$totalBerat     = $transaksiModel->getTotalBerat();
$totalOmset     = $transaksiModel->getTotalOmset();
$recentTransaksi = $transaksiModel->getRecent(6);

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
?>

<?php require_once __DIR__ . '/../includes/alert.php'; ?>

<!-- 4 Modern Stat Widgets -->
<div class="row g-3 mb-4">
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-widget h-100">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-semibold text-uppercase tracking-wider">Total Resi Kargo</span>
                    <h3 class="fw-bold text-dark mt-1 mb-0"><?= number_format($totalTransaksi) ?></h3>
                    <div class="mt-2">
                        <span class="badge-pill badge-soft-success">
                            <i class="bi bi-arrow-up-right"></i> Terbit Resmi
                        </span>
                    </div>
                </div>
                <div class="stat-icon-wrapper stat-icon-blue">
                    <i class="bi bi-receipt-cutoff"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-widget h-100">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-semibold text-uppercase tracking-wider">Berat Terkirim</span>
                    <h3 class="fw-bold text-dark mt-1 mb-0"><?= number_format($totalBerat) ?> <span class="fs-6 fw-normal text-muted">Kg</span></h3>
                    <div class="mt-2">
                        <span class="badge-pill badge-soft-warning">
                            <i class="bi bi-truck"></i> Akumulasi Kargo
                        </span>
                    </div>
                </div>
                <div class="stat-icon-wrapper stat-icon-amber">
                    <i class="bi bi-speedometer2"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-widget h-100">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-semibold text-uppercase tracking-wider">Total Omset</span>
                    <h3 class="fw-bold text-dark mt-1 mb-0" style="font-size: 1.35rem;"><?= format_rupiah($totalOmset) ?></h3>
                    <div class="mt-2">
                        <span class="badge-pill badge-soft-success">
                            <i class="bi bi-wallet2"></i> Pendapatan Bersih
                        </span>
                    </div>
                </div>
                <div class="stat-icon-wrapper stat-icon-green">
                    <i class="bi bi-cash-stack"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-widget h-100">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-semibold text-uppercase tracking-wider">Pengirim Terdaftar</span>
                    <h3 class="fw-bold text-dark mt-1 mb-0"><?= number_format($totalPelanggan) ?></h3>
                    <div class="mt-2">
                        <span class="badge-pill badge-soft-secondary">
                            <i class="bi bi-people"></i> Konsumen Aktif
                        </span>
                    </div>
                </div>
                <div class="stat-icon-wrapper stat-icon-purple">
                    <i class="bi bi-person-check"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modern Action Banner -->
<div class="card-modern p-4 mb-4 border-0 text-white" style="background: linear-gradient(135deg, #0b0f19 0%, #1e293b 100%);">
    <div class="row align-items-center">
        <div class="col-lg-8">
            <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill mb-2" style="background: rgba(37, 99, 235, 0.2); border: 1px solid rgba(37, 99, 235, 0.3);">
                <span class="d-inline-block rounded-circle bg-primary" style="width: 8px; height: 8px;"></span>
                <span class="small fw-semibold text-info">Sistem Ekspedisi Kargo Siap Beroperasi</span>
            </div>
            <h5 class="fw-bold mb-1 text-white">Input Resi & Surat Muatan Kargo Baru</h5>
            <p class="text-white-50 mb-lg-0 small">Hitung otomatis tarif per kilogram sesuai layanan kargo darat, express udara, atau kargo muatan besar, lalu cetak resi pengiriman resmi.</p>
        </div>
        <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
            <a href="index.php?page=transaksi&act=tambah" class="btn btn-warning px-4 py-2 fw-bold text-dark rounded-pill shadow-sm">
                <i class="bi bi-plus-circle-fill me-1"></i> Buat Resi Pengiriman
            </a>
        </div>
    </div>
</div>

<!-- Recent Transactions Table Card -->
<div class="card-modern">
    <div class="p-3 px-4 border-bottom d-flex justify-content-between align-items-center bg-white" style="border-top-left-radius: var(--nce-radius-lg); border-top-right-radius: var(--nce-radius-lg);">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-clock-history text-primary fs-5"></i>
            <div>
                <h6 class="mb-0 fw-bold text-dark">Pengiriman Kargo Terkini</h6>
                <small class="text-muted">6 resi pengiriman kargo terbaru yang masuk ke sistem</small>
            </div>
        </div>
        <a href="index.php?page=transaksi" class="btn btn-sm btn-outline-primary rounded-pill px-3">
            Lihat Semua Resi <i class="bi bi-arrow-right ms-1"></i>
        </a>
    </div>

    <div class="table-responsive">
        <table class="table-modern">
            <thead>
                <tr>
                    <th style="width: 125px;">No. Resi</th>
                    <th>Nama Pengirim</th>
                    <th style="min-width: 200px;">Paket Layanan</th>
                    <th style="width: 110px;">Tgl Drop-off</th>
                    <th style="width: 120px;">Estimasi Tiba</th>
                    <th style="width: 140px;">Status Kargo</th>
                    <th class="text-center" style="width: 80px;">Berat</th>
                    <th style="width: 130px;">Total Biaya</th>
                    <th class="text-center" style="width: 90px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($recentTransaksi)): ?>
                    <tr>
                        <td colspan="9" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-2 d-block mb-2 text-secondary"></i>
                            Belum ada riwayat transaksi pengiriman kargo.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($recentTransaksi as $trx): ?>
                        <?php $statusKargo = get_status_kargo($trx['trans_tgl_selesai']); ?>
                        <tr>
                            <td class="text-nowrap">
                                <span class="badge-pill badge-soft-secondary font-monospace fw-bold">
                                    NCE-<?= str_pad((string)$trx['trans_id'], 5, '0', STR_PAD_LEFT) ?>
                                </span>
                            </td>
                            <td>
                                <div class="fw-bold text-dark"><?= htmlspecialchars($trx['pel_nama']) ?></div>
                                <div class="small text-muted d-flex align-items-center gap-1 mt-1">
                                    <i class="bi bi-telephone text-primary"></i> 
                                    <?= htmlspecialchars(!empty($trx['pel_hp']) ? $trx['pel_hp'] : '-') ?>
                                </div>
                            </td>
                            <td>
                                <span class="badge-tag badge-soft-primary">
                                    <?= htmlspecialchars($trx['lay_jenis']) ?>
                                </span>
                            </td>
                            <td class="text-nowrap text-muted"><?= date('d/m/Y', strtotime($trx['trans_tgl_masuk'])) ?></td>
                            <td class="text-nowrap">
                                <span class="text-dark fw-medium"><?= date('d/m/Y', strtotime($trx['trans_tgl_selesai'])) ?></span>
                            </td>
                            <td class="text-nowrap">
                                <span class="badge-pill <?= $statusKargo['badge'] ?>">
                                    <i class="bi <?= $statusKargo['icon'] ?>"></i> <?= $statusKargo['label'] ?>
                                </span>
                            </td>
                            <td class="text-center text-nowrap">
                                <span class="badge-pill badge-soft-warning fw-bold"><?= $trx['trans_berat'] ?> Kg</span>
                            </td>
                            <td class="text-nowrap">
                                <span class="fw-bold text-success fs-6"><?= format_rupiah($trx['trans_total']) ?></span>
                            </td>
                            <td class="text-center text-nowrap">
                                <a href="index.php?page=transaksi_cetak&id=<?= $trx['trans_id'] ?>" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1 shadow-sm" title="Cetak Surat Resi" target="_blank">
                                    <i class="bi bi-printer me-1"></i> Cetak
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
require_once __DIR__ . '/../includes/footer.php';