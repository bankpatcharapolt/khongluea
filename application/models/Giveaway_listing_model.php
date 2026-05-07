<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Giveaway_listing_model extends CI_Model {

    protected string $table      = 'giveaway_listings';
    protected string $img_table  = 'giveaway_listing_images';

    // ── CREATE ────────────────────────────────────────────
    public function create(array $data): int
    {
        $data['quantity_left'] = $data['quantity_total'];
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    // ── READ ──────────────────────────────────────────────
    public function get_by_id(int $id): ?array
    {
        $row = $this->db
            ->select('gl.*, u.name AS donor_name, u.business_name, u.business_type,
                      u.city AS donor_city, u.avatar AS donor_avatar,
                      c.name AS category_name,
                      (SELECT image_path FROM giveaway_listing_images
                       WHERE listing_id=gl.id AND is_primary=1 LIMIT 1) AS primary_image')
            ->from("{$this->table} gl")
            ->join('users u',      'u.id = gl.donor_user_id')
            ->join('categories c', 'c.id = gl.category_id', 'left')
            ->where('gl.id', $id)
            ->where('gl.status !=', 'deleted')
            ->get()->row_array();

        if ($row) {
            $row['images'] = $this->get_images($id);
        }
        return $row ?: NULL;
    }

    public function get_images(int $listing_id): array
    {
        return $this->db
            ->order_by('is_primary', 'DESC')
            ->order_by('sort_order', 'ASC')
            ->get_where($this->img_table, ['listing_id' => $listing_id])
            ->result_array();
    }

    // ── LIST / BROWSE ─────────────────────────────────────
    public function get_active(array $filters = [], int $limit = 20, int $offset = 0): array
    {
        $this->db
            ->select('gl.*, u.name AS donor_name, u.business_name,
                      (SELECT image_path FROM giveaway_listing_images
                       WHERE listing_id=gl.id AND is_primary=1 LIMIT 1) AS primary_image')
            ->from("{$this->table} gl")
            ->join('users u', 'u.id = gl.donor_user_id')
            ->where('gl.status', 'active')
            ->where('gl.quantity_left >', 0)
            ->where('gl.pickup_end >', date('Y-m-d H:i:s'));

        if (!empty($filters['category_id'])) {
            $this->db->where('gl.category_id', (int)$filters['category_id']);
        }

        if (!empty($filters['search'])) {
            $s = $this->db->escape_like_str($filters['search']);
            $this->db->group_start()
                ->like('gl.title', $s)
                ->or_like('gl.description', $s)
                ->group_end();
        }

        // Haversine distance filter
        if (!empty($filters['lat']) && !empty($filters['lng']) && !empty($filters['radius_km'])) {
            $lat = (float)$filters['lat'];
            $lng = (float)$filters['lng'];
            $km  = (float)$filters['radius_km'];
            $this->db->having('distance_km <=', $km);
            $this->db->select(
                "(6371 * ACOS(COS(RADIANS({$lat})) * COS(RADIANS(gl.pickup_lat))
                  * COS(RADIANS(gl.pickup_lng) - RADIANS({$lng}))
                  + SIN(RADIANS({$lat})) * SIN(RADIANS(gl.pickup_lat)))) AS distance_km"
            );
            $this->db->order_by('distance_km', 'ASC');
        } else {
            $this->db->order_by('gl.created_at', 'DESC');
        }

        return $this->db->limit($limit, $offset)->get()->result_array();
    }

    public function count_active(array $filters = []): int
    {
        $this->db
            ->from("{$this->table} gl")
            ->where('gl.status', 'active')
            ->where('gl.quantity_left >', 0)
            ->where('gl.pickup_end >', date('Y-m-d H:i:s'));

        if (!empty($filters['category_id'])) {
            $this->db->where('gl.category_id', (int)$filters['category_id']);
        }
        if (!empty($filters['search'])) {
            $s = $this->db->escape_like_str($filters['search']);
            $this->db->group_start()
                ->like('gl.title', $s)
                ->or_like('gl.description', $s)
                ->group_end();
        }
        return $this->db->count_all_results();
    }

    // ── DONOR OWN LISTINGS ────────────────────────────────
    public function get_by_donor(int $donor_id, int $limit = 20, int $offset = 0): array
    {
        return $this->db
            ->select('gl.*,
                      (SELECT image_path FROM giveaway_listing_images
                       WHERE listing_id=gl.id AND is_primary=1 LIMIT 1) AS primary_image,
                      (SELECT COUNT(*) FROM giveaway_reservations
                       WHERE listing_id=gl.id AND status IN ("pending","confirmed")) AS reservation_count')
            ->from("{$this->table} gl")
            ->where('gl.donor_user_id', $donor_id)
            ->where('gl.status !=', 'deleted')
            ->order_by('gl.created_at', 'DESC')
            ->limit($limit, $offset)
            ->get()->result_array();
    }

    // ── UPDATE ────────────────────────────────────────────
    public function update(int $id, int $donor_id, array $data): bool
    {
        return $this->db
            ->where('id', $id)
            ->where('donor_user_id', $donor_id)
            ->update($this->table, $data);
    }

    public function soft_delete(int $id, int $donor_id): bool
    {
        return $this->db
            ->where('id', $id)
            ->where('donor_user_id', $donor_id)
            ->update($this->table, ['status' => 'deleted']);
    }

    public function increment_views(int $id): void
    {
        $this->db->where('id', $id)
            ->set('view_count', 'view_count+1', FALSE)
            ->update($this->table);
    }

    // ── IMAGE MANAGEMENT ──────────────────────────────────
    public function add_image(int $listing_id, string $path, bool $is_primary = FALSE): int
    {
        if ($is_primary) {
            $this->db->where('listing_id', $listing_id)
                ->update($this->img_table, ['is_primary' => 0]);
        }
        $this->db->insert($this->img_table, [
            'listing_id' => $listing_id,
            'image_path' => $path,
            'is_primary' => $is_primary ? 1 : 0,
        ]);
        return $this->db->insert_id();
    }

    public function delete_image(int $image_id, int $listing_id): ?string
    {
        $img = $this->db->get_where($this->img_table,
            ['id' => $image_id, 'listing_id' => $listing_id])->row_array();
        if (!$img) return NULL;
        $this->db->delete($this->img_table, ['id' => $image_id]);
        if ($img['is_primary']) {
            $next = $this->db->order_by('sort_order', 'ASC')
                ->get_where($this->img_table, ['listing_id' => $listing_id])->row_array();
            if ($next) {
                $this->db->where('id', $next['id'])
                    ->update($this->img_table, ['is_primary' => 1]);
            }
        }
        return $img['image_path'];
    }

    // ── DONOR DAILY COUNT (spam prevention) ───────────────
    public function count_donor_active_today(int $donor_id): int
    {
        return (int)$this->db
            ->where('donor_user_id', $donor_id)
            ->where('DATE(created_at)', date('Y-m-d'))
            ->where_in('status', ['active', 'paused'])
            ->count_all_results($this->table);
    }
}
