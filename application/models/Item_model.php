<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Item_model extends CI_Model {

    protected string $table = 'items';

    // ------------------------------------------------------------------
    // CREATE / UPDATE / DELETE
    // ------------------------------------------------------------------

    public function create(array $data): int
    {
        $data['is_free'] = ($data['price'] <= 0) ? 1 : 0;
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function update(int $id, int $user_id, array $data): bool
    {
        if (isset($data['price'])) {
            $data['is_free'] = ($data['price'] <= 0) ? 1 : 0;
        }
        return $this->db->where('id', $id)->where('user_id', $user_id)->update($this->table, $data);
    }

    public function soft_delete(int $id, int $user_id): bool
    {
        return $this->db->where('id', $id)->where('user_id', $user_id)
            ->update($this->table, ['status' => ITEM_DELETED]);
    }

    // ------------------------------------------------------------------
    // READ
    // ------------------------------------------------------------------

    public function get_by_id(int $id): ?array
    {
        $this->db->select('items.*, users.name AS seller_name, users.avatar AS seller_avatar,
                           users.city AS seller_city, users.created_at AS seller_since,
                           categories.name AS category_name, categories.slug AS category_slug')
            ->from($this->table)
            ->join('users',      'users.id = items.user_id')
            ->join('categories', 'categories.id = items.category_id')
            ->where('items.id', $id)
            ->where('items.status !=', ITEM_DELETED);
        $row = $this->db->get()->row_array();
        if ($row) {
            $row['images'] = $this->get_images($id);
        }
        return $row ?: NULL;
    }

    public function get_images(int $item_id): array
    {
        return $this->db->order_by('is_primary', 'DESC')->order_by('sort_order', 'ASC')
            ->get_where('item_images', ['item_id' => $item_id])->result_array();
    }

    public function increment_views(int $id): void
    {
        $this->db->where('id', $id)->set('view_count', 'view_count+1', FALSE)->update($this->table);
    }

    // ------------------------------------------------------------------
    // LIST / SEARCH / FILTER
    // ------------------------------------------------------------------

    private function _base_query(array $filters = []): void
    {
        $this->db->select('items.*, users.name AS seller_name,
            (SELECT image_path FROM item_images WHERE item_id=items.id AND is_primary=1 LIMIT 1) AS primary_image')
            ->from($this->table)
            ->join('users', 'users.id = items.user_id')
            ->where('items.status', ITEM_ACTIVE)
            ->where('users.is_banned', 0);

        if ( ! empty($filters['category_id'])) {
            $this->db->where('items.category_id', (int)$filters['category_id']);
        }
        if (isset($filters['is_free']) && $filters['is_free'] !== '') {
            $this->db->where('items.is_free', (int)$filters['is_free']);
        }
        if ( ! empty($filters['condition'])) {
            $this->db->where('items.condition', $filters['condition']);
        }
        if ( ! empty($filters['min_price'])) {
            $this->db->where('items.price >=', (float)$filters['min_price']);
        }
        if ( ! empty($filters['max_price'])) {
            $this->db->where('items.price <=', (float)$filters['max_price']);
        }
        if ( ! empty($filters['search'])) {
            $s = $this->db->escape_like_str($filters['search']);
            $this->db->group_start()
                ->like('items.title', $s)
                ->or_like('items.description', $s)
                ->group_end();
        }
        if ( ! empty($filters['user_id'])) {
            $this->db->where('items.user_id', (int)$filters['user_id']);
        }
    }

    public function get_list(array $filters = [], int $limit = ITEMS_PER_PAGE, int $offset = 0): array
    {
        $this->_base_query($filters);

        // Sorting: bumped items first, then by date
        $sort = $filters['sort'] ?? 'newest';
        switch ($sort) {
            case 'price_asc':  $this->db->order_by('items.price', 'ASC'); break;
            case 'price_desc': $this->db->order_by('items.price', 'DESC'); break;
            default:
                $this->db->order_by('items.is_bumped', 'DESC')
                         ->order_by('items.is_highlighted', 'DESC')
                         ->order_by('items.created_at', 'DESC');
        }

        return $this->db->limit($limit, $offset)->get()->result_array();
    }

    public function count_list(array $filters = []): int
    {
        $this->_base_query($filters);
        return $this->db->count_all_results();
    }

    public function get_featured(int $limit = 8): array
    {
        return $this->db->select('items.*, users.name AS seller_name,
            (SELECT image_path FROM item_images WHERE item_id=items.id AND is_primary=1 LIMIT 1) AS primary_image')
            ->from($this->table)
            ->join('users', 'users.id = items.user_id')
            ->where('items.status', ITEM_ACTIVE)
            ->where('items.is_featured', 1)
            ->where('users.is_banned', 0)
            ->order_by('items.created_at', 'DESC')
            ->limit($limit)->get()->result_array();
    }

    public function get_recent(int $limit = 12): array
    {
        return $this->db->select('items.*, users.name AS seller_name,
            (SELECT image_path FROM item_images WHERE item_id=items.id AND is_primary=1 LIMIT 1) AS primary_image')
            ->from($this->table)
            ->join('users', 'users.id = items.user_id')
            ->where('items.status', ITEM_ACTIVE)
            ->where('users.is_banned', 0)
            ->order_by('items.created_at', 'DESC')
            ->limit($limit)->get()->result_array();
    }

    // ------------------------------------------------------------------
    // PREMIUM FEATURES
    // ------------------------------------------------------------------

    public function bump(int $id, int $user_id): bool
    {
        return $this->db->where('id', $id)->where('user_id', $user_id)
            ->update($this->table, ['is_bumped' => 1, 'bumped_at' => date('Y-m-d H:i:s')]);
    }

    public function highlight(int $id, int $user_id): bool
    {
        return $this->db->where('id', $id)->where('user_id', $user_id)
            ->update($this->table, ['is_highlighted' => 1]);
    }

    // ------------------------------------------------------------------
    // ADMIN
    // ------------------------------------------------------------------

    public function admin_get_all(array $filters = [], int $limit = ADMIN_PER_PAGE, int $offset = 0): array
    {
        $this->db->select('items.*, users.name AS seller_name, categories.name AS category_name')
            ->from($this->table)
            ->join('users',      'users.id = items.user_id')
            ->join('categories', 'categories.id = items.category_id');

        if ( ! empty($filters['status']))   $this->db->where('items.status', $filters['status']);
        if ( ! empty($filters['search'])) {
            $s = $this->db->escape_like_str($filters['search']);
            $this->db->group_start()->like('items.title', $s)->group_end();
        }
        $this->db->order_by('items.created_at', 'DESC')->limit($limit, $offset);
        return $this->db->get()->result_array();
    }

    public function admin_count(array $filters = []): int
    {
        if ( ! empty($filters['status'])) $this->db->where('items.status', $filters['status']);
        $this->db->from($this->table)
            ->join('users',      'users.id = items.user_id')
            ->join('categories', 'categories.id = items.category_id');
        return $this->db->count_all_results();
    }

    public function admin_update(int $id, array $data): bool
    {
        return $this->db->where('id', $id)->update($this->table, $data);
    }

    public function count_total(): int
    {
        return $this->db->count_all($this->table);
    }

    public function count_active(): int
    {
        return $this->db->where('status', ITEM_ACTIVE)->count_all_results($this->table);
    }

    // ------------------------------------------------------------------
    // USER LISTING COUNT (for free tier limit check)
    // ------------------------------------------------------------------

    public function count_active_by_user(int $user_id): int
    {
        return $this->db->where('user_id', $user_id)
            ->where('status', ITEM_ACTIVE)
            ->count_all_results($this->table);
    }
}
