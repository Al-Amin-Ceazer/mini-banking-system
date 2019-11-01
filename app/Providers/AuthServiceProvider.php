<?php

namespace App\Providers;

use App\Models\Customer;
use App\Models\CustomerToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Auth::viaRequest('customer-token', function (Request $request) {
            $token = $request->bearerToken();
            $customerToken = CustomerToken::where('token', $token)->first();

            if (empty($customerToken)) {
                return null;
            }

            return Customer::find($customerToken->customer_id);
        });
    }
}
