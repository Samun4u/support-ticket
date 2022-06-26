<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('comments')->updateOrInsert([
            'body' => 'This is new Comment',
            'user_id' => 1,
            'ticket_id' => 1,
        ]);
    }
}
