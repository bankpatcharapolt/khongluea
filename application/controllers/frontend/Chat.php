<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Chat extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        require_login();
        $this->load->model(['Conversation_model', 'Message_model', 'Item_model']);
    }

    // ------------------------------------------------------------------
    // INBOX
    // ------------------------------------------------------------------

    public function inbox(): void
    {
        $user          = current_user();
        $conversations = $this->Conversation_model->get_for_user($user['id']);

        $this->_render('chat/inbox', [
            'title'         => 'My Messages',
            'conversations' => $conversations,
        ]);
    }

    // ------------------------------------------------------------------
    // THREAD
    // ------------------------------------------------------------------

    public function thread(int $conv_id): void
    {
        $user = current_user();
        $conv = $this->Conversation_model->get_by_id($conv_id);

        if ( ! $conv || ($conv['buyer_id'] != $user['id'] && $conv['seller_id'] != $user['id'])) {
            show_error('Access denied.', 403);
        }

        $item     = $this->Item_model->get_by_id($conv['item_id']);
        $messages = $this->Message_model->get_thread($conv_id);

        // Mark messages as read
        $this->Message_model->mark_read($conv_id, $user['id']);

        $this->_render('chat/thread', [
            'title'    => 'Chat — ' . ($item['title'] ?? ''),
            'conv'     => $conv,
            'item'     => $item,
            'messages' => $messages,
        ]);
    }

    // ------------------------------------------------------------------
    // START CONVERSATION (from item detail page)
    // ------------------------------------------------------------------

    public function start(): void
    {
        require_login();
        $user    = current_user();
        $item_id = (int)$this->input->post('item_id');
        $item    = $this->Item_model->get_by_id($item_id);

        if ( ! $item) show_404();
        if ($item['user_id'] == $user['id']) {
            $this->session->set_flashdata('error', 'You cannot chat about your own listing.');
            redirect(site_url('items/' . $item_id));
        }

        $conv_id = $this->Conversation_model->get_or_create($item_id, $user['id'], $item['user_id']);
        redirect(site_url('chat/' . $conv_id));
    }

    // ------------------------------------------------------------------
    // SEND MESSAGE (AJAX)
    // ------------------------------------------------------------------

    public function send(): void
    {
        if ( ! $this->input->is_ajax_request()) show_error('Bad request.', 400);

        $this->_verify_csrf_header();

        $user    = current_user();
        $conv_id = (int)$this->input->post('conversation_id');
        $message = trim($this->security->xss_clean($this->input->post('message')));

        if ( ! $message || ! $conv_id) {
            $this->output->set_status_header(422)->set_content_type('application/json')
                ->set_output(json_encode(['error' => 'Message cannot be empty.']));
            return;
        }

        $conv = $this->Conversation_model->get_by_id($conv_id);
        if ( ! $conv || ($conv['buyer_id'] != $user['id'] && $conv['seller_id'] != $user['id'])) {
            $this->output->set_status_header(403)->set_content_type('application/json')
                ->set_output(json_encode(['error' => 'Forbidden.']));
            return;
        }

        $receiver_id = ($conv['buyer_id'] == $user['id']) ? $conv['seller_id'] : $conv['buyer_id'];

        $msg_id = $this->Message_model->send([
            'conversation_id' => $conv_id,
            'sender_id'       => $user['id'],
            'receiver_id'     => $receiver_id,
            'item_id'         => $conv['item_id'],
            'message'         => $message,
        ]);

        $this->Conversation_model->update_last_message($conv_id);

        $this->output->set_content_type('application/json')->set_output(json_encode([
            'success'    => TRUE,
            'message_id' => $msg_id,
            'message'    => $message,
            'sender'     => $user['name'],
            'time'       => date('H:i'),
            'csrf_token' => $this->security->get_csrf_hash(), // token ใหม่สำหรับ request ถัดไป
        ]));
    }

    // ------------------------------------------------------------------
    // POLL FOR NEW MESSAGES (AJAX long-poll)
    // ------------------------------------------------------------------

    public function poll(): void
    {
        if ( ! $this->input->is_ajax_request()) show_error('Bad request.', 400);

        $user     = current_user();
        $conv_id  = (int)$this->input->get('conversation_id');
        $last_id  = (int)$this->input->get('last_id');

        $conv = $this->Conversation_model->get_by_id($conv_id);
        if ( ! $conv || ($conv['buyer_id'] != $user['id'] && $conv['seller_id'] != $user['id'])) {
            $this->output->set_status_header(403)->set_content_type('application/json')
                ->set_output(json_encode([]));
            return;
        }

        $messages = $this->Message_model->get_since($conv_id, $last_id);
        if ($messages) {
            $this->Message_model->mark_read($conv_id, $user['id']);
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($messages));
    }

    // ------------------------------------------------------------------
    // PRIVATE
    // ------------------------------------------------------------------

    private function _verify_csrf_header(): void
    {
        $token_name  = $this->security->get_csrf_token_name();
        $token_value = $this->security->get_csrf_hash();
        
        // รับ token จาก header หรือ POST body อย่างใดอย่างหนึ่ง
        $header_val = $this->input->get_request_header('X-CSRF-Token');
        $post_val   = $this->input->post($token_name);
        
        if ($header_val !== $token_value && $post_val !== $token_value) {
            $this->output->set_status_header(403)->set_content_type('application/json')
                ->set_output(json_encode(['error' => 'CSRF token mismatch.']));
            exit;
        }
    }

    private function _render(string $view, array $data = []): void
    {
        $this->load->view('layouts/frontend_layout', array_merge($data, ['content_view' => 'frontend/' . $view]));
    }
}
