/**
 * Nusantara Cargo Express (NCE)
 * Interactive Script & Form Automation
 */

document.addEventListener('DOMContentLoaded', function () {
    // ----------------------------------------------------
    // Kalkulasi Otomatis Form Transaksi (Berat x Tarif Layanan)
    // ----------------------------------------------------
    const selectLayanan = document.getElementById('trans_id_layanan');
    const inputBerat    = document.getElementById('trans_berat');
    const inputTotal    = document.getElementById('trans_total_display');
    const hiddenTotal   = document.getElementById('trans_total');

    function hitungTotal() {
        if (!selectLayanan || !inputBerat) return;

        const selectedOption = selectLayanan.options[selectLayanan.selectedIndex];
        const tarif = selectedOption ? parseInt(selectedOption.getAttribute('data-harga') || '0', 10) : 0;
        const berat = parseInt(inputBerat.value || '0', 10);

        const total = (berat > 0 && tarif > 0) ? (berat * tarif) : 0;

        if (inputTotal) {
            inputTotal.value = 'Rp ' + total.toLocaleString('id-ID');
        }
        if (hiddenTotal) {
            hiddenTotal.value = total;
        }
    }

    if (selectLayanan && inputBerat) {
        selectLayanan.addEventListener('change', hitungTotal);
        inputBerat.addEventListener('input', hitungTotal);
        // Hitung awal jika mode edit
        hitungTotal();
    }

    // ----------------------------------------------------
    // Konfirmasi Hapus Data Universal
    // ----------------------------------------------------
    const deleteButtons = document.querySelectorAll('.btn-delete-confirm');
    deleteButtons.forEach(btn => {
        btn.addEventListener('click', function (e) {
            const nama = this.getAttribute('data-name') || 'data ini';
            if (!confirm(`Apakah Anda yakin ingin menghapus ${nama}? Data yang sudah dihapus tidak dapat dikembalikan.`)) {
                e.preventDefault();
            }
        });
    });
});