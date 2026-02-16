<?php

if (!function_exists('get_youtube_id')) {
    /**
     * Extracts the YouTube video ID from various URL formats.
     * Use this to get the unique string (e.g., 'dQw4w9WgXcQ') 
     * needed for embedding or thumbnail fetching.
     * 
     * @param string $url The full YouTube link.
     * @return string|null The 11-character video ID or null if not a valid YouTube link.
     */
    function get_youtube_id($url) {
        if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match)) {
            return $match[1];
        }
        return null;
    }
}

if (!function_exists('get_youtube_thumbnail')) {
    /**
     * Returns the high-resolution YouTube thumbnail URL for a given video URL.
     * This is used in the video list grid to show a preview image.
     * 
     * @param string $url The full YouTube link.
     * @return string|null The direct image URL (maxresdefault) or null if ID extraction fails.
     */
    function get_youtube_thumbnail($url) {
        $videoId = get_youtube_id($url);
        if ($videoId) {
            return "https://img.youtube.com/vi/{$videoId}/maxresdefault.jpg";
        }
        return null;
    }
}
