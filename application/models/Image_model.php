<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Image_model extends CI_Model {

    public function upload_multiple(int $item_id, string $field = 'images'): array
    {
        $uploaded = [];

        // ตรวจว่ามีไฟล์ส่งมาจริงไหม
        if (empty($_FILES[$field]['name'][0])) return $uploaded;

        $files      = $_FILES[$field];
        $file_count = count($files['name']);

        // สร้าง upload folder ถ้ายังไม่มี
        $upload_path = FCPATH . 'uploads' . DIRECTORY_SEPARATOR . 'items' . DIRECTORY_SEPARATOR;
        if ( ! is_dir($upload_path)) {
            mkdir($upload_path, 0755, TRUE);
        }

        // นับรูปที่มีอยู่แล้ว
        $existing_count = (int)$this->db
            ->where('item_id', $item_id)
            ->count_all_results('item_images');

        // โหลด upload library ครั้งเดียว
        $upload_config = [
            'upload_path'   => $upload_path,
            'allowed_types' => 'jpg|jpeg|png|webp|gif',
            'max_size'      => MAX_UPLOAD_KB,
            'encrypt_name'  => TRUE,
            'remove_spaces' => TRUE,
        ];

        // โหลด library ครั้งแรก
        $this->load->library('upload', $upload_config);

        for ($i = 0; $i < $file_count; $i++) {

            // หยุดถ้าเกิน limit
            if (($existing_count + count($uploaded)) >= MAX_ITEM_IMAGES) break;

            // ข้ามไฟล์ที่มี error
            if ($files['error'][$i] !== UPLOAD_ERR_OK) continue;
            if (empty($files['name'][$i])) continue;

            // Re-initialize สำหรับแต่ละไฟล์ (สำคัญมากสำหรับ CI3 loop)
            $this->upload->initialize($upload_config);

            // สร้าง $_FILES['userfile'] สำหรับ CI upload library
            $_FILES['userfile'] = [
                'name'     => $files['name'][$i],
                'type'     => $files['type'][$i],
                'tmp_name' => $files['tmp_name'][$i],
                'error'    => $files['error'][$i],
                'size'     => $files['size'][$i],
            ];

            if ($this->upload->do_upload('userfile')) {
                $info = $this->upload->data();

                // รูปแรกสุดเป็น primary
                $is_primary = ($existing_count === 0 && count($uploaded) === 0) ? 1 : 0;

                // path ที่บันทึกใน DB (relative, ใช้ / เสมอ)
                $db_path = 'uploads/items/' . $info['file_name'];

                $this->db->insert('item_images', [
                    'item_id'    => $item_id,
                    'image_path' => $db_path,
                    'is_primary' => $is_primary,
                    'sort_order' => $existing_count + count($uploaded),
                ]);

                $uploaded[] = $db_path;

            } else {
                // Log upload error for debugging
                log_message('error', 'Image upload error: ' . $this->upload->display_errors('', ''));
            }
        }

        return $uploaded;
    }

    // ───── DELETE ─────────────────────────────────
    public function delete(int $image_id, int $item_id): bool
    {
        $img = $this->db->get_where('item_images', [
            'id'      => $image_id,
            'item_id' => $item_id,
        ])->row_array();

        if ( ! $img) return FALSE;

        // ลบไฟล์จริง
        $full_path = FCPATH . str_replace('/', DIRECTORY_SEPARATOR, $img['image_path']);
        if (file_exists($full_path)) {
            @unlink($full_path);
        }

        $this->db->delete('item_images', ['id' => $image_id]);

        // ถ้าลบ primary → เลื่อนรูปถัดไปเป็น primary แทน
        if ($img['is_primary']) {
            $next = $this->db
                ->order_by('sort_order', 'ASC')
                ->get_where('item_images', ['item_id' => $item_id])
                ->row_array();
            if ($next) {
                $this->db->where('id', $next['id'])
                    ->update('item_images', ['is_primary' => 1]);
            }
        }
        return TRUE;
    }

    // ───── SET PRIMARY ─────────────────────────────
    public function set_primary(int $image_id, int $item_id): bool
    {
        $this->db->where('item_id', $item_id)
            ->update('item_images', ['is_primary' => 0]);

        return $this->db->where('id', $image_id)
            ->where('item_id', $item_id)
            ->update('item_images', ['is_primary' => 1]);
    }
}
