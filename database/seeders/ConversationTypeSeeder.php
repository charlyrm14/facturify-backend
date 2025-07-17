<?php

namespace Database\Seeders;

use App\Models\ConversationType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ConversationTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ConversationType::updateOrCreate([
            'name' => 'Private'
        ], [
            'name' => 'Private',
            'description' => 'Conversación entre dos usuarios',
        ]);

        ConversationType::updateOrCreate([
            'name' => 'Group'
        ], [
            'name' => 'Group',
            'description' => 'Conversación grupal',
        ]);
    }
}
