<?php

namespace App\Services;

use CodeIgniter\I18n\Time;

/**
 * Service Hitung Durasi
 * 
 * Menangani perhitungan waktu akses video.
 * Fokus utamanya adalah ngitung tanggal expired dan format waktu.
 */
class VideoDurationService
{
    /**
     * Hitung tanggal kadaluarsa berdasarkan waktu approve dan durasi yang dikasih.
     * Retun format datetime string buat disimpan ke database.
     */
    public function calculateExpiry(string $approvedAt, int $durationHours, int $durationMinutes = 0): string
    {
        $time = Time::parse($approvedAt);
        // Tambahkan jam dan menit ke waktu approve
        return $time->addHours($durationHours)->addMinutes($durationMinutes)->toDateTimeString();
    }

    /**
     * Placeholder untuk ambil durasi video (jika nanti dibutuhkan).
     * Saat ini return 0 karena kita pakai YouTube/URL Eksternal
     */
    public function getDuration(string $filePath): int
    {
        if (filter_var($filePath, FILTER_VALIDATE_URL)) {
            return 0; 
        }
        return 0; 
    }

    /**
     * Cek apakah waktu tertentu sudah lewat (expired) dibanding waktu sekarang.
     */
    public function isExpired(string $expiredAt): bool
    {
        $expiry = Time::parse($expiredAt);
        // Cek apakah $expiry < waktu sekarang
        return $expiry->isBefore(Time::now());
    }
}
