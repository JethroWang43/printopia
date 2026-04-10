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
        $data = $this->request->getJSON(true);

        if (!isset($data['email']) || !isset($data['password'])) {
            return $this->fail("Email and password required");
        }

        $existing = $this->model->where('email', $data['email'])->first();
        if ($existing) return $this->fail("Email already registered");

        $this->model->insert([
            'first_name' => $data['first_name'] ?? null,
            'last_name'  => $data['last_name'] ?? null,
            'email'      => $data['email'],
            'password'   => password_hash($data['password'], PASSWORD_DEFAULT),
            'phone_number' => $data['phone_number'] ?? null,
            'date_created' => date('Y-m-d H:i:s')
        ]);

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
        $data = $this->request->getJSON(true);

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
        $redirectUrl = '/dashboard'; 

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
                'role_id' => $user['role_id'] ?? null
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