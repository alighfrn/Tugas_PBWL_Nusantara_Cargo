<?php
/**
 * Nusantara Cargo Express (NCE)
 * Modul: Manajemen Petugas / Pengguna (tb_users)
 * KHUSUS HAK AKSES ADMINISTRATOR
 */

declare(strict_types=1);

// Proteksi Khusus Admin
require_admin();

$userModel = new User();
$act = $_GET['act'] ?? 'index';
$pageTitle = 'Kelola Petugas / Operator';
$pageSubtitle = 'Manajemen akun login dan hak akses petugas ekspedisi (Khusus Administrator)';

$currentLoggedIn = current_user();

// -------------------------------------------------------------------
// PROSES SIMPAN USER BARU
// -------------------------------------------------------------------
if ($act === 'simpan' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['user_name'] ?? '');
    $password = trim($_POST['user_password'] ?? '');
    $nama     = trim($_POST['user_namalengkap'] ?? '');
    $aktif    = (int)($_POST['user_aktif'] ?? 1);

    if (empty($username) || empty($password) || empty($nama)) {
        set_flash('danger', 'Username, password, dan nama lengkap wajib diisi!');
        header('Location: index.php?page=users&act=tambah');
        exit;
    }

    $ok = $userModel->create([
        'user_name'        => $username,
        'user_password'    => $password,
        'user_namalengkap' => $nama,
        'user_aktif'       => $aktif,
    ]);

    if ($ok) {
        set_flash('success', 'Akun petugas baru berhasil dibuat!');
    } else {
        set_flash('danger', 'Gagal membuat akun petugas.');
    }
    header('Location: index.php?page=users');
    exit;
}

// -------------------------------------------------------------------
// PROSES UPDATE USER
// -------------------------------------------------------------------
if ($act === 'update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $id       = (int)($_POST['user_id'] ?? 0);
    $username = trim($_POST['user_name'] ?? '');
    $password = trim($_POST['user_password'] ?? '');
    $nama     = trim($_POST['user_namalengkap'] ?? '');
    $aktif    = (int)($_POST['user_aktif'] ?? 1);

    if ($id <= 0 || empty($username) || empty($nama)) {
        set_flash('danger', 'Username dan nama lengkap wajib diisi!');
        header('Location: index.php?page=users&act=edit&id=' . $id);
        exit;
    }

    $ok = $userModel->update($id, [
        'user_name'        => $username,
        'user_password'    => $password,
        'user_namalengkap' => $nama,
        'user_aktif'       => $aktif,
    ]);

    if ($ok) {
        set_flash('success', 'Data akun petugas berhasil diperbarui!');
    } else {
        set_flash('danger', 'Gagal memperbarui data akun petugas.');
    }
    header('Location: index.php?page=users');
    exit;
}

// -------------------------------------------------------------------
// PROSES HAPUS USER
// -------------------------------------------------------------------
if ($act === 'hapus') {
    $id = (int)($_GET['id'] ?? 0);

    if ($id === (int)$currentLoggedIn['user_id']) {
        set_flash('danger', 'Anda tidak dapat menghapus akun Anda sendiri yang sedang aktif digunakan!');
    } else {
        $ok = $userModel->delete($id);
        if ($ok) {
            set_flash('success', 'Akun petugas berhasil dihapus.');
        } else {
            set_flash('danger', 'Gagal menghapus akun petugas.');
        }
    }
    header('Location: index.php?page=users');
    exit;
}

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
?>

<?php require_once __DIR__ . '/../includes/alert.php'; ?>

