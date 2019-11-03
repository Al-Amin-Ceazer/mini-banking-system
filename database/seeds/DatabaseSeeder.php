<?php

use App\Models\Customer;
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
        //factory(Customer::class,10)->create();

        DB::table('branches')->insert([
            ['branch_name' => 'default', 'City' => 'Berline', 'created_at' => now(), 'updated_at' => now()]
        ]);

        DB::table('account_types')->insert([
            ['type' => 'savings', 'minimum_balance_restriction' => 1500, 'created_at' => now(), 'updated_at' => now()]
        ]);
    }
}
