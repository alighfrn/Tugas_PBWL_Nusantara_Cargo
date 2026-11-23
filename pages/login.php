<?php
/**
 * Nusantara Cargo Express (NCE)
 * Halaman Login Sistem
 */

declare(strict_types=1);

if (is_logged_in()) {
    header('Location: index.php?page=dashboard');
    exit;
}

$userModel = new User();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['user_name'] ?? '');
    $password = trim($_POST['user_password'] ?? '');

    if (empty($username) || empty($password)) {
        $error = 'Username dan Password wajib diisi!';
    } else {
        $res = $userModel->login($username, $password);
        if ($res['status']) {
            $_SESSION['user_logged_in'] = true;
            $_SESSION['user_data'] = [
                'user_id'          => $res['data']['user_id'],
                'user_name'        => $res['data']['user_name'],
                'user_namalengkap' => $res['data']['user_namalengkap'],
                'user_aktif'       => $res['data']['user_aktif'],
            ];
            set_flash('success', 'Akses diizinkan. Selamat datang, ' . htmlspecialchars($res['data']['user_namalengkap']) . '!');
            header('Location: index.php?page=dashboard');
            exit;
        } else {
            $error = $res['message'];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Masuk &bull; <?= APP_NAME ?></title>
    
    <!-- Favicon NCE -->
    <link rel="icon" type="image/svg+xml" href="assets/images/logo-nce.svg">
    
    <!-- Google Fonts: Plus Jakarta Sans & Space Grotesk -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Custom Style Terpusat -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="login-page-body">

<div class="glow-orb-1"></div>
<div class="glow-orb-2"></div>

<div class="futuristic-wrapper">
    <div class="row g-0">
        <!-- Hero Left Side -->
        <div class="col-lg-6 hero-panel">
            <div class="neon-badge mb-3">
                <span class="pulse-dot"></span>
                <span>SISTEM TERHUBUNG REAL-TIME</span>
            </div>

            <h1 class="brand-hero-title"><?= APP_NAME ?></h1>
            <p class="text-secondary small mb-0" style="line-height: 1.6;">
                Portal terpadu ekspedisi logistik masa kini. Pantau rute surat muatan, kalkulasi tarif timbangan kargo instan, dan kelola nomor resi secara otomatis.
            </p>

            <!-- Cyber Metric Highlights -->
            <div class="cyber-grid">
                <div class="cyber-chip">
                    <div class="cyber-chip-num">24/7</div>
                    <div class="cyber-chip-lbl">Operasional Resi</div>
                </div>
                <div class="cyber-chip">
                    <div class="cyber-chip-num">100%</div>
                    <div class="cyber-chip-lbl">Akurasi Timbangan</div>
                </div>
                <div class="cyber-chip">
                    <div class="cyber-chip-num">Instant</div>
                    <div class="cyber-chip-lbl">Cetak Surat Muatan</div>
                </div>
                <div class="cyber-chip">
                    <div class="cyber-chip-num">Bcrypt</div>
                    <div class="cyber-chip-lbl">Keamanan Data</div>
                </div>
            </div>
        </div>

        <!-- Form Right Side -->
        <div class="col-lg-6 form-panel">
            <div class="mb-4">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <div class="rounded-3 d-flex align-items-center justify-content-center text-white" style="width: 32px; height: 32px; background: linear-gradient(135deg, #2563eb, #06b6d4); font-size: 1rem;">
                        <i class="bi bi-shield-lock-fill"></i>
                    </div>
                    <h4 class="fw-bold text-white mb-0" style="letter-spacing: -0.02em;">Otentikasi Petugas</h4>
                </div>
                <p class="text-secondary small">Masukkan identitas kredensial Anda untuk masuk ke sistem.</p>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-danger py-2 px-3 small d-flex align-items-center gap-2 rounded-3 mb-4" style="background: rgba(239, 68, 68, 0.15); border: 1px solid rgba(239, 68, 68, 0.3); color: #fca5a5;">
                    <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                    <div><?= htmlspecialchars($error) ?></div>
                </div>
            <?php endif; ?>

            <?php require_once __DIR__ . '/../includes/alert.php'; ?>

            <form method="POST" action="index.php?page=login" id="loginForm">
                <div class="mb-3">
                    <label class="form-label small text-secondary fw-semibold mb-1">Username Petugas</label>
                    <div class="input-futuristic">
                        <input type="text" name="user_name" id="user_name" placeholder="Contoh: admin / operator" required autofocus value="<?= htmlspecialchars($_POST['user_name'] ?? '') ?>">
                        <i class="bi bi-person-fill field-icon"></i>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label small text-secondary fw-semibold mb-1">Kata Sandi (Password)</label>
                    <div class="input-futuristic">
                        <input type="password" name="user_password" id="user_password" placeholder="Masukkan kata sandi" required>
                        <i class="bi bi-key-fill field-icon"></i>
                        <button type="button" class="btn-toggle-pw" id="togglePasswordBtn" title="Tampilkan Password">
                            <i class="bi bi-eye-slash-fill" id="eyeIcon"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn-futuristic mb-4">
                    <i class="bi bi-box-arrow-in-right me-2"></i> Akses Portal Ekspedisi
                </button>
            </form>

            <!-- Tombol Cepat Pilihan Akun -->
            <div class="pt-3 border-top" style="border-color: rgba(255, 255, 255, 0.08) !important;">
                <div class="mb-2">
                    <small class="text-secondary fw-bold" style="font-size: 0.72rem; letter-spacing: 0.06em;">PILIH AKUN LOGIN CEPAT:</small>
                </div>
                <div class="row g-2">
                    <div class="col-6">
                        <div class="demo-chip" onclick="fillDemo('admin', 'adminpassword')">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-shield-fill-check text-warning fs-5"></i>
                                <div>
                                    <div class="fw-bold text-white small">Mode Admin</div>
                                    <div class="text-secondary" style="font-size: 0.68rem;">adminpassword</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="demo-chip" onclick="fillDemo('operator', 'adminpassword')">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-person-badge-fill text-info fs-5"></i>
                                <div>
                                    <div class="fw-bold text-white small">Mode Operator</div>
                                    <div class="text-secondary" style="font-size: 0.68rem;">adminpassword</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Fungsi Pengisian Cepat Akun
    function fillDemo(username, password) {
        document.getElementById('user_name').value = username;
        document.getElementById('user_password').value = password;
    }

    // Toggle Password Visibility
    const toggleBtn = document.getElementById('togglePasswordBtn');
    const passwordInput = document.getElementById('user_password');
    const eyeIcon = document.getElementById('eyeIcon');

    if (toggleBtn && passwordInput && eyeIcon) {
        toggleBtn.addEventListener('click', function () {
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.replace('bi-eye-slash-fill', 'bi-eye-fill');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.replace('bi-eye-fill', 'bi-eye-slash-fill');
            }
        });
    }
</script>

</body>
</html>