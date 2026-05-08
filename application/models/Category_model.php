<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Category_model extends CI_Model {
    public function __construct()
    {
        parent::__construct();
        // CI3: get_instance() guaranteed after bootstrap
        $CI =& get_instance();
        $this->db = $CI->db;
    }

    protected string $table = 'categories';

    public function get_all_active(): array
    {
        return $this->db->where('is_active', 1)->order_by('sort_order', 'ASC')->get($this->table)->result_array();
    }

    public function get_by_id(int $id): ?array
    {
        $row = $this->db->get_where($this->table, ['id' => $id])->row_array();
        return $row ?: NULL;
    }

    public function get_by_slug(string $slug): ?array
    {
        $row = $this->db->get_where($this->table, ['slug' => $slug, 'is_active' => 1])->row_array();
        return $row ?: NULL;
    }

    public function create(array $data): int
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function update(int $id, array $data): bool
    {
        return $this->db->where('id', $id)->update($this->table, $data);
    }

    public function delete(int $id): bool
    {
        return $this->db->where('id', $id)->update($this->table, ['is_active' => 0]);
    }

    public function get_all_admin(): array
    {
        return $this->db->order_by('sort_order', 'ASC')->get($this->table)->result_array();
    }
}
