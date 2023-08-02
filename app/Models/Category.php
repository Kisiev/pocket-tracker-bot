<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';

    protected $fillable = [
        'id',
        'user_id',
        'parent_id',
        'title',
    ];

    public function charges(): HasMany
    {
        return $this->hasMany(Charge::class);
    }
}
