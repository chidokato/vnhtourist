<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TourOption extends Model
{
    use HasFactory;

    public const GROUP_TRANSPORT = 'transport';
    public const GROUP_LOCATION = 'location';
    public const GROUP_DEPARTURE_DATE = 'departure_date';

    protected $fillable = [
        'group_key',
        'name',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public static function groups(): array
    {
        return [
            self::GROUP_TRANSPORT => 'Phuong tien',
            self::GROUP_LOCATION => 'Dia diem',
            self::GROUP_DEPARTURE_DATE => 'Ngay khoi hanh',
        ];
    }
}
