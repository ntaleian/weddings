<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call('UsersSeeder');
        $this->call('CampusesSeeder');
        $this->call('PastorsSeeder');
        $this->call('BookingsSeeder');
        $this->call('BlockedDatesSeeder');
    }
}
