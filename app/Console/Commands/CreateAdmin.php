<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

#[Signature('portfolio:admin {email? : Email address used to sign in}')]
#[Description('Create an administrator account, or reset the password of an existing one')]
class CreateAdmin extends Command
{
    public function handle(): int
    {
        $email = $this->argument('email') ?: $this->ask('Email address');
        $user = User::where('email', $email)->first();
        $name = $user?->name ?? $this->ask('Name', 'Bakunzi Bake Fiston');
        $password = $this->secret('Password (min. 12 characters)');

        $validator = Validator::make(
            ['email' => $email, 'name' => $name, 'password' => $password],
            ['email' => 'required|email', 'name' => 'required|string|max:255', 'password' => 'required|string|min:12'],
        );

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }

            return self::FAILURE;
        }

        $user ??= new User(['email' => $email]);
        $user->fill(['name' => $name, 'password' => $password]);
        $user->is_admin = true;
        $user->save();

        $this->info("Administrator {$email} is ready. Sign in at ".url('/admin/login'));

        return self::SUCCESS;
    }
}
