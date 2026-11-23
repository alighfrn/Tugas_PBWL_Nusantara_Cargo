<?php
/**
 * Nusantara Cargo Express (NCE)
 * Model: Layanan (tb_layanan)
 */

declare(strict_types=1);

require_once __DIR__ . '/BaseModel.php';

class Layanan extends BaseModel {
    private string $table = 'tb_layanan';

    public function getAll(): array {
        $stmt = $this->db->query("SELECT * FROM {$this->table} ORDER BY lay_id ASC");
        return $stmt->fetchAll();
    }

    public function getById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE lay_id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        $res = $stmt->fetch();
        return $res ?: null;
    }

    public function create(array $data): bool {
        $sql = "INSERT INTO {$this->table} (lay_jenis, lay_harga) VALUES (:lay_jenis, :lay_harga)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':lay_jenis' => trim($data['lay_jenis']),
            ':lay_harga' => (int)$data['lay_harga'],
        ]);
    }

    public function update(int $id, array $data): bool {
        $sql = "UPDATE {$this->table} SET lay_jenis = :lay_jenis, lay_harga = :lay_harga WHERE lay_id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':lay_jenis' => trim($data['lay_jenis']),
            ':lay_harga' => (int)$data['lay_harga'],
            ':id'        => $id,
        ]);
    }

    public function delete(int $id): array {
        // Cek apakah layanan masih dipakai pada tb_transaksi
        $check = $this->db->prepare("SELECT COUNT(*) FROM tb_transaksi WHERE trans_id_layanan = :id");
        $check->execute([':id' => $id]);
        if ($check->fetchColumn() > 0) {
            return [
                'status' => false, 
                'message' => 'Layanan ini tidak dapat dihapus karena tercatat dalam transaksi pengiriman kargo!'
            ];
        }

        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE lay_id = :id");
        $success = $stmt->execute([':id' => $id]);
        return [
            'status' => $success,
            'message' => $success ? 'Paket layanan berhasil dihapus.' : 'Gagal menghapus layanan.'
        ];
    }

    public function count(): int {
        return (int)$this->db->query("SELECT COUNT(*) FROM {$this->table}")->fetchColumn();
    }
}