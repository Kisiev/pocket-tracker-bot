<?php

namespace App\Models;

use App\Casts\UserAction;
use App\Casts\UserSettings;
use App\ValueObjects\UserActionField;
use App\ValueObjects\UserSettingsField;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property int $id
 * @property string $fist_name
 * @property string $last_name
 * @property string $username
 * @property UserActionField $action
 * @property UserSettingsField $settings
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'fist_name',
        'last_name',
        'username',
        'action',
        'settings',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'action' => UserAction::class,
        'settings' => UserSettings::class,
    ];
}
