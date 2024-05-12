<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QueryStat extends Model
{
    use HasFactory;

    public float $duration;
    public int $requestTime;
    public string $language;
}
