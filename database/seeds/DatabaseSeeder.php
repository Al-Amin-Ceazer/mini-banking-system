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

        DB::table('beneficiaries')->insert([
            ['customer_id' => '2','transfer_type' => 'same_bank', 'account_number' => '2', 'description' => 'same bank fund transfer', 'agent_name' => 'MBS', 'created_at' => now(), 'updated_at' => now()],
            ['customer_id' => '2','transfer_type' => 'others', 'account_number' => '3161123456', 'description' => 'other bank fund transfer', 'agent_name' => 'DANSKE BANK', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
