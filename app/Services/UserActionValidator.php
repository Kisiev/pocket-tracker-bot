<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Validator;

class UserActionValidator
{
    public function validate(array $rules, User $user): array
    {
        return Validator::make($user->action->fields, $rules)->getMessageBag()->toArray();
    }
}