<?php if ($act === 'tambah'): ?>
    <!-- FORM TAMBAH USER -->
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card-modern">
                <div class="p-3 px-4 bg-white border-bottom d-flex justify-content-between align-items-center" style="border-top-left-radius: var(--nce-radius-lg); border-top-right-radius: var(--nce-radius-lg);">
                    <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-person-plus me-2 text-primary"></i>Tambah Petugas Baru</h6>
                    <a href="index.php?page=users" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                        <i class="bi bi-arrow-left me-1"></i> Kembali
                    </a>
                </div>
                <div class="p-4">
                    <form method="POST" action="index.php?page=users&act=simpan">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama Lengkap Petugas <span class="text-danger">*</span></label>
                            <input type="text" name="user_namalengkap" class="form-control" placeholder="Contoh: Rahmat Hidayat" required autofocus>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Username Login <span class="text-danger">*</span></label>
                            <input type="text" name="user_name" class="form-control" placeholder="Contoh: rahmat" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Password <span class="text-danger">*</span></label>
                            <input type="password" name="user_password" class="form-control" placeholder="Minimal 6 karakter" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Status Akun</label>
                            <select name="user_aktif" class="form-select">
                                <option value="1">Aktif (Dapat Login)</option>
                                <option value="0">Non-Aktif (Diblokir)</option>
                            </select>
                        </div>
                        <div class="d-flex justify-content-end gap-2">
                            <a href="index.php?page=users" class="btn btn-light px-4 rounded-pill">Batal</a>
                            <button type="submit" class="btn btn-primary px-4 fw-semibold rounded-pill">
                                <i class="bi bi-save me-1"></i> Simpan Petugas
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<?php elseif ($act === 'edit'): ?>
    <!-- FORM EDIT USER -->
    <?php
    $id = (int)($_GET['id'] ?? 0);
    $data = $userModel->getById($id);
    if (!$data) {
        set_flash('danger', 'Data akun petugas tidak ditemukan!');
        header('Location: index.php?page=users');
        exit;
    }
    ?>
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card-modern">
                <div class="p-3 px-4 bg-white border-bottom d-flex justify-content-between align-items-center" style="border-top-left-radius: var(--nce-radius-lg); border-top-right-radius: var(--nce-radius-lg);">
                    <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-pencil-square me-2 text-warning"></i>Edit Akun Petugas</h6>
                    <a href="index.php?page=users" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                        <i class="bi bi-arrow-left me-1"></i> Kembali
                    </a>
                </div>
                <div class="p-4">
                    <form method="POST" action="index.php?page=users&act=update">
                        <input type="hidden" name="user_id" value="<?= $data['user_id'] ?>">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama Lengkap Petugas <span class="text-danger">*</span></label>
                            <input type="text" name="user_namalengkap" class="form-control" value="<?= htmlspecialchars($data['user_namalengkap']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Username Login <span class="text-danger">*</span></label>
                            <input type="text" name="user_name" class="form-control" value="<?= htmlspecialchars($data['user_name']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Ganti Password (Opsional)</label>
                            <input type="password" name="user_password" class="form-control" placeholder="Kosongkan jika tidak ingin mengubah password">
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Status Akun</label>
                            <select name="user_aktif" class="form-select">
                                <option value="1" <?= $data['user_aktif'] == 1 ? 'selected' : '' ?>>Aktif (Dapat Login)</option>
                                <option value="0" <?= $data['user_aktif'] == 0 ? 'selected' : '' ?>>Non-Aktif (Diblokir)</option>
                            </select>
                        </div>
                        <div class="d-flex justify-content-end gap-2">
                            <a href="index.php?page=users" class="btn btn-light px-4 rounded-pill">Batal</a>
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
    <!-- TABEL SEMUA USER -->
    <?php $listUsers = $userModel->getAll(); ?>
    <div class="card-modern">
        <div class="p-3 px-4 bg-white border-bottom d-flex justify-content-between align-items-center" style="border-top-left-radius: var(--nce-radius-lg); border-top-right-radius: var(--nce-radius-lg);">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-shield-lock-fill text-warning fs-5"></i>
                <div>
                    <span class="fw-bold text-dark">Daftar Akun Petugas & Operator</span>
                    <small class="text-muted d-block" style="font-size: 0.75rem;">Hanya Administrator yang memiliki akses ke halaman ini</small>
                </div>
            </div>
            <a href="index.php?page=users&act=tambah" class="btn btn-sm btn-primary rounded-pill px-3 shadow-sm">
                <i class="bi bi-plus-lg me-1"></i> Tambah Petugas
            </a>
        </div>
        <div class="table-responsive">
            <table class="table-modern">
                <thead>
                    <tr>
                        <th style="width: 60px;">No</th>
                        <th>Nama Petugas</th>
                        <th>Username</th>
                        <th class="text-center">Role / Peran</th>
                        <th class="text-center">Status Akun</th>
                        <th class="text-center" style="width: 140px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($listUsers as $u): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td>
                                <div class="fw-bold text-dark"><?= htmlspecialchars($u['user_namalengkap']) ?></div>
                                <span class="badge-pill badge-soft-secondary" style="font-size: 0.7rem;">User ID: #<?= $u['user_id'] ?></span>
                            </td>
                            <td>
                                <span class="badge-pill badge-soft-secondary font-monospace fw-bold"><?= htmlspecialchars($u['user_name']) ?></span>
                            </td>
                            <td class="text-center">
                                <?php if (strtolower($u['user_name']) === 'admin' || (int)$u['user_id'] === 1): ?>
                                    <span class="badge-pill badge-soft-warning fw-bold"><i class="bi bi-shield-fill-check me-1"></i>Administrator</span>
                                <?php else: ?>
                                    <span class="badge-pill badge-soft-primary"><i class="bi bi-person-badge-fill me-1"></i>Operator Loket</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <?php if ((int)$u['user_aktif'] === 1): ?>
                                    <span class="badge-pill badge-soft-success"><i class="bi bi-check-circle-fill me-1"></i>Aktif</span>
                                <?php else: ?>
                                    <span class="badge-pill badge-soft-danger"><i class="bi bi-x-circle-fill me-1"></i>Non-Aktif</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm">
                                    <a href="index.php?page=users&act=edit&id=<?= $u['user_id'] ?>" class="btn btn-outline-warning rounded-start-pill" title="Edit Akun">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <?php if ($u['user_id'] != $currentLoggedIn['user_id']): ?>
                                        <a href="index.php?page=users&act=hapus&id=<?= $u['user_id'] ?>" class="btn btn-outline-danger btn-delete-confirm rounded-end-pill" data-name="<?= htmlspecialchars($u['user_namalengkap']) ?>" title="Hapus Akun">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    <?php else: ?>
                                        <button class="btn btn-outline-secondary rounded-end-pill" disabled title="Akun Anda Sedang Digunakan">
                                            <i class="bi bi-lock"></i>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>

<?php
require_once __DIR__ . '/../includes/footer.php';