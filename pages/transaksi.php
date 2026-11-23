<?php
/**
 * Nusantara Cargo Express (NCE)
 * Modul: Transaksi Pengiriman Kargo (tb_transaksi)
 */

declare(strict_types=1);

require_login();

$transaksiModel = new Transaksi();
$pelangganModel = new Pelanggan();
$layananModel   = new Layanan();

$act = $_GET['act'] ?? 'index';
$pageTitle = 'Transaksi Pengiriman Kargo';
$pageSubtitle = 'Input surat muatan, timbangan kargo, dan terbitkan nomor resi';

$isAdmin = is_admin();

// -------------------------------------------------------------------
// PROSES SIMPAN TRANSAKSI BARU (ADMIN & OPERATOR BISA)
// -------------------------------------------------------------------
if ($act === 'simpan' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $pelangganId = (int)($_POST['trans_id_pelanggan'] ?? 0);
    $layananId   = (int)($_POST['trans_id_layanan'] ?? 0);
    $tglMasuk    = trim($_POST['trans_tgl_masuk'] ?? '');
    $tglSelesai  = trim($_POST['trans_tgl_selesai'] ?? '');
    $berat       = (int)($_POST['trans_berat'] ?? 0);

    if ($pelangganId <= 0 || $layananId <= 0 || empty($tglMasuk) || empty($tglSelesai) || $berat <= 0) {
        set_flash('danger', 'Semua kolom formulir pengiriman kargo wajib diisi dengan benar!');
        header('Location: index.php?page=transaksi&act=tambah');
        exit;
    }

    $ok = $transaksiModel->create([
        'trans_id_pelanggan' => $pelangganId,
        'trans_id_layanan'   => $layananId,
        'trans_tgl_masuk'    => $tglMasuk,
        'trans_tgl_selesai'  => $tglSelesai,
        'trans_berat'        => $berat,
    ]);

    if ($ok) {
        set_flash('success', 'Transaksi pengiriman kargo berhasil diterbitkan!');
    } else {
        set_flash('danger', 'Gagal memproses transaksi pengiriman.');
    }
    header('Location: index.php?page=transaksi');
    exit;
}

// -------------------------------------------------------------------
// PROSES UPDATE TRANSAKSI
// -------------------------------------------------------------------
if ($act === 'update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $id          = (int)($_POST['trans_id'] ?? 0);
    $pelangganId = (int)($_POST['trans_id_pelanggan'] ?? 0);
    $layananId   = (int)($_POST['trans_id_layanan'] ?? 0);
    $tglMasuk    = trim($_POST['trans_tgl_masuk'] ?? '');
    $tglSelesai  = trim($_POST['trans_tgl_selesai'] ?? '');
    $berat       = (int)($_POST['trans_berat'] ?? 0);

    if ($id <= 0 || $pelangganId <= 0 || $layananId <= 0 || empty($tglMasuk) || empty($tglSelesai) || $berat <= 0) {
        set_flash('danger', 'Data perubahan transaksi tidak valid!');
        header('Location: index.php?page=transaksi&act=edit&id=' . $id);
        exit;
    }

    $ok = $transaksiModel->update($id, [
        'trans_id_pelanggan' => $pelangganId,
        'trans_id_layanan'   => $layananId,
        'trans_tgl_masuk'    => $tglMasuk,
        'trans_tgl_selesai'  => $tglSelesai,
        'trans_berat'        => $berat,
    ]);

    if ($ok) {
        set_flash('success', 'Data transaksi resi pengiriman berhasil diperbarui!');
    } else {
        set_flash('danger', 'Gagal memperbarui transaksi pengiriman.');
    }
    header('Location: index.php?page=transaksi');
    exit;
}

// -------------------------------------------------------------------
// PROSES TANDAI SUDAH SAMPAI / TERKIRIM (ADMIN & OPERATOR BISA)
// -------------------------------------------------------------------
if ($act === 'selesai') {
    $id = (int)($_GET['id'] ?? 0);
    $ok = $transaksiModel->markAsDelivered($id);
    if ($ok) {
        set_flash('success', 'Status kargo berhasil diubah menjadi Terkirim (Sampai di Tujuan)!');
    } else {
        set_flash('danger', 'Gagal memperbarui status pengiriman kargo.');
    }
    header('Location: index.php?page=transaksi');
    exit;
}

