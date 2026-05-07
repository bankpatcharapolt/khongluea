<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Image_model extends CI_Model {

    /**
     * Upload หลายไฟล์ด้วย native PHP move_uploaded_file()
     * หลีกเลี่ยงปัญหา CI3 upload library บน Windows
     */
    public function upload_multiple(int $item_id, string $field = 'images'): array
    {
        $uploaded = [];

        if (empty($_FILES[$field]['name'][0])) {
            return $uploaded;
        }

        $files = $_FILES[$field];
        $count = count($files['name']);

        // สร้าง path แบบ absolute — รองรับ Windows และ Linux
        $upload_dir = rtrim(str_replace('\\', '/', FCPATH), '/') . '/uploads/items/';

        // สร้าง folder ถ้ายังไม่มี
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, TRUE);
        }

        // ตรวจสิทธิ์ write
        if (!is_writable($upload_dir)) {
            log_message('error', 'Image_model: upload_dir not writable: ' . $upload_dir);
            return $uploaded;
        }

        // นับรูปที่มีอยู่
        $existing = (int)$this->db->where('item_id', $item_id)
            ->count_all_results('item_images');

        $allowed_ext = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
        $max_size_bytes = MAX_UPLOAD_KB * 1024;

        for ($i = 0; $i < $count; $i++) {

            if (($existing + count($uploaded)) >= MAX_ITEM_IMAGES) break;

            // ข้ามถ้ามี error หรือไม่มีไฟล์
            if ($files['error'][$i] !== UPLOAD_ERR_OK) {
                log_message('error', 'Image_model: file error code ' . $files['error'][$i] . ' for ' . $files['name'][$i]);
                continue;
            }
            if (empty($files['name'][$i]) || empty($files['tmp_name'][$i])) continue;

            // ตรวจ file size
            if ($files['size'][$i] > $max_size_bytes) {
                log_message('error', 'Image_model: file too large: ' . $files['size'][$i] . ' bytes');
                continue;
            }

            // ตรวจ extension
            $orig_name = $files['name'][$i];
            $ext = strtolower(pathinfo($orig_name, PATHINFO_EXTENSION));
            if (!in_array($ext, $allowed_ext)) {
                log_message('error', 'Image_model: invalid extension: ' . $ext);
                continue;
            }

            // ตรวจ MIME type จริงๆ ด้วย finfo
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime  = finfo_file($finfo, $files['tmp_name'][$i]);
            finfo_close($finfo);
            $allowed_mime = ['image/jpeg','image/png','image/webp','image/gif'];
            if (!in_array($mime, $allowed_mime)) {
                log_message('error', 'Image_model: invalid MIME: ' . $mime);
                continue;
            }

            // สร้างชื่อไฟล์ unique
            $new_name = md5(uniqid(mt_rand(), TRUE)) . '.' . $ext;
            $dest     = $upload_dir . $new_name;

            // move_uploaded_file — native PHP, ไม่มีปัญหา Windows
            if (move_uploaded_file($files['tmp_name'][$i], $dest)) {

                $is_primary = ($existing === 0 && count($uploaded) === 0) ? 1 : 0;
                $db_path    = 'uploads/items/' . $new_name;

                $this->db->insert('item_images', [
                    'item_id'    => $item_id,
                    'image_path' => $db_path,
                    'is_primary' => $is_primary,
                    'sort_order' => $existing + count($uploaded),
                ]);

                $uploaded[] = $db_path;
                log_message('debug', 'Image_model: uploaded ' . $db_path);

            } else {
                log_message('error', 'Image_model: move_uploaded_file failed for ' . $orig_name . ' → ' . $dest);
            }
        }

        return $uploaded;
    }

    // ───── DELETE ──────────────────────────────────────
    public function delete(int $image_id, int $item_id): bool
    {
        $img = $this->db->get_where('item_images', [
            'id'      => $image_id,
            'item_id' => $item_id,
        ])->row_array();

        if (!$img) return FALSE;

        // ลบไฟล์จริง
        $full = rtrim(str_replace('\\', '/', FCPATH), '/') . '/' . $img['image_path'];
        if (file_exists($full)) @unlink($full);

        $this->db->delete('item_images', ['id' => $image_id]);

        if ($img['is_primary']) {
            $next = $this->db->order_by('sort_order', 'ASC')
                ->get_where('item_images', ['item_id' => $item_id])->row_array();
            if ($next) {
                $this->db->where('id', $next['id'])
                    ->update('item_images', ['is_primary' => 1]);
            }
        }
        return TRUE;
    }

    // ───── SET PRIMARY ─────────────────────────────────
    public function set_primary(int $image_id, int $item_id): bool
    {
        $this->db->where('item_id', $item_id)->update('item_images', ['is_primary' => 0]);
        return $this->db->where('id', $image_id)->where('item_id', $item_id)
            ->update('item_images', ['is_primary' => 1]);
    }
}
