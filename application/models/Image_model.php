<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Image_model extends CI_Model {

    public function upload_multiple(int $item_id, string $field = 'images'): array
    {
        $uploaded = [];
        if ( ! isset($_FILES[$field])) return $uploaded;

        $files      = $_FILES[$field];
        $file_count = count($files['name']);

        // Check existing image count
        $existing = $this->db->where('item_id', $item_id)->count_all_results('item_images');

        for ($i = 0; $i < $file_count && ($existing + count($uploaded)) < MAX_ITEM_IMAGES; $i++) {
            if ($files['error'][$i] !== UPLOAD_ERR_OK) continue;

            // Re-build $_FILES for CI upload library (expects single file)
            $_FILES['userfile'] = [
                'name'     => $files['name'][$i],
                'type'     => $files['type'][$i],
                'tmp_name' => $files['tmp_name'][$i],
                'error'    => $files['error'][$i],
                'size'     => $files['size'][$i],
            ];

            $this->load->library('upload', [
                'upload_path'   => UPLOAD_PATH,
                'allowed_types' => 'jpg|jpeg|png|webp',
                'max_size'      => MAX_UPLOAD_KB,
                'encrypt_name'  => TRUE,
            ]);

            if ($this->upload->do_upload('userfile')) {
                $info       = $this->upload->data();
                $is_primary = ($existing === 0 && count($uploaded) === 0) ? 1 : 0;
                $path       = 'uploads/items/' . $info['file_name'];

                $this->db->insert('item_images', [
                    'item_id'    => $item_id,
                    'image_path' => $path,
                    'is_primary' => $is_primary,
                    'sort_order' => $existing + count($uploaded),
                ]);

                $uploaded[] = $path;
                $existing   = 0; // prevent double primary
            }
        }

        return $uploaded;
    }

    public function delete(int $image_id, int $item_id): bool
    {
        $img = $this->db->get_where('item_images', ['id' => $image_id, 'item_id' => $item_id])->row_array();
        if ( ! $img) return FALSE;

        // Delete physical file
        $full_path = FCPATH . $img['image_path'];
        if (file_exists($full_path)) @unlink($full_path);

        $this->db->delete('item_images', ['id' => $image_id]);

        // If deleted was primary, promote next image
        if ($img['is_primary']) {
            $next = $this->db->order_by('sort_order', 'ASC')
                ->get_where('item_images', ['item_id' => $item_id])->row_array();
            if ($next) {
                $this->db->where('id', $next['id'])->update('item_images', ['is_primary' => 1]);
            }
        }
        return TRUE;
    }

    public function set_primary(int $image_id, int $item_id): bool
    {
        $this->db->where('item_id', $item_id)->update('item_images', ['is_primary' => 0]);
        return $this->db->where('id', $image_id)->where('item_id', $item_id)
            ->update('item_images', ['is_primary' => 1]);
    }
}
