<?php

namespace Modules\Logging\Models;

use Illuminate\Database\Eloquent\Model;

class Logging extends Model
{
    protected $table = 'telegram_log';
    protected $casts = [
        'message' => 'array',
    ];
    protected $fillable = [
        'user_id',
        'message',
        'is_bot',
    ];
}
