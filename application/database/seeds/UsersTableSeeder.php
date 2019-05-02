<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Collection;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Collection::make([
            [
                'name' => 'user1',
                'email' => 'user1@test.com',
                'nick' => 'user1',
                'password' => Hash::make('123456'),
                'id' => '1'
            ],
            [
                'name' => 'user2',
                'email' => 'user2@test.com',
                'nick' => 'user2',
                'password' => Hash::make('123456'),
                'id' => '2'
            ],
            [
                'name' => 'user3',
                'email' => 'user3@test.com',
                'nick' => 'user3',
                'password' => Hash::make('123456'),
                'id' => '3'
            ],
            [
                'name' => 'user4',
                'email' => 'user4@test.com',
                'nick' => 'user4',
                'password' => Hash::make('123456'),
                'id' => '4'
            ],
            [
                'name' => 'user5',
                'email' => 'user5@test.com',
                'nick' => 'user5',
                'password' => Hash::make('123456'),
                'id' => '5'
            ],
        ])->each(function($item){
            User::updateOrCreate(
                ['id' => $item['id']],
                $item
            );
        });
    }
}
