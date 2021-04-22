<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'FOO_123',
            'email' => 'foo@gmail.com',
            'password' => bcrypt('foo-bar-baz'),
        ]);
        DB::table('users')->insert([
            'name' => 'BAR_123',
            'email' => 'bar@gmail.com',
            'password' => bcrypt('foo-bar-baz'),
        ]);
        DB::table('users')->insert([
            'name' => 'BAZ_123',
            'email' => 'baz@gmail.com',
            'password' => bcrypt('foo-bar-baz'),
        ]);
    }
}
