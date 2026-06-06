<?php

namespace App\Support;

use Illuminate\Support\Facades\DB;

class SimrsNumber
{
    public static function medicalRecord(): string
    {
        $next = (int) DB::table('patients')->count() + 1;

        return 'RM-' . str_pad((string) $next, 8, '0', STR_PAD_LEFT);
    }

    public static function daily(string $prefix, string $table, string $column): string
    {
        $date = now()->format('Ymd');
        $count = DB::table($table)
            ->where($column, 'like', "{$prefix}-{$date}-%")
            ->count() + 1;

        return "{$prefix}-{$date}-" . str_pad((string) $count, 5, '0', STR_PAD_LEFT);
    }
}
