<?php
/**
 * Nusantara Cargo Express (NCE)
 * Modul: Pelanggan / Pengirim (tb_pelanggan)
 */

declare(strict_types=1);

require_login();

$pelangganModel = new Pelanggan();
$act = $_GET['act'] ?? 'index';
$pageTitle = 'Data Pengirim (Pelanggan)';
$pageSubtitle = 'Kelola data perusahaan dan pengirim kargo ekspedisi';

// -------------------------------------------------------------------
// PROSES SIMPAN DATA BARU
// -------------------------------------------------------------------
if ($act === 'simpan' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama   = trim($_POST['pel_nama'] ?? '');
    $alamat = trim($_POST['pel_alamat'] ?? '');
    $hp     = trim($_POST['pel_hp'] ?? '');

    if (empty($nama)) {
        set_flash('danger', 'Nama pengirim wajib diisi!');
        header('Location: index.php?page=pelanggan&act=tambah');
        exit;
    }

    $ok = $pelangganModel->create([
        'pel_nama'   => $nama,
        'pel_alamat' => $alamat,
        'pel_hp'     => $hp,
    ]);

    if ($ok) {
        set_flash('success', 'Data pengirim baru berhasil disimpan!');
    } else {
        set_flash('danger', 'Gagal menyimpan data pengirim.');
    }
    header('Location: index.php?page=pelanggan');
    exit;
}

// -------------------------------------------------------------------
// PROSES UPDATE DATA
// -------------------------------------------------------------------
if ($act === 'update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $id     = (int)($_POST['pel_id'] ?? 0);
    $nama   = trim($_POST['pel_nama'] ?? '');
    $alamat = trim($_POST['pel_alamat'] ?? '');
    $hp     = trim($_POST['pel_hp'] ?? '');

    if (empty($nama) || $id <= 0) {
        set_flash('danger', 'Nama pengirim wajib diisi!');
        header('Location: index.php?page=pelanggan&act=edit&id=' . $id);
        exit;
    }

    $ok = $pelangganModel->update($id, [
        'pel_nama'   => $nama,
        'pel_alamat' => $alamat,
        'pel_hp'     => $hp,
    ]);

    if ($ok) {
        set_flash('success', 'Perubahan data pengirim berhasil disimpan!');
    } else {
        set_flash('danger', 'Gagal memperbarui data pengirim.');
    }
    header('Location: index.php?page=pelanggan');
    exit;
}

// -------------------------------------------------------------------
// PROSES HAPUS DATA
// -------------------------------------------------------------------
if ($act === 'hapus') {
    $id = (int)($_GET['id'] ?? 0);
    $res = $pelangganModel->delete($id);
    if ($res['status']) {
        set_flash('success', $res['message']);
    } else {
        set_flash('danger', $res['message']);
    }
    header('Location: index.php?page=pelanggan');
    exit;
}

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
?>

<?php require_once __DIR__ . '/../includes/alert.php'; ?>

