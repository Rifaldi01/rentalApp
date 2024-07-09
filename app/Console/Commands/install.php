<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
class install extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Artisan::call('migrate:reset', ['--force' => true]);
        Artisan::call('migrate');
        Artisan::call('permission:create-role superAdmin');
        Artisan::call('permission:create-role admin');
        Artisan::call('permission:create-role manager');
        Artisan::call('permission:create-role employe');

        $user = [
            [
                'id' => '1',
                'name' => 'Super Admin',
                'phone' => '08123456789',
                'email' => 'superadmin@gmail.com',
                'password' => \Illuminate\Support\Facades\Hash::make('123'),
                'image' => 'avatar.png',
            ],
            [
                'id' => '2',
                'name' => 'Admin',
                'phone' => '08223456789',
                'email' => 'admin@gmail.com',
                'password' => \Illuminate\Support\Facades\Hash::make('123'),
                'image' => 'avatar.png',
            ],
            [
                'id' => '3',
                'name' => 'Manager',
                'phone' => '08323456789',
                'email' => 'manager@gmail.com',
                'password' => \Illuminate\Support\Facades\Hash::make('123'),
                'image' => 'avatar.png',
            ],
            [
                'id' => '4',
                'name' => 'Employe',
                'phone' => '08423456789',
                'email' => 'employe@gmail.com',
                'password' => \Illuminate\Support\Facades\Hash::make('123'),
                'image' => 'avatar.png',
            ],

        ];
        \App\Models\User::insert($user);

        User::find(1)->assignRole('superAdmin');
        User::find(2)->assignRole('Admin');
        User::find(3)->assignRole('manager');
        User::find(4)->assignRole('employe');

        $category = [
            [
                'id' => '1',
                'name' => 'TS',
            ],
            [
                'id' => '2',
                'name' => 'GPS',
            ],
            [
                'id' => '3',
                'name' => 'WP',
            ],
            [
                'id' => '4',
                'name' => 'DT',
            ],
            [
                'id' => '5',
                'name' => 'HANDHELD',
            ],
        ];
        \App\Models\Category::insert($category);

//        $cataccess = [
//            [
//                'id' => '1',
//                'name' => 'Antena',
//            ],
//            [
//                'id' => '2',
//                'name' => 'Battery',
//            ],
//            [
//                'id' => '3',
//                'name' => 'Charger',
//            ],
//            [
//                'id' => '4',
//                'name' => 'Briket',
//            ],
//            [
//                'id' => '5',
//                'name' => 'Prima',
//            ],
//        ];
//        \App\Models\CatAccess::insert($cataccess);
    }
}
