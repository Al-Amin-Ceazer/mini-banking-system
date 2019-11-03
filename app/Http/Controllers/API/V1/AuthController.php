<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Requests\API\V1\CustomerStoreRequest;
use App\Http\Resources\API\V1\LoginResponse;
use App\Models\Customer;
use App\Repositories\CustomerRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\API\V1\Profile;
use App\Http\Requests\API\V1\LoginRequest;
use App\Repositories\SubmitTokenRepository;
use App\Http\Resources\API\ErrorResponse;
use App\Http\Resources\API\GenericResponse;
use Illuminate\Http\Response;

/**
 * Class AuthController
 *
 * @package App\Http\Controllers\API\V1
 */
class AuthController extends Controller
{
    protected $customers;
    protected $tokens;

    /**
     * AuthController constructor.
     *
     * @param \App\Repositories\CustomerRepository    $customers
     * @param \App\Repositories\SubmitTokenRepository $tokens
     */
    public function __construct(CustomerRepository $customers, SubmitTokenRepository $tokens)
    {
        $this->customers = $customers;
        $this->tokens    = $tokens;
    }

    public function postLogin(LoginRequest $request)
    {
        $email        = $request->get('email');
        $personalCode = $request->get('personal_code');
        $password     = $request->get('password');

        $customer = $this->customers->findByCredential($email, $personalCode);

        if ($customer !== null && password_verify($password, $customer->password)) {

            [$customer, $token] = $this->customers->registerApiToken($customer, $request->userAgent());

            $customer->api_token = $token->token;

            return new LoginResponse($customer);
        }

        $payload = [
            'code'         => Response::HTTP_FORBIDDEN,
            'app_message'  => 'login unsuccessful, credentials mismatch-match',
            'user_message' => 'Credentials didn\'t validate.',
        ];

        return new ErrorResponse('login', $payload, Response::HTTP_FORBIDDEN);
    }

    public function postLogout(Request $request)
    {
        $token = $request->bearerToken() ?? $request->input('api_token');
        $this->customers->unregisterApiToken($request->user(), $token);

        $payload = [
            'code'         => Response::HTTP_OK,
            'app_message'  => 'logout successful, access_token invalidated',
            'user_message' => 'Logout successful.',
        ];

        return new GenericResponse('logout', null, $payload, Response::HTTP_OK);
    }

    public function register(CustomerStoreRequest $request)
    {
        $data        = $request->all();
        $submitToken = $request->input('submit_token');

        $newCustomer = $this->tokens->query($submitToken, Customer::class);

        if (empty($newCustomer)) {

            $newCustomer = $this->customers->store($data);

            if ($newCustomer === false) {

                $message = [
                    'code'         => Response::HTTP_INTERNAL_SERVER_ERROR,
                    'app_message'  => 'customer store failed',
                    'user_message' => 'Customer could not be stored!',
                    'submit_token' => $submitToken,
                ];

                return new ErrorResponse('customer', $message, Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            if ($newCustomer->wasRecentlyCreated) {
                $this->tokens->store($submitToken, Customer::class, $newCustomer->id);
            }
        }

        $message = [
            'code'         => Response::HTTP_CREATED,
            'app_message'  => 'customer store successful',
            'user_message' => 'Customer registered successfully!',
            'submit_token' => $submitToken,
        ];

        return new GenericResponse('logout', new Profile($newCustomer), $message,Response::HTTP_CREATED);
    }
}
