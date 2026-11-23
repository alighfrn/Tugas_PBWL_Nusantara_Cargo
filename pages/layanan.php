<?php
/**
 * Nusantara Cargo Express (NCE)
 * Modul: Paket Layanan Kargo (tb_layanan)
 * Hak Akses: Admin (Kelola Penuh) & Operator (Lihat Tarif)
 */

declare(strict_types=1);

require_login();

$layananModel = new Layanan();
$act = $_GET['act'] ?? 'index';
$pageTitle = 'Paket Layanan Kargo';
$pageSubtitle = 'Kelola daftar jenis pengiriman kargo dan tarif per kilogram';

$isAdmin = is_admin();

// Jika bukan admin dan mencoba aksi selain melihat (index)
if (!$isAdmin && in_array($act, ['tambah', 'simpan', 'edit', 'update', 'hapus'], true)) {
    set_flash('danger', 'Akses ditolak! Hanya Administrator yang berwenang mengubah tarif paket layanan.');
    header('Location: index.php?page=layanan');
    exit;
}

// -------------------------------------------------------------------
// PROSES SIMPAN LAYANAN BARU (KHUSUS ADMIN)
// -------------------------------------------------------------------
if ($act === 'simpan' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $jenis = trim($_POST['lay_jenis'] ?? '');
    $harga = (int)($_POST['lay_harga'] ?? 0);

    if (empty($jenis) || $harga <= 0) {
        set_flash('danger', 'Nama jenis layanan dan tarif per Kg wajib diisi dengan benar!');
        header('Location: index.php?page=layanan&act=tambah');
        exit;
    }

    $ok = $layananModel->create([
        'lay_jenis' => $jenis,
        'lay_harga' => $harga,
    ]);

    if ($ok) {
        set_flash('success', 'Paket layanan baru berhasil ditambahkan!');
    } else {
        set_flash('danger', 'Gagal menambahkan paket layanan.');
    }
    header('Location: index.php?page=layanan');
    exit;
}

// -------------------------------------------------------------------
// PROSES UPDATE LAYANAN (KHUSUS ADMIN)
// -------------------------------------------------------------------
if ($act === 'update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $id    = (int)($_POST['lay_id'] ?? 0);
    $jenis = trim($_POST['lay_jenis'] ?? '');
    $harga = (int)($_POST['lay_harga'] ?? 0);

    if (empty($jenis) || $harga <= 0 || $id <= 0) {
        set_flash('danger', 'Data paket layanan tidak valid!');
        header('Location: index.php?page=layanan&act=edit&id=' . $id);
        exit;
    }

    $ok = $layananModel->update($id, [
        'lay_jenis' => $jenis,
        'lay_harga' => $harga,
    ]);

    if ($ok) {
        set_flash('success', 'Perubahan tarif paket layanan berhasil disimpan!');
    } else {
        set_flash('danger', 'Gagal memperbarui paket layanan.');
    }
    header('Location: index.php?page=layanan');
    exit;
}

// -------------------------------------------------------------------
// PROSES HAPUS LAYANAN (KHUSUS ADMIN)
// -------------------------------------------------------------------
if ($act === 'hapus') {
    $id = (int)($_GET['id'] ?? 0);
    $res = $layananModel->delete($id);
    if ($res['status']) {
        set_flash('success', $res['message']);
    } else {
        set_flash('danger', $res['message']);
    }
    header('Location: index.php?page=layanan');
    exit;
}

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
?>

<?php require_once __DIR__ . '/../includes/alert.php'; ?>

