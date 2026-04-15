<?php
namespace App\Controllers\Api;

use App\Models\User_model;
use CodeIgniter\RESTful\ResourceController;
use Firebase\JWT\JWT;

class AuthController extends ResourceController
{
    protected $modelName = User_model::class;
    protected $format = 'json';
    private $key;

    public function __construct()
    {
        $this->key = getenv('JWT_SECRET');
    }

    public function register()
    {
        $data = $this->request->getJSON(true) ?? $this->request->getPost();

            if (
                empty($data['first_name']) ||
                empty($data['last_name']) ||
                empty($data['email']) ||
                empty($data['phone_number']) ||
                empty($data['password'])
            ) {
                return $this->fail("All fields are required");
            }
            unset($data['confirm_password']); // Remove confirm_password from validation

        if(strlen($data['password']) < 6){
            return $this->fail("Password must be at least 6 characters");
        }

        $existing = $this->model->where('email', $data['email'])->first();
        if ($existing) return $this->fail("Email already registered");

        log_message('debug', 'REACHED INSERT STEP');

        $result = $this->model->insert([ // palitan fields for Fname Lname cannot be null
            'first_name' => $data['first_name'],
            'middle_name' => $data['middle_name'] ?? '',
            'last_name'  => $data['last_name'],
            'email'      => $data['email'],
            'password'   => password_hash($data['password'], PASSWORD_DEFAULT),
            'phone_number' => $data['phone_number'],
            'role_id' => 3,
            'date_created' => date('Y-m-d H:i:s'),
            'date_updated' => date('Y-m-d H:i:s'),
        ]);

        if (!$result) {
            return $this->respond([
                'MODEL_ERRORS' => $this->model->errors(),
                'DB_ERROR'     => db_connect()->error()
            ]);
        }

        return $this->respond([
            'status' => 'success',
            'message' => 'User registered successfully'
        ]);

    }

    public function showSignupForm()
    {
        $data = ['title' => 'Sign Up - Printopia'];

        echo view('include/head_view', $data);
        echo view('include/nav_view');
        echo view('logins/signup_view');
        echo view('include/foot_view');
    }

    public function login()
    {
        $data = $this->request->getJSON(true) ?? $this->request->getPost();

        if (!isset($data['email']) || !isset($data['password'])) {
            return $this->fail("Email and password required");
        }

        $user = $this->model->where('email', $data['email'])->first();

        if (!$user || !password_verify($data['password'], $user['password'])) {
            return $this->failUnauthorized("Invalid email or password");
        }

        $payload = [
            'iss' => base_url(),
            'aud' => base_url(),
            'iat' => time(),
            'exp' => time() + 86400,
            'uid' => $user['user_id'],
        ];

        $jwt = JWT::encode($payload, $this->key, 'HS256');

        // Default redirect
        $redirectUrl = '/printopia/dashboard'; 

        // Role-based redirect
        if (isset($user['role_id'])) {
            if ($user['role_id'] == 1) {
                $redirectUrl = '/admin';
            } else if ($user['role_id'] == 2) {
                $redirectUrl = '/employee';
            }
        }

        return $this->respond([
            'status' => 'success',
            'token' => $jwt,
            'redirect' => $redirectUrl,
            'user' => [
                'id' => $user['user_id'],
                'email' => $user['email'],
                'first_name' => $user['first_name'],
                'last_name' => $user['last_name'],
                'role_id' => $user['role_id'] ?? NULL
            ]
        ]);
    }

        public function showLoginform()
    {
        $data = ['title' => 'Sign Up - Printopia'];

        echo view('include/head_view', $data);
        echo view('include/nav_view');
        echo view('logins/login_view');
        echo view('include/foot_view');
    }

}