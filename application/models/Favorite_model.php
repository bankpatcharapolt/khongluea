<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Favorite_model extends CI_Model {

    protected string $table = 'favorites';

    public function toggle(int $user_id, int $item_id): string
    {
        $exists = $this->db->get_where($this->table, ['user_id' => $user_id, 'item_id' => $item_id])->row();
        if ($exists) {
            $this->db->delete($this->table, ['user_id' => $user_id, 'item_id' => $item_id]);
            return 'removed';
        }
        $this->db->insert($this->table, ['user_id' => $user_id, 'item_id' => $item_id]);
        return 'added';
    }

    public function is_favorited(int $user_id, int $item_id): bool
    {
        return (bool) $this->db->get_where($this->table, ['user_id' => $user_id, 'item_id' => $item_id])->num_rows();
    }

    public function get_for_user(int $user_id, int $limit = ITEMS_PER_PAGE, int $offset = 0): array
    {
        return $this->db->select('items.*, users.name AS seller_name,
            (SELECT image_path FROM item_images WHERE item_id=items.id AND is_primary=1 LIMIT 1) AS primary_image,
            favorites.created_at AS favorited_at')
            ->from($this->table)
            ->join('items', 'items.id = favorites.item_id')
            ->join('users', 'users.id = items.user_id')
            ->where('favorites.user_id', $user_id)
            ->where('items.status', ITEM_ACTIVE)
            ->order_by('favorites.created_at', 'DESC')
            ->limit($limit, $offset)
            ->get()->result_array();
    }
}
