<?php
/**
 * Nusantara Cargo Express (NCE)
 * Model: Transaksi (tb_transaksi)
 */

declare(strict_types=1);

require_once __DIR__ . '/BaseModel.php';

class Transaksi extends BaseModel {
    private string $table = 'tb_transaksi';

    public function getAll(): array {
        $sql = "SELECT t.*, p.pel_nama, p.pel_hp, p.pel_alamat, l.lay_jenis, l.lay_harga 
                FROM {$this->table} t
                JOIN tb_pelanggan p ON t.trans_id_pelanggan = p.pel_id
                JOIN tb_layanan l ON t.trans_id_layanan = l.lay_id
                ORDER BY t.trans_id DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function getById(int $id): ?array {
        $sql = "SELECT t.*, p.pel_nama, p.pel_hp, p.pel_alamat, l.lay_jenis, l.lay_harga 
                FROM {$this->table} t
                JOIN tb_pelanggan p ON t.trans_id_pelanggan = p.pel_id
                JOIN tb_layanan l ON t.trans_id_layanan = l.lay_id
                WHERE t.trans_id = :id 
                LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        $res = $stmt->fetch();
        return $res ?: null;
    }

    public function getRecent(int $limit = 5): array {
        $sql = "SELECT t.*, p.pel_nama, p.pel_hp, p.pel_alamat, l.lay_jenis, l.lay_harga 
                FROM {$this->table} t
                JOIN tb_pelanggan p ON t.trans_id_pelanggan = p.pel_id
                JOIN tb_layanan l ON t.trans_id_layanan = l.lay_id
                ORDER BY t.trans_id DESC 
                LIMIT :lim";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':lim', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function create(array $data): bool {
        // Ambil harga layanan untuk menjamin integritas kalkulasi
        $stmtLay = $this->db->prepare("SELECT lay_harga FROM tb_layanan WHERE lay_id = :lay_id LIMIT 1");
        $stmtLay->execute([':lay_id' => (int)$data['trans_id_layanan']]);
        $layanan = $stmtLay->fetch();

        if (!$layanan) {
            return false;
        }

        $berat = max(1, (int)$data['trans_berat']);
        $harga = (int)$layanan['lay_harga'];
        $total = $berat * $harga;

        $sql = "INSERT INTO {$this->table} 
                (trans_id_pelanggan, trans_id_layanan, trans_tgl_masuk, trans_tgl_selesai, trans_berat, trans_total) 
                VALUES (:pel, :lay, :tgl_masuk, :tgl_selesai, :berat, :total)";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':pel'         => (int)$data['trans_id_pelanggan'],
            ':lay'         => (int)$data['trans_id_layanan'],
            ':tgl_masuk'   => $data['trans_tgl_masuk'],
            ':tgl_selesai' => $data['trans_tgl_selesai'],
            ':berat'       => $berat,
            ':total'       => $total,
        ]);
    }

    public function update(int $id, array $data): bool {
        $stmtLay = $this->db->prepare("SELECT lay_harga FROM tb_layanan WHERE lay_id = :lay_id LIMIT 1");
        $stmtLay->execute([':lay_id' => (int)$data['trans_id_layanan']]);
        $layanan = $stmtLay->fetch();

        if (!$layanan) {
            return false;
        }

        $berat = max(1, (int)$data['trans_berat']);
        $harga = (int)$layanan['lay_harga'];
        $total = $berat * $harga;

        $sql = "UPDATE {$this->table} SET 
                trans_id_pelanggan = :pel, 
                trans_id_layanan   = :lay, 
                trans_tgl_masuk    = :tgl_masuk, 
                trans_tgl_selesai  = :tgl_selesai, 
                trans_berat        = :berat, 
                trans_total        = :total 
                WHERE trans_id = :id";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':pel'         => (int)$data['trans_id_pelanggan'],
            ':lay'         => (int)$data['trans_id_layanan'],
            ':tgl_masuk'   => $data['trans_tgl_masuk'],
            ':tgl_selesai' => $data['trans_tgl_selesai'],
            ':berat'       => $berat,
            ':total'       => $total,
            ':id'          => $id,
        ]);
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE trans_id = :id");
        return $stmt->execute([':id' => $id]);
    }

    /**
     * Tandai Kargo Sudah Sampai / Terkirim Hari Ini
     */
    public function markAsDelivered(int $id): bool {
        $today = date('Y-m-d');
        $stmt = $this->db->prepare("UPDATE {$this->table} SET trans_tgl_selesai = :today WHERE trans_id = :id");
        return $stmt->execute([':today' => $today, ':id' => $id]);
    }

    public function count(): int {
        return (int)$this->db->query("SELECT COUNT(*) FROM {$this->table}")->fetchColumn();
    }

    public function getTotalOmset(): int {
        $res = $this->db->query("SELECT SUM(trans_total) FROM {$this->table}")->fetchColumn();
        return (int)($res ?? 0);
    }

    public function getTotalBerat(): int {
        $res = $this->db->query("SELECT SUM(trans_berat) FROM {$this->table}")->fetchColumn();
        return (int)($res ?? 0);
    }
}