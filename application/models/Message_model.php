<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Message_model extends CI_Model {
    public function __construct()
    {
        parent::__construct();
        // CI3: get_instance() guaranteed after bootstrap
        $CI =& get_instance();
        $this->db = $CI->db;
    }

    protected string $table = 'messages';

    public function send(array $data): int
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function get_thread(int $conv_id, int $limit = MESSAGES_PER_PAGE, int $offset = 0): array
    {
        return $this->db->select('messages.*, users.name AS sender_name, users.avatar AS sender_avatar')
            ->from($this->table)
            ->join('users', 'users.id = messages.sender_id')
            ->where('messages.conversation_id', $conv_id)
            ->order_by('messages.created_at', 'ASC')
            ->limit($limit, $offset)
            ->get()->result_array();
    }

    /**
     * Fetch only messages newer than a given message ID (for AJAX polling).
     */
    public function get_since(int $conv_id, int $last_id): array
    {
        return $this->db->select('messages.*, users.name AS sender_name, users.avatar AS sender_avatar')
            ->from($this->table)
            ->join('users', 'users.id = messages.sender_id')
            ->where('messages.conversation_id', $conv_id)
            ->where('messages.id >', $last_id)
            ->order_by('messages.created_at', 'ASC')
            ->get()->result_array();
    }

    public function mark_read(int $conv_id, int $receiver_id): void
    {
        $this->db->where('conversation_id', $conv_id)
            ->where('receiver_id', $receiver_id)
            ->where('is_read', 0)
            ->update($this->table, ['is_read' => 1, 'read_at' => date('Y-m-d H:i:s')]);
    }

    public function unread_count(int $user_id): int
    {
        return (int) $this->db->where('receiver_id', $user_id)
            ->where('is_read', 0)
            ->count_all_results($this->table);
    }
}
