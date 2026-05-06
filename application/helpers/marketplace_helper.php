<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if ( ! function_exists('format_price')) {
    function format_price(float $price, string $currency = '฿'): string {
        if ($price <= 0) {
            return '<span class="badge-free">แจกฟรี!</span>';
        }
        // แสดงทศนิยมเฉพาะเมื่อมีสตางค์ เช่น 500 → ฿500, 499.50 → ฿499.50
        $formatted = (floor($price) == $price)
            ? number_format((int)$price)
            : number_format($price, 2);
        return '<span class="fw-800 text-primary">' . $currency . $formatted . '</span>';
    }
}

if ( ! function_exists('time_ago')) {
    function time_ago(string $datetime): string {
        $now  = new DateTime;
        $past = new DateTime($datetime);
        $diff = $now->diff($past);
        if ($diff->y > 0) return $diff->y . 'y ago';
        if ($diff->m > 0) return $diff->m . 'mo ago';
        if ($diff->d > 0) return $diff->d . 'd ago';
        if ($diff->h > 0) return $diff->h . 'h ago';
        if ($diff->i > 0) return $diff->i . 'min ago';
        return 'Just now';
    }
}

if ( ! function_exists('truncate_text')) {
    function truncate_text(string $text, int $limit = 120): string {
        return mb_strlen($text) > $limit
            ? mb_substr($text, 0, $limit) . '…'
            : $text;
    }
}

if ( ! function_exists('item_condition_badge')) {
    function item_condition_badge(string $condition): string {
        $map = [
            'new'      => 'success',
            'like_new' => 'primary',
            'good'     => 'info',
            'fair'     => 'warning',
            'poor'     => 'danger',
        ];
        $color = $map[$condition] ?? 'secondary';
        $label = ucfirst(str_replace('_', ' ', $condition));
        return "<span class=\"badge bg-{$color}\">{$label}</span>";
    }
}

if ( ! function_exists('csrf_meta_tag')) {
    /**
     * Outputs a <meta> tag with the CSRF token.
     * Place in your layout <head>.
     * JS reads: document.querySelector('meta[name="csrf-token"]').content
     */
    function csrf_meta_tag(): string {
        $CI =& get_instance();
        $token_name  = $CI->security->get_csrf_token_name();
        $token_value = $CI->security->get_csrf_hash();
        return "<meta name=\"csrf-token\" content=\"{$token_value}\" data-name=\"{$token_name}\">";
    }
}

if ( ! function_exists('item_status_badge')) {
    function item_status_badge(string $status): string {
        $map = [
            'active'   => ['success', 'Active'],
            'reserved' => ['warning', 'Reserved'],
            'sold'     => ['secondary', 'Sold'],
            'deleted'  => ['danger', 'Deleted'],
        ];
        [$color, $label] = $map[$status] ?? ['secondary', ucfirst($status)];
        return "<span class=\"badge bg-{$color}\">{$label}</span>";
    }
}