<?php if ($act === 'tambah'): ?>
    <!-- FORM TAMBAH PENGIRIM -->
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card-modern">
                <div class="p-3 px-4 bg-white border-bottom d-flex justify-content-between align-items-center" style="border-top-left-radius: var(--nce-radius-lg); border-top-right-radius: var(--nce-radius-lg);">
                    <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-person-plus-fill me-2 text-primary"></i>Tambah Pengirim Baru</h6>
                    <a href="index.php?page=pelanggan" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                        <i class="bi bi-arrow-left me-1"></i> Kembali
                    </a>
                </div>
                <div class="p-4">
                    <form method="POST" action="index.php?page=pelanggan&act=simpan">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama Lengkap / Perusahaan Pengirim <span class="text-danger">*</span></label>
                            <input type="text" name="pel_nama" class="form-control" placeholder="Contoh: PT. Sumber Rezeki / Ali Ghufron" required autofocus>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nomor HP / WhatsApp</label>
                            <input type="text" name="pel_hp" class="form-control" placeholder="Contoh: 081234567890">
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Alamat Lengkap Asal Pengirim</label>
                            <textarea name="pel_alamat" class="form-control" rows="3" placeholder="Masukkan jalan, nomor gedung, kecamatan, kota"></textarea>
                        </div>
                        <div class="d-flex justify-content-end gap-2">
                            <a href="index.php?page=pelanggan" class="btn btn-light px-4 rounded-pill">Batal</a>
                            <button type="submit" class="btn btn-primary px-4 fw-semibold rounded-pill">
                                <i class="bi bi-save me-1"></i> Simpan Pengirim
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<?php elseif ($act === 'edit'): ?>
    <!-- FORM EDIT PENGIRIM -->
    <?php
    $id = (int)($_GET['id'] ?? 0);
    $data = $pelangganModel->getById($id);
    if (!$data) {
        set_flash('danger', 'Data pengirim tidak ditemukan!');
        header('Location: index.php?page=pelanggan');
        exit;
    }
    ?>
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card-modern">
                <div class="p-3 px-4 bg-white border-bottom d-flex justify-content-between align-items-center" style="border-top-left-radius: var(--nce-radius-lg); border-top-right-radius: var(--nce-radius-lg);">
                    <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-pencil-square me-2 text-warning"></i>Edit Data Pengirim</h6>
                    <a href="index.php?page=pelanggan" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                        <i class="bi bi-arrow-left me-1"></i> Kembali
                    </a>
                </div>
                <div class="p-4">
                    <form method="POST" action="index.php?page=pelanggan&act=update">
                        <input type="hidden" name="pel_id" value="<?= $data['pel_id'] ?>">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama Lengkap / Perusahaan Pengirim <span class="text-danger">*</span></label>
                            <input type="text" name="pel_nama" class="form-control" value="<?= htmlspecialchars($data['pel_nama']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nomor HP / WhatsApp</label>
                            <input type="text" name="pel_hp" class="form-control" value="<?= htmlspecialchars($data['pel_hp']) ?>">
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Alamat Lengkap Asal Pengirim</label>
                            <textarea name="pel_alamat" class="form-control" rows="3"><?= htmlspecialchars($data['pel_alamat']) ?></textarea>
                        </div>
                        <div class="d-flex justify-content-end gap-2">
                            <a href="index.php?page=pelanggan" class="btn btn-light px-4 rounded-pill">Batal</a>
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
    <!-- TABEL LIST PENGIRIM -->
    <?php $listPelanggan = $pelangganModel->getAll(); ?>
    <div class="card-modern">
        <div class="p-3 px-4 bg-white border-bottom d-flex justify-content-between align-items-center" style="border-top-left-radius: var(--nce-radius-lg); border-top-right-radius: var(--nce-radius-lg);">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-people-fill text-primary fs-5"></i>
                <span class="fw-bold">Daftar Pengirim Terdaftar</span>
                <span class="badge-pill badge-soft-secondary"><?= count($listPelanggan) ?> Data</span>
            </div>
            <a href="index.php?page=pelanggan&act=tambah" class="btn btn-sm btn-primary rounded-pill px-3 shadow-sm">
                <i class="bi bi-plus-lg me-1"></i> Tambah Pengirim
            </a>
        </div>
        <div class="table-responsive">
            <table class="table-modern">
                <thead>
                    <tr>
                        <th style="width: 60px;">No</th>
                        <th>Nama Pengirim</th>
                        <th>Kontak HP / WhatsApp</th>
                        <th>Alamat Asal</th>
                        <th class="text-center" style="width: 140px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($listPelanggan)): ?>
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-2 d-block mb-2 text-secondary"></i>
                                Belum ada data pengirim kargo. Klik tombol "Tambah Pengirim" untuk menambahkan.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1; foreach ($listPelanggan as $p): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td>
                                    <div class="fw-bold text-dark"><?= htmlspecialchars($p['pel_nama']) ?></div>
                                    <span class="badge-pill badge-soft-secondary" style="font-size: 0.7rem;">ID: #<?= $p['pel_id'] ?></span>
                                </td>
                                <td>
                                    <?php if ($p['pel_hp']): ?>
                                        <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $p['pel_hp']) ?>" target="_blank" class="text-decoration-none text-success fw-medium">
                                            <i class="bi bi-whatsapp me-1"></i> <?= htmlspecialchars($p['pel_hp']) ?>
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <small class="text-secondary"><?= htmlspecialchars($p['pel_alamat'] ?: '-') ?></small>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm">
                                        <a href="index.php?page=pelanggan&act=edit&id=<?= $p['pel_id'] ?>" class="btn btn-outline-warning rounded-start-pill" title="Edit Data">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="index.php?page=pelanggan&act=hapus&id=<?= $p['pel_id'] ?>" class="btn btn-outline-danger btn-delete-confirm rounded-end-pill" data-name="<?= htmlspecialchars($p['pel_nama']) ?>" title="Hapus Data">
                                            <i class="bi bi-trash"></i>
                                        </a>
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