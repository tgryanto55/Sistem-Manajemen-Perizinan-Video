<?php

if (!function_exists('get_youtube_id')) {
    /**
     * Ambil ID YouTube dari berbagai format URL.
     * ID ini (contoh: 'dQw4w9WgXcQ') dibutuhkan buat embed atau ambil thumbnail.
     */
    function get_youtube_id($url) {
        // Regex untuk menangkap ID dari URL YouTube (baik short url maupang long url)
        if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match)) {
            return $match[1];
        }
        return null;
    }
}

if (!function_exists('get_youtube_thumbnail')) {
    /**
     * Ambil URL thumbnail resolusi tinggi dari video YouTube.
     * Dipakai di tampilan grid video sebagai preview gambar.
     */
    function get_youtube_thumbnail($url) {
        // Ambil ID dulu
        $videoId = get_youtube_id($url);
        
        // Kalau valid, return URL gambar maxresdefault
        if ($videoId) {
            return "https://img.youtube.com/vi/{$videoId}/maxresdefault.jpg";
        }
        return "https://placehold.co/600x400?text=No+Thumbnail"; // Fallback image biar gak broken
    }
}
