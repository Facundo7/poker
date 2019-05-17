<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;


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
                'name' => 'juan',
                'email' => 'juan@poker.com',
                'password' => Hash::make('123456'),
                'nickname' => 'juannick',
                'image' => 'imageurl'
            ],
            [
                'name' => 'pepe',
                'email' => 'pepe@poker.com',
                'password' => Hash::make('123456'),
                'nickname' => 'pepenick',
                'image' => 'imageurl'
            ],
            [
                'name' => 'jorge',
                'email' => 'jorge@poker.com',
                'password' => Hash::make('123456'),
                'nickname' => 'jorgenick',
                'image' => 'imageurl'
            ],
            [
                'name' => 'laura',
                'email' => 'laura@poker.com',
                'password' => Hash::make('123456'),
                'nickname' => 'lauranick',
                'image' => 'imageurl'
            ],
            [
                'name' => 'isabel',
                'email' => 'isabel@poker.com',
                'password' => Hash::make('123456'),
                'nickname' => 'isabelnick',
                'image' => 'imageurl'
            ],
            [
                'name' => 'facundo',
                'email' => 'facundo@poker.com',
                'password' => Hash::make('123456'),
                'nickname' => 'facundonick',
                'image' => 'imageurl'
            ],
        ])->each(function ($item) {

            User::updateOrCreate(
                ['email' => $item['email']],
                $item
            );

        });
    }
}
