<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CustomersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $password = Hash::make('secret');

        DB::table('customers')->insert([
            [
                'first_name'    => "Jhon",
                'last_name'     => 'Doe',
                'email'         => 'jhon@gmail.com',
                'personal_code' => '20191104-101-2',
                'password'      => $password,
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'first_name'    => "Karry",
                'last_name'     => 'Wing',
                'email'         => 'karry@gmail.com',
                'personal_code' => '20191104-101-3',
                'password'      => $password,
                'created_at'    => now(),
                'updated_at'    => now(),
            ],

        ]);

        DB::table('accounts')->insert([
            [
                'branch_id'       => 1,
                'account_type_id' => 1,
                'account_balance' => 1500,
                'date_opened'     => '2018-11-03',
                'currency'        => 'Euro',
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
            [
                'branch_id'       => 1,
                'account_type_id' => 1,
                'account_balance' => 1500,
                'date_opened'     => '2019-03-03',
                'currency'        => 'Euro',
                'created_at'      => now(),
                'updated_at'      => now(),
            ],

        ]);

        DB::table('account_customer')->insert([
            [
                'account_id'  => 1,
                'customer_id' => 1,
            ],
            [
                'account_id'  => 2,
                'customer_id' => 2,
            ],

        ]);
    }
}
