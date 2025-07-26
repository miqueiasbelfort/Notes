<?php

namespace Database\Seeders;

use App\Models\Note;
use Illuminate\Database\Seeder;

class NotesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Note::insert([
            [
                'user_id' => 1,
                'title' => 'Note 1',
                'text' => 'This is the note 1',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'user_id' => 1,
                'title' => 'Note 2',
                'text' => 'This is the note 2',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
