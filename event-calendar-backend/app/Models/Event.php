<?php

namespace App\Models;

use App\Enum\EventTypeEnum;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'name',
        'description',
        'datetime',
        'location',
        'type',
    ];

    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $casts = [
        'type' => EventTypeEnum::class,
        'datetime' => 'datetime:Y-m-d\TH:i:sP',
        ];
}
