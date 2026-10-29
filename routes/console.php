<?php

use App\Models\User;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('travelista:admin {email : Email of an existing account}', function () {
    $user = User::where('email', strtolower($this->argument('email')))->first();

    if (! $user) {
        $this->error('Create the account first, then run this command again.');

        return 1;
    }

    $user->forceFill(['is_admin' => true])->save();
    $this->info('Admin access granted to '.$user->email.'.');

    return 0;
})->purpose('Grant an existing Travelista user access to the Filament admin panel');
