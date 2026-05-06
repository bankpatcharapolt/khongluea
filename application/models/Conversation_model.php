<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Conversation_model extends CI_Model {

    protected string $table = 'conversations';

    public function get_or_create(int $item_id, int $buyer_id, int $seller_id): int
    {
        $existing = $this->db->get_where($this->table, [
            'item_id'   => $item_id,
            'buyer_id'  => $buyer_id,
            'seller_id' => $seller_id,
        ])->row_array();

        if ($existing) return (int)$existing['id'];

        $this->db->insert($this->table, [
            'item_id'   => $item_id,
            'buyer_id'  => $buyer_id,
            'seller_id' => $seller_id,
        ]);
        return $this->db->insert_id();
    }

    public function get_by_id(int $id): ?array
    {
        $row = $this->db->get_where($this->table, ['id' => $id])->row_array();
        return $row ?: NULL;
    }

    public function get_for_user(int $user_id): array
    {
        $uid = (int)$user_id;
        $sql = "SELECT conversations.*,
                items.title AS item_title,
                (SELECT image_path FROM item_images WHERE item_id=items.id AND is_primary=1 LIMIT 1) AS item_image,
                buyer.name   AS buyer_name,  buyer.avatar  AS buyer_avatar,
                seller.name  AS seller_name, seller.avatar AS seller_avatar,
                (SELECT message FROM messages WHERE conversation_id=conversations.id ORDER BY created_at DESC LIMIT 1) AS last_message,
                (SELECT COUNT(*) FROM messages WHERE conversation_id=conversations.id AND receiver_id={$uid} AND is_read=0) AS unread_count
                FROM conversations
                JOIN items          ON items.id   = conversations.item_id
                JOIN users AS buyer  ON buyer.id  = conversations.buyer_id
                JOIN users AS seller ON seller.id = conversations.seller_id
                WHERE conversations.buyer_id = {$uid} OR conversations.seller_id = {$uid}
                ORDER BY conversations.last_message_at DESC";

        return $this->db->query($sql)->result_array();
    }

    public function update_last_message(int $conv_id): void
    {
        $this->db->where('id', $conv_id)->update($this->table, [
            'last_message_at' => date('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Total unread messages count across all conversations for a user.
     */
    public function total_unread(int $user_id): int
    {
        return (int) $this->db->where('receiver_id', $user_id)
            ->where('is_read', 0)
            ->count_all_results('messages');
    }
}
