<?php
/**
 * Nusantara Cargo Express (NCE)
 * Alert Notification Helper
 */
declare(strict_types=1);

$flash = get_flash();
if ($flash):
    $icons = [
        'success' => 'bi-check-circle-fill',
        'danger'  => 'bi-exclamation-triangle-fill',
        'warning' => 'bi-exclamation-circle-fill',
        'info'    => 'bi-info-circle-fill'
    ];
    $icon = $icons[$flash['type']] ?? 'bi-info-circle-fill';
?>
<div class="alert alert-<?= htmlspecialchars($flash['type']) ?> alert-dismissible fade show d-flex align-items-center gap-2 shadow-sm rounded-3 mb-4" role="alert">
    <i class="bi <?= $icon ?> fs-5"></i>
    <div><?= htmlspecialchars($flash['message']) ?></div>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>