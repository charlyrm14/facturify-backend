<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate([
            'email' => 'c.iramosm90@gmail.com'
        ], [
            'name' => 'Carlos I.',
            'last_name' => 'Ramos Morales',
            'email' => 'c.iramosm90@gmail.com',
            'password' => 'Ch@rlyrm07'
        ]);

        User::factory()->count(4)->create();
    }
}
