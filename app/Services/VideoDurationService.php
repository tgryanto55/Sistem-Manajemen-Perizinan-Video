<?php

namespace App\Services;

use CodeIgniter\I18n\Time;

/**
 * VideoDurationService
 * 
 * This service handles time-based calculations for video access.
 * It primarily manages expiration string parsing and duration formatting.
 */
class VideoDurationService
{
    /**
     * Calculate expiry time based on approval time and duration in hours and minutes.
     *
     * @param string $approvedAt The start time (usually now).
     * @param int $durationHours
     * @param int $durationMinutes
     * @return string Future timestamp in 'Y-m-d H:i:s' format.
     */
    public function calculateExpiry(string $approvedAt, int $durationHours, int $durationMinutes = 0): string
    {
        $time = Time::parse($approvedAt);
        return $time->addHours($durationHours)->addMinutes($durationMinutes)->toDateTimeString();
    }

    /**
     * Placeholder for video metadata extraction.
     * Currently returns 0 as YouTube/External URLs don't need local duration checks.
     *
     * @param string $filePath
     * @return int
     */
    public function getDuration(string $filePath): int
    {
        if (filter_var($filePath, FILTER_VALIDATE_URL)) {
            return 0; 
        }
        return 0; 
    }

    /**
     * Check if a given "expired_at" timestamp is in the past compared to right now.
     *
     * @param string $expiredAt
     * @return bool True if expired, false if still valid.
     */
    public function isExpired(string $expiredAt): bool
    {
        $expiry = Time::parse($expiredAt);
        return $expiry->isBefore(Time::now());
    }
}