// -------------------------------------------------------------------
// PROSES HAPUS TRANSAKSI (HANYA ADMINISTRATOR)
// -------------------------------------------------------------------
if ($act === 'hapus') {
    if (!$isAdmin) {
        set_flash('danger', 'Akses ditolak! Pembatalan/penghapusan resi transaksi hanya boleh dilakukan oleh Administrator.');
        header('Location: index.php?page=transaksi');
        exit;
    }

    $id = (int)($_GET['id'] ?? 0);
    $ok = $transaksiModel->delete($id);
    if ($ok) {
        set_flash('success', 'Data transaksi resi berhasil dihapus.');
    } else {
        set_flash('danger', 'Gagal menghapus data transaksi.');
    }
    header('Location: index.php?page=transaksi');
    exit;
}

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
?>

<?php require_once __DIR__ . '/../includes/alert.php'; ?>

<?php if ($act === 'tambah'): ?>
    <!-- FORM TAMBAH RESI PENGIRIMAN -->
    <?php
    $dataPelanggan = $pelangganModel->getAll();
    $dataLayanan   = $layananModel->getAll();
    ?>
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card-modern">
                <div class="p-3 px-4 bg-white border-bottom d-flex justify-content-between align-items-center" style="border-top-left-radius: var(--nce-radius-lg); border-top-right-radius: var(--nce-radius-lg);">
                    <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-box-seam me-2 text-primary"></i>Buat Transaksi Pengiriman Kargo</h6>
                    <a href="index.php?page=transaksi" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                        <i class="bi bi-arrow-left me-1"></i> Kembali
                    </a>
                </div>
                <div class="p-4">
                    <?php if (empty($dataPelanggan) || empty($dataLayanan)): ?>
                        <div class="alert alert-warning rounded-3">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            Harap pastikan Anda telah mengisi data <strong>Pengirim (Pelanggan)</strong> dan <strong>Paket Layanan</strong> terlebih dahulu sebelum membuat transaksi pengiriman.
                        </div>
                    <?php else: ?>
                        <form method="POST" action="index.php?page=transaksi&act=simpan">
                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Pilih Pengirim / Pelanggan <span class="text-danger">*</span></label>
                                    <select name="trans_id_pelanggan" class="form-select" required>
                                        <option value="">-- Pilih Pengirim Kargo --</option>
                                        <?php foreach ($dataPelanggan as $p): ?>
                                            <option value="<?= $p['pel_id'] ?>">
                                                <?= htmlspecialchars($p['pel_nama']) ?> (<?= htmlspecialchars($p['pel_hp'] ?: 'Tanpa HP') ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div class="mt-1">
                                        <a href="index.php?page=pelanggan&act=tambah" class="small text-decoration-none fw-medium">+ Tambah Pengirim Baru</a>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Pilih Paket Layanan Kargo <span class="text-danger">*</span></label>
                                    <select name="trans_id_layanan" id="trans_id_layanan" class="form-select" required>
                                        <option value="" data-harga="0">-- Pilih Jenis Layanan --</option>
                                        <?php foreach ($dataLayanan as $l): ?>
                                            <option value="<?= $l['lay_id'] ?>" data-harga="<?= $l['lay_harga'] ?>">
                                                <?= htmlspecialchars($l['lay_jenis']) ?> &bull; <?= format_rupiah($l['lay_harga']) ?>/Kg
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Tanggal Masuk / Drop-off <span class="text-danger">*</span></label>
                                    <input type="date" name="trans_tgl_masuk" class="form-control" value="<?= date('Y-m-d') ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Estimasi Tiba di Tujuan <span class="text-danger">*</span></label>
                                    <input type="date" name="trans_tgl_selesai" class="form-control" value="<?= date('Y-m-d', strtotime('+3 days')) ?>" required>
                                </div>
                            </div>

                            <div class="row g-3 mb-4 p-3 bg-light rounded-3 border">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-dark">Timbangan Berat Kargo (Kg) <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" name="trans_berat" id="trans_berat" class="form-control form-control-lg fw-bold" placeholder="0" min="1" step="1" value="1" required>
                                        <span class="input-group-text bg-white fw-bold">Kg</span>
                                    </div>
                                    <small class="text-muted">Total biaya otomatis terkalkulasi secara realtime.</small>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-dark">Total Biaya Pengiriman (Otomatis)</label>
                                    <input type="text" id="trans_total_display" class="form-control form-control-lg fw-bold text-success bg-white" value="Rp 0" readonly>
                                    <input type="hidden" name="trans_total" id="trans_total" value="0">
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <a href="index.php?page=transaksi" class="btn btn-light px-4 rounded-pill">Batal</a>
                                <button type="submit" class="btn btn-primary px-4 fw-semibold rounded-pill shadow-sm">
                                    <i class="bi bi-printer me-1"></i> Terbitkan Resi Pengiriman
                                </button>
                            </div>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

<?php elseif ($act === 'edit'): ?>
    <!-- FORM EDIT RESI PENGIRIMAN -->
    <?php
    $id = (int)($_GET['id'] ?? 0);
    $trx = $transaksiModel->getById($id);
    if (!$trx) {
        set_flash('danger', 'Data transaksi pengiriman tidak ditemukan!');
        header('Location: index.php?page=transaksi');
        exit;
    }
    $dataPelanggan = $pelangganModel->getAll();
    $dataLayanan   = $layananModel->getAll();
    ?>
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card-modern">
                <div class="p-3 px-4 bg-white border-bottom d-flex justify-content-between align-items-center" style="border-top-left-radius: var(--nce-radius-lg); border-top-right-radius: var(--nce-radius-lg);">
                    <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-pencil-square me-2 text-warning"></i>Edit Transaksi Resi #NCE-<?= str_pad((string)$trx['trans_id'], 5, '0', STR_PAD_LEFT) ?></h6>
                    <a href="index.php?page=transaksi" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                        <i class="bi bi-arrow-left me-1"></i> Kembali
                    </a>
                </div>
                <div class="p-4">
                    <form method="POST" action="index.php?page=transaksi&act=update">
                        <input type="hidden" name="trans_id" value="<?= $trx['trans_id'] ?>">
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Pilih Pengirim / Pelanggan <span class="text-danger">*</span></label>
                                <select name="trans_id_pelanggan" class="form-select" required>
                                    <?php foreach ($dataPelanggan as $p): ?>
                                        <option value="<?= $p['pel_id'] ?>" <?= $p['pel_id'] == $trx['trans_id_pelanggan'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($p['pel_nama']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Pilih Paket Layanan Kargo <span class="text-danger">*</span></label>
                                <select name="trans_id_layanan" id="trans_id_layanan" class="form-select" required>
                                    <?php foreach ($dataLayanan as $l): ?>
                                        <option value="<?= $l['lay_id'] ?>" data-harga="<?= $l['lay_harga'] ?>" <?= $l['lay_id'] == $trx['trans_id_layanan'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($l['lay_jenis']) ?> &bull; <?= format_rupiah($l['lay_harga']) ?>/Kg
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Tanggal Masuk / Drop-off <span class="text-danger">*</span></label>
                                <input type="date" name="trans_tgl_masuk" class="form-control" value="<?= $trx['trans_tgl_masuk'] ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Estimasi Tiba di Tujuan <span class="text-danger">*</span></label>
                                <input type="date" name="trans_tgl_selesai" class="form-control" value="<?= $trx['trans_tgl_selesai'] ?>" required>
                            </div>
                        </div>

                        <div class="row g-3 mb-4 p-3 bg-light rounded-3 border">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-dark">Timbangan Berat Kargo (Kg) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" name="trans_berat" id="trans_berat" class="form-control form-control-lg fw-bold" min="1" step="1" value="<?= $trx['trans_berat'] ?>" required>
                                    <span class="input-group-text bg-white fw-bold">Kg</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-dark">Total Biaya Pengiriman (Otomatis)</label>
                                <input type="text" id="trans_total_display" class="form-control form-control-lg fw-bold text-success bg-white" value="<?= format_rupiah($trx['trans_total']) ?>" readonly>
                                <input type="hidden" name="trans_total" id="trans_total" value="<?= $trx['trans_total'] ?>">
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="index.php?page=transaksi" class="btn btn-light px-4 rounded-pill">Batal</a>
                            <button type="submit" class="btn btn-warning px-4 fw-semibold text-dark rounded-pill">
                                <i class="bi bi-check-lg me-1"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<?php else: ?>
    <!-- TABEL SEMUA TRANSAKSI RESI -->
    <?php $listTransaksi = $transaksiModel->getAll(); ?>
    <div class="card-modern">
        <div class="p-3 px-4 bg-white border-bottom d-flex justify-content-between align-items-center" style="border-top-left-radius: var(--nce-radius-lg); border-top-right-radius: var(--nce-radius-lg);">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-receipt-cutoff text-primary fs-5"></i>
                <span class="fw-bold">Riwayat Pengiriman & Resi Kargo</span>
                <span class="badge-pill badge-soft-secondary"><?= count($listTransaksi) ?> Transaksi</span>
            </div>
            <a href="index.php?page=transaksi&act=tambah" class="btn btn-sm btn-primary rounded-pill px-3 shadow-sm">
                <i class="bi bi-plus-lg me-1"></i> Input Resi Baru
            </a>
        </div>
        <div class="table-responsive">
            <table class="table-modern">
                <thead>
                    <tr>
                        <th style="width: 125px;">No. Resi</th>
                        <th>Pengirim Kargo</th>
                        <th style="min-width: 200px;">Paket Layanan</th>
                        <th style="width: 110px;">Tgl Kirim</th>
                        <th style="width: 120px;">Estimasi Tiba</th>
                        <th style="width: 150px;">Status Kargo</th>
                        <th class="text-center" style="width: 80px;">Berat</th>
                        <th style="width: 130px;">Total Ongkir</th>
                        <th class="text-center" style="width: 160px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($listTransaksi)): ?>
                        <tr>
                            <td colspan="9" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-2 d-block mb-2 text-secondary"></i>
                                Belum ada transaksi pengiriman kargo.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($listTransaksi as $t): ?>
                            <?php $statusKargo = get_status_kargo($t['trans_tgl_selesai']); ?>
                            <tr>
                                <td class="text-nowrap">
                                    <span class="badge-pill badge-soft-secondary font-monospace fw-bold">
                                        NCE-<?= str_pad((string)$t['trans_id'], 5, '0', STR_PAD_LEFT) ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark"><?= htmlspecialchars($t['pel_nama']) ?></div>
                                    <div class="small text-muted d-flex align-items-center gap-1 mt-1">
                                        <i class="bi bi-telephone text-primary"></i> 
                                        <?= htmlspecialchars(!empty($t['pel_hp']) ? $t['pel_hp'] : '-') ?>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge-tag badge-soft-primary">
                                        <?= htmlspecialchars($t['lay_jenis']) ?>
                                    </span>
                                    <div class="small text-muted mt-1"><?= format_rupiah($t['lay_harga']) ?> / Kg</div>
                                </td>
                                <td class="text-nowrap text-muted"><?= date('d/m/Y', strtotime($t['trans_tgl_masuk'])) ?></td>
                                <td class="text-nowrap">
                                    <span class="text-dark fw-medium"><?= date('d/m/Y', strtotime($t['trans_tgl_selesai'])) ?></span>
                                </td>
                                <td class="text-nowrap">
                                    <span class="badge-pill <?= $statusKargo['badge'] ?>">
                                        <i class="bi <?= $statusKargo['icon'] ?>"></i> <?= $statusKargo['label'] ?>
                                    </span>
                                </td>
                                <td class="text-center text-nowrap">
                                    <span class="badge-pill badge-soft-warning fw-bold"><?= $t['trans_berat'] ?> Kg</span>
                                </td>
                                <td class="text-nowrap">
                                    <span class="fw-bold text-success fs-6"><?= format_rupiah($t['trans_total']) ?></span>
                                </td>
                                <td class="text-center text-nowrap">
                                    <div class="btn-group btn-group-sm">
                                        <?php if (!$statusKargo['is_delivered']): ?>
                                            <a href="index.php?page=transaksi&act=selesai&id=<?= $t['trans_id'] ?>" class="btn btn-outline-success" title="Tandai Barang Sudah Sampai / Terkirim" onclick="return confirm('Apakah kargo dengan nomor resi ini sudah sampai di tujuan?')">
                                                <i class="bi bi-check2-circle me-1"></i> Sampai
                                            </a>
                                        <?php endif; ?>
                                        <a href="index.php?page=transaksi_cetak&id=<?= $t['trans_id'] ?>" class="btn btn-outline-primary" title="Cetak Surat Resi Kargo" target="_blank">
                                            <i class="bi bi-printer"></i>
                                        </a>
                                        <?php if ($isAdmin): ?>
                                            <a href="index.php?page=transaksi&act=edit&id=<?= $t['trans_id'] ?>" class="btn btn-outline-warning" title="Edit Transaksi">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <a href="index.php?page=transaksi&act=hapus&id=<?= $t['trans_id'] ?>" class="btn btn-outline-danger btn-delete-confirm rounded-end-pill" data-name="Resi NCE-<?= str_pad((string)$t['trans_id'], 5, '0', STR_PAD_LEFT) ?>" title="Hapus Transaksi">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>

<?php
require_once __DIR__ . '/../includes/footer.php';