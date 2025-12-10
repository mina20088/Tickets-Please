<?php

namespace Database\Seeders;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
         Ticket::factory(1)->recycle(User::factory(2)->create())->create();


         User::create([
             'name' => "The Manger",
             'email' => 'manger@laracasts.com',
             'password' => 'password',
             'is_manger' => true
         ]);
    }
}
