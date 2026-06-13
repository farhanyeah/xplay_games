<?php

namespace App\Helpers;

use App\Models\ChecksheetHeader;
use Carbon\Carbon;

class ChecksheetHelper
{
    public static function generateChecksum($date = null)
    {
        $date = $date ?? Carbon::now();
        $dateStr = $date->format('Ymd');
        
        $lastHeader = ChecksheetHeader::whereDate('date', $date->toDateString())
            ->orderBy('id', 'desc')
            ->first();
        
        $sequence = 1;
        if ($lastHeader) {
            $lastSeq = (int) substr($lastHeader->checksum, -3);
            $sequence = $lastSeq + 1;
        }
        
        return 'CS-' . $dateStr . '-' . str_pad($sequence, 3, '0', STR_PAD_LEFT);
    }

    public static function detectShift()
    {
        $hour = (int) Carbon::now()->format('H');
        
        // Shift Malam: 18:00 - 03:00
        if ($hour >= 18 || $hour < 10) {
            return 'malam';
        }
        
        // Shift Pagi: 10:00 - 18:30
        return 'pagi';
    }

    public static function isBiweeklySchedule($date = null)
    {
        $date = $date ?? Carbon::now();
        $day = (int) $date->format('d');
        
        return in_array($day, [1, 15]);
    }

    public static function isMonthlySchedule($date = null)
    {
        $date = $date ?? Carbon::now();
        $day = (int) $date->format('d');
        
        return $day === 1;
    }

    public static function getNextBiweeklyDate($date = null)
    {
        $date = $date ?? Carbon::now();
        
        if ((int) $date->format('d') < 15) {
            return $date->copy()->day(15);
        }
        
        return $date->copy()->addMonth()->day(1);
    }

    public static function getNextMonthlyDate($date = null)
    {
        $date = $date ?? Carbon::now();
        
        return $date->copy()->addMonth()->day(1);
    }

    public static function isItemLocked($item, $currentDate = null)
    {
        $date = $currentDate ?? Carbon::now();
        
        switch ($item->frequency) {
            case 'biweekly':
                return !self::isBiweeklySchedule($date);
            case 'monthly':
                return !self::isMonthlySchedule($date);
            default:
                return false;
        }
    }
}