<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {
    public function __construct()
    {
        parent::__construct();
        // CI3: get_instance() guaranteed after bootstrap
        $CI =& get_instance();
        $this->db = $CI->db;
    }

    protected string $table = 'users';

    // ------------------------------------------------------------------
    // AUTH
    // ------------------------------------------------------------------

    public function create(array $data): int
    {
        $data['password_hash'] = password_hash($data['password'], PASSWORD_BCRYPT);
        unset($data['password']);
        $data['verify_token'] = bin2hex(random_bytes(16));
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function get_by_email(string $email): ?array
    {
        $row = $this->db->get_where($this->table, ['email' => $email, 'is_active' => 1])->row_array();
        return $row ?: NULL;
    }

    public function get_by_id(int $id): ?array
    {
        $row = $this->db->get_where($this->table, ['id' => $id])->row_array();
        return $row ?: NULL;
    }

    public function verify_password(string $plain, string $hash): bool
    {
        return password_verify($plain, $hash);
    }

    public function verify_email(string $token): bool
    {
        $this->db->where('verify_token', $token)->where('email_verified', 0);
        $user = $this->db->get($this->table)->row_array();
        if ( ! $user) return FALSE;
        $this->db->where('id', $user['id'])->update($this->table, [
            'email_verified' => 1,
            'verify_token'   => NULL,
        ]);
        return TRUE;
    }

    // ------------------------------------------------------------------
    // PROFILE
    // ------------------------------------------------------------------

    public function update(int $id, array $data): bool
    {
        if (isset($data['password'])) {
            $data['password_hash'] = password_hash($data['password'], PASSWORD_BCRYPT);
            unset($data['password']);
        }
        return $this->db->where('id', $id)->update($this->table, $data);
    }

    // ------------------------------------------------------------------
    // CREDITS
    // ------------------------------------------------------------------

    public function get_credits(int $user_id): int
    {
        $row = $this->db->select('credits')->get_where($this->table, ['id' => $user_id])->row_array();
        return (int) ($row['credits'] ?? 0);
    }

    /**
     * Add or deduct credits atomically and log the transaction.
     */
    public function adjust_credits(int $user_id, int $amount, string $type, ?int $ref_id = NULL, string $note = ''): bool
    {
        $this->db->trans_start();

        // Lock row
        $this->db->query("SELECT credits FROM users WHERE id = ? FOR UPDATE", [$user_id]);
        $current = $this->get_credits($user_id);
        $new_balance = max(0, $current + $amount);

        $this->db->where('id', $user_id)->update($this->table, ['credits' => $new_balance]);

        $this->db->insert('credit_transactions', [
            'user_id'       => $user_id,
            'amount'        => $amount,
            'type'          => $type,
            'reference_id'  => $ref_id,
            'note'          => $note,
            'balance_after' => $new_balance,
        ]);

        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    // ------------------------------------------------------------------
    // ADMIN
    // ------------------------------------------------------------------

    public function get_all(array $filters = [], int $limit = ADMIN_PER_PAGE, int $offset = 0): array
    {
        $this->db->from($this->table);
        if ( ! empty($filters['role']))   $this->db->where('role', $filters['role']);
        if ( ! empty($filters['search'])) {
            $s = $this->db->escape_like_str($filters['search']);
            $this->db->group_start()
                ->like('name', $s)
                ->or_like('email', $s)
                ->group_end();
        }
        if ( ! empty($filters['banned'])) $this->db->where('is_banned', 1);
        $this->db->limit($limit, $offset)->order_by('id', 'DESC');
        return $this->db->get()->result_array();
    }

    public function count_all(array $filters = []): int
    {
        if ( ! empty($filters['role']))   $this->db->where('role', $filters['role']);
        if ( ! empty($filters['banned'])) $this->db->where('is_banned', 1);
        return $this->db->count_all_results($this->table);
    }

    public function ban(int $id, bool $ban = TRUE): bool
    {
        return $this->db->where('id', $id)->update($this->table, ['is_banned' => (int)$ban]);
    }

    public function count_total(): int
    {
        return $this->db->count_all($this->table);
    }
}
