<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report_model extends CI_Model {

    protected string $table = 'reports';

    public function create(array $data): int
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function get_all(array $filters = [], int $limit = ADMIN_PER_PAGE, int $offset = 0): array
    {
        $this->db->select('reports.*, 
            reporter.name AS reporter_name,
            reported.name AS reported_user_name,
            items.title AS item_title')
            ->from($this->table)
            ->join('users AS reporter', 'reporter.id = reports.reporter_id', 'left')
            ->join('users AS reported', 'reported.id = reports.reported_user_id', 'left')
            ->join('items',            'items.id = reports.item_id', 'left');

        if ( ! empty($filters['status'])) $this->db->where('reports.status', $filters['status']);

        $this->db->order_by('reports.created_at', 'DESC')->limit($limit, $offset);
        return $this->db->get()->result_array();
    }

    public function count_pending(): int
    {
        return (int) $this->db->where('status', 'pending')->count_all_results($this->table);
    }

    public function update_status(int $id, string $status, int $admin_id, string $note = ''): bool
    {
        return $this->db->where('id', $id)->update($this->table, [
            'status'      => $status,
            'admin_note'  => $note,
            'reviewed_by' => $admin_id,
            'reviewed_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
