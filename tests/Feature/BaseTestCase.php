<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

abstract class BaseTestCase extends TestCase
{
    const VALID_EMAIL      = 'johndoe@gmail.com';
    const VALID_CODE       = 'code';
    const VALID_PASSWORD   = 'password';
    const INVALID_EMAIL    = 'janedoe@gmail.com';
    const INVALID_PASSWORD = 'secret';

    protected $customer;

    public function setUp():void
    {
        parent::setUp();

        $this->setUpUser();
    }

    protected function setUpUser()
    {
        $this->customer = [
            'first_name'    => 'John',
            'last_name'     => 'Doe',
            'email'         => static::VALID_EMAIL,
            'personal_code' => static::VALID_CODE,
            'password'      => Hash::make(static::VALID_PASSWORD),
        ];

        $this->customer['id'] = DB::table('customers')->insertGetId($this->customer);

        $token = [
            'token'       => bin2hex(random_bytes(32)),
            'customer_id' => $this->customer['id'],
            'description' => '',
        ];

        $this->customer['token'] = $token['token'];

        DB::table('customer_tokens')->insert($token);
    }
}
