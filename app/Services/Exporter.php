<?php

namespace App\Services;

use App\Models\User;

interface Exporter
{
    public function export(User $user): void;
}
