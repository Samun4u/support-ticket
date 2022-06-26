<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupportTicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('support_tickets')->updateOrInsert([
            'subject' => 'Ticket Number One',
        ]);
        DB::table('support_tickets')->updateOrInsert([
            'subject' => 'Ticket Number two',
        ]);
    }
}