<?php if ($act === 'tambah' && $isAdmin): ?>
    <!-- FORM TAMBAH LAYANAN -->
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card-modern">
                <div class="p-3 px-4 bg-white border-bottom d-flex justify-content-between align-items-center" style="border-top-left-radius: var(--nce-radius-lg); border-top-right-radius: var(--nce-radius-lg);">
                    <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-tag-fill me-2 text-primary"></i>Tambah Paket Layanan Kargo</h6>
                    <a href="index.php?page=layanan" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                        <i class="bi bi-arrow-left me-1"></i> Kembali
                    </a>
                </div>
                <div class="p-4">
                    <form method="POST" action="index.php?page=layanan&act=simpan">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama / Jenis Layanan <span class="text-danger">*</span></label>
                            <input type="text" name="lay_jenis" class="form-control" placeholder="Contoh: Kargo Truk Muatan Besar (3-5 Hari)" required autofocus>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Tarif Dasar per Kilogram (Rp) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">Rp</span>
                                <input type="number" name="lay_harga" class="form-control" placeholder="Contoh: 15000" min="500" step="500" required>
                                <span class="input-group-text bg-light">/ Kg</span>
                            </div>
                            <small class="text-muted">Tarif ini akan dikalikan otomatis dengan timbangan kargo di form transaksi.</small>
                        </div>
                        <div class="d-flex justify-content-end gap-2">
                            <a href="index.php?page=layanan" class="btn btn-light px-4 rounded-pill">Batal</a>
                            <button type="submit" class="btn btn-primary px-4 fw-semibold rounded-pill">
                                <i class="bi bi-save me-1"></i> Simpan Paket
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<?php elseif ($act === 'edit' && $isAdmin): ?>
    <!-- FORM EDIT LAYANAN -->
    <?php
    $id = (int)($_GET['id'] ?? 0);
    $data = $layananModel->getById($id);
    if (!$data) {
        set_flash('danger', 'Data paket layanan tidak ditemukan!');
        header('Location: index.php?page=layanan');
        exit;
    }
    ?>
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card-modern">
                <div class="p-3 px-4 bg-white border-bottom d-flex justify-content-between align-items-center" style="border-top-left-radius: var(--nce-radius-lg); border-top-right-radius: var(--nce-radius-lg);">
                    <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-pencil-square me-2 text-warning"></i>Edit Paket Layanan</h6>
                    <a href="index.php?page=layanan" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                        <i class="bi bi-arrow-left me-1"></i> Kembali
                    </a>
                </div>
                <div class="p-4">
                    <form method="POST" action="index.php?page=layanan&act=update">
                        <input type="hidden" name="lay_id" value="<?= $data['lay_id'] ?>">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama / Jenis Layanan <span class="text-danger">*</span></label>
                            <input type="text" name="lay_jenis" class="form-control" value="<?= htmlspecialchars($data['lay_jenis']) ?>" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Tarif Dasar per Kilogram (Rp) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">Rp</span>
                                <input type="number" name="lay_harga" class="form-control" value="<?= $data['lay_harga'] ?>" min="500" step="500" required>
                                <span class="input-group-text bg-light">/ Kg</span>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end gap-2">
                            <a href="index.php?page=layanan" class="btn btn-light px-4 rounded-pill">Batal</a>
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
    <!-- TABEL LIST LAYANAN -->
    <?php $listLayanan = $layananModel->getAll(); ?>
    <div class="card-modern">
        <div class="p-3 px-4 bg-white border-bottom d-flex justify-content-between align-items-center" style="border-top-left-radius: var(--nce-radius-lg); border-top-right-radius: var(--nce-radius-lg);">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-tags-fill text-primary fs-5"></i>
                <div>
                    <span class="fw-bold text-dark">Daftar Paket Layanan & Tarif</span>
                    <?php if (!$isAdmin): ?>
                        <small class="text-muted d-block" style="font-size: 0.75rem;">Mode Operator: Hanya melihat daftar tarif resmi</small>
                    <?php endif; ?>
                </div>
                <span class="badge-pill badge-soft-secondary ms-2"><?= count($listLayanan) ?> Layanan</span>
            </div>
            <?php if ($isAdmin): ?>
                <a href="index.php?page=layanan&act=tambah" class="btn btn-sm btn-primary rounded-pill px-3 shadow-sm">
                    <i class="bi bi-plus-lg me-1"></i> Tambah Layanan
                </a>
            <?php endif; ?>
        </div>
        <div class="table-responsive">
            <table class="table-modern">
                <thead>
                    <tr>
                        <th style="width: 60px;">No</th>
                        <th>Jenis Layanan Kargo</th>
                        <th>Tarif per Kilogram</th>
                        <?php if ($isAdmin): ?>
                            <th class="text-center" style="width: 140px;">Aksi (Admin)</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($listLayanan)): ?>
                        <tr>
                            <td colspan="<?= $isAdmin ? 4 : 3 ?>" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-2 d-block mb-2 text-secondary"></i>
                                Belum ada data paket layanan.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1; foreach ($listLayanan as $l): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td>
                                    <div class="fw-bold text-dark"><?= htmlspecialchars($l['lay_jenis']) ?></div>
                                    <span class="badge-pill badge-soft-secondary" style="font-size: 0.7rem;">Kode: LAY-<?= str_pad((string)$l['lay_id'], 3, '0', STR_PAD_LEFT) ?></span>
                                </td>
                                <td>
                                    <span class="badge-pill badge-soft-success fw-bold fs-6">
                                        <?= format_rupiah($l['lay_harga']) ?> <span class="small fw-normal">/ Kg</span>
                                    </span>
                                </td>
                                <?php if ($isAdmin): ?>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm">
                                            <a href="index.php?page=layanan&act=edit&id=<?= $l['lay_id'] ?>" class="btn btn-outline-warning rounded-start-pill" title="Edit Layanan">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <a href="index.php?page=layanan&act=hapus&id=<?= $l['lay_id'] ?>" class="btn btn-outline-danger btn-delete-confirm rounded-end-pill" data-name="<?= htmlspecialchars($l['lay_jenis']) ?>" title="Hapus Layanan">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                <?php endif; ?>
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