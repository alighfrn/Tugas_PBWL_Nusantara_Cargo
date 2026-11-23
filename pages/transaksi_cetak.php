<?php
/**
 * Nusantara Cargo Express (NCE)
 * Cetak Surat Jalan & Resi Pengiriman Kargo
 */

declare(strict_types=1);

require_login();

$id = (int)($_GET['id'] ?? 0);
$transaksiModel = new Transaksi();
$trx = $transaksiModel->getById($id);

if (!$trx) {
    die('Data resi pengiriman kargo tidak ditemukan!');
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resi Pengiriman - NCE-<?= str_pad((string)$trx['trans_id'], 5, '0', STR_PAD_LEFT) ?></title>
    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Libre+Barcode+39+Text&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            background-color: #f1f5f9;
            font-family: 'Inter', sans-serif;
            color: #1e293b;
            padding: 2rem 0;
        }
        .receipt-container {
            max-width: 780px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            padding: 2.5rem;
            border: 1px solid #e2e8f0;
        }
        .barcode-box {
            font-family: 'Courier New', Courier, monospace;
            background: #f8fafc;
            border: 1px dashed #cbd5e1;
            padding: 0.5rem 1rem;
            display: inline-block;
            border-radius: 6px;
            letter-spacing: 2px;
        }
        .table th, .table td {
            padding: 0.75rem;
        }
        @media print {
            body {
                background: #ffffff !important;
                padding: 0 !important;
            }
            .receipt-container {
                box-shadow: none !important;
                border: 2px dashed #000000 !important;
                border-radius: 0 !important;
                width: 100% !important;
                max-width: 100% !important;
                padding: 1.5rem !important;
            }
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>

<div class="receipt-container">
    <!-- Action Bar (Hide on Print) -->
    <div class="no-print d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
        <a href="index.php?page=transaksi" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Transaksi
        </a>
        <button onclick="window.print()" class="btn btn-primary btn-sm rounded-pill px-4 fw-semibold shadow-sm">
            <i class="bi bi-printer me-1"></i> Cetak Dokumen Resi (Print)
        </button>
    </div>

    <!-- Header Perusahaan -->
    <div class="d-flex justify-content-between align-items-start border-bottom pb-4 mb-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <img src="assets/images/logo-nce.svg" alt="NCE" width="44" height="44" class="rounded-3">
                <h4 class="fw-bold mb-0 text-dark"><?= APP_NAME ?></h4>
            </div>
            <p class="text-muted small mb-0">Hub Logistik & Ekspedisi Pengiriman Kargo Seluruh Indonesia</p>
            <p class="text-muted small mb-0">Hotline: (061) 8899-7700 &bull; Web: www.nusantaracargo.id</p>
        </div>
        <div class="text-end">
            <div class="barcode-box mb-2">
                <div class="small text-muted" style="letter-spacing: normal;">SURAT MUATAN RESI</div>
                <strong class="fs-5 text-dark">*NCE-<?= str_pad((string)$trx['trans_id'], 5, '0', STR_PAD_LEFT) ?>*</strong>
            </div>
            <div class="small text-muted">Tanggal Cetak: <?= date('d/m/Y H:i') ?> WIB</div>
        </div>
    </div>

    <!-- Data Pengirim & Jadwal -->
    <div class="row g-4 mb-4">
        <div class="col-sm-6">
            <div class="p-3 bg-light rounded-3 h-100 border">
                <span class="text-uppercase small fw-bold text-muted d-block mb-2">
                    <i class="bi bi-person-fill text-primary me-1"></i> Data Pengirim Kargo
                </span>
                <h6 class="fw-bold text-dark mb-1"><?= htmlspecialchars($trx['pel_nama']) ?></h6>
                <div class="small text-muted mb-1"><i class="bi bi-telephone me-1"></i> <?= htmlspecialchars($trx['pel_hp'] ?: '-') ?></div>
                <div class="small text-secondary"><i class="bi bi-geo-alt me-1"></i> <?= nl2br(htmlspecialchars($trx['pel_alamat'] ?: 'Alamat tidak dicantumkan')) ?></div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="p-3 bg-light rounded-3 h-100 border">
                <span class="text-uppercase small fw-bold text-muted d-block mb-2">
                    <i class="bi bi-calendar-check text-primary me-1"></i> Informasi Jadwal Pengiriman
                </span>
                <div class="d-flex justify-content-between mb-2">
                    <span class="small text-muted">Tanggal Drop-off:</span>
                    <strong class="small text-dark"><?= format_tanggal($trx['trans_tgl_masuk']) ?></strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="small text-muted">Estimasi Tiba:</span>
                    <strong class="small text-primary"><?= format_tanggal($trx['trans_tgl_selesai']) ?></strong>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="small text-muted">Status:</span>
                    <?php $statusKargo = get_status_kargo($trx['trans_tgl_selesai']); ?>
                    <?php if ($statusKargo['is_delivered']): ?>
                        <span class="badge bg-success"><i class="bi bi-check-circle-fill me-1"></i> Terkirim (Sampai)</span>
                    <?php else: ?>
                        <span class="badge bg-primary"><i class="bi bi-truck me-1"></i> Dalam Pengiriman</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Rincian Kargo -->
    <div class="table-responsive mb-4">
        <table class="table table-bordered align-middle">
            <thead class="table-light">
                <tr>
                    <th>Paket Layanan Kargo</th>
                    <th class="text-center" style="width: 150px;">Tarif per Kg</th>
                    <th class="text-center" style="width: 150px;">Timbangan (Berat)</th>
                    <th class="text-end" style="width: 180px;">Total Ongkos Kirim</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <div class="fw-bold text-dark"><?= htmlspecialchars($trx['lay_jenis']) ?></div>
                        <small class="text-muted">Kode Layanan: LAY-<?= str_pad((string)$trx['trans_id_layanan'], 3, '0', STR_PAD_LEFT) ?></small>
                    </td>
                    <td class="text-center"><?= format_rupiah($trx['lay_harga']) ?></td>
                    <td class="text-center fw-bold"><?= $trx['trans_berat'] ?> Kg</td>
                    <td class="text-end fw-bold text-dark fs-6"><?= format_rupiah($trx['trans_total']) ?></td>
                </tr>
            </tbody>
            <tfoot>
                <tr class="table-light">
                    <th colspan="3" class="text-end fw-bold">TOTAL PEMBAYARAN:</th>
                    <th class="text-end fw-bold text-success fs-5"><?= format_rupiah($trx['trans_total']) ?></th>
                </tr>
            </tfoot>
        </table>
    </div>

    <!-- Catatan & Tanda Tangan -->
    <div class="row pt-2">
        <div class="col-8">
            <div class="small text-muted">
                <strong>Ketentuan Pengiriman:</strong>
                <ol class="ps-3 mb-0" style="font-size: 0.75rem;">
                    <li>Barang yang dikirim telah ditimbang dan disetujui oleh pengirim.</li>
                    <li>Resi ini merupakan bukti sah pengiriman kargo Nusantara Cargo Express.</li>
                    <li>Simpan resi ini untuk proses pelacakan dan pengambilan paket kargo.</li>
                </ol>
            </div>
        </div>
        <div class="col-4 text-center">
            <small class="text-muted d-block mb-4">Petugas / Operator NCE,</small>
            <div style="height: 45px;"></div>
            <strong class="d-block border-top pt-1 small text-dark">( <?= htmlspecialchars(current_user()['user_namalengkap'] ?? 'Petugas Loket') ?> )</strong>
        </div>
    </div>
</div>

</body>
</html>