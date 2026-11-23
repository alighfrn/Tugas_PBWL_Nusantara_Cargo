<?php
/**
 * Nusantara Cargo Express (NCE)
 * Model: Pelanggan (tb_pelanggan)
 */

declare(strict_types=1);

require_once __DIR__ . '/BaseModel.php';

class Pelanggan extends BaseModel {
    private string $table = 'tb_pelanggan';

    public function getAll(): array {
        $stmt = $this->db->query("SELECT * FROM {$this->table} ORDER BY pel_id DESC");
        return $stmt->fetchAll();
    }

    public function getById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE pel_id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        $res = $stmt->fetch();
        return $res ?: null;
    }

    public function create(array $data): bool {
        $sql = "INSERT INTO {$this->table} (pel_nama, pel_alamat, pel_hp) 
                VALUES (:pel_nama, :pel_alamat, :pel_hp)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':pel_nama'   => trim($data['pel_nama']),
            ':pel_alamat' => trim($data['pel_alamat'] ?? ''),
            ':pel_hp'     => trim($data['pel_hp'] ?? ''),
        ]);
    }

    public function update(int $id, array $data): bool {
        $sql = "UPDATE {$this->table} SET 
                pel_nama = :pel_nama, 
                pel_alamat = :pel_alamat, 
                pel_hp = :pel_hp 
                WHERE pel_id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':pel_nama'   => trim($data['pel_nama']),
            ':pel_alamat' => trim($data['pel_alamat'] ?? ''),
            ':pel_hp'     => trim($data['pel_hp'] ?? ''),
            ':id'         => $id,
        ]);
    }

    public function delete(int $id): array {
        // Cek apakah pelanggan memiliki transaksi terkait
        $check = $this->db->prepare("SELECT COUNT(*) FROM tb_transaksi WHERE trans_id_pelanggan = :id");
        $check->execute([':id' => $id]);
        if ($check->fetchColumn() > 0) {
            return [
                'status' => false, 
                'message' => 'Data pengirim tidak dapat dihapus karena memiliki riwayat transaksi pengiriman kargo!'
            ];
        }

        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE pel_id = :id");
        $success = $stmt->execute([':id' => $id]);
        return [
            'status' => $success,
            'message' => $success ? 'Data pengirim berhasil dihapus.' : 'Gagal menghapus data pengirim.'
        ];
    }

    public function count(): int {
        return (int)$this->db->query("SELECT COUNT(*) FROM {$this->table}")->fetchColumn();
    }
}