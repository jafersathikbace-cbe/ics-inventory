<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UsersSeeder extends Seeder
{
    public function run(): void
    {
        $password = env('SEED_DEFAULT_PASSWORD');

        if (blank($password)) {
            throw new \RuntimeException('SEED_DEFAULT_PASSWORD must be configured before seeding demo users.');
        }
        // SUPER ADMIN (Owner)
        $owner = User::updateOrCreate(
            ['email' => 'owner@itcore.local'],
            [
                'name' => 'Owner (Super Admin)',
                'password' => Hash::make($password),
                'is_owner' => true,
                'telegram_chat_id' => null,
                'telegram_is_group' => false,
                'ownership_transferred_at' => null,
            ]
        );
        $owner->syncRoles(['Super Admin']);

        // ADMIN
        $admin = User::updateOrCreate(
            ['email' => 'admin@itcore.local'],
            [
                'name' => 'Admin User',
                'password' => Hash::make($password),
                'is_owner' => false,
                'telegram_chat_id' => null,
                'telegram_is_group' => false,
                'ownership_transferred_at' => null,
            ]
        );
        $admin->syncRoles(['Admin']);

        // OPERATOR
        $operator = User::updateOrCreate(
            ['email' => 'operator@itcore.local'],
            [
                'name' => 'Operator User',
                'password' => Hash::make($password),
                'is_owner' => false,
                'telegram_chat_id' => null,
                'telegram_is_group' => false,
                'ownership_transferred_at' => null,
            ]
        );
        $operator->syncRoles(['Operator']);
    }
}
