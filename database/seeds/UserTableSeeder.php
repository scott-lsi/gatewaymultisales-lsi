<?php

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->delete();

        User::create(array(
        	'name'->'Robert Hemingway',
        	'email'->'robert@lsi.co.uk',
        	'password'->Hash::make('lsirh2018'),

        ))
    }
}
