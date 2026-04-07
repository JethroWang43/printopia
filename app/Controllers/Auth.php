<?php
namespace App\Controllers;

use CodeIgniter\Controller;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Exception\AuthException;

class Auth extends Controller
{
    protected $auth;
    protected $database;

    public function __construct()
    {
        $factory = (new Factory)
            ->withServiceAccount(APPPATH . 'config/firebase-service-account.json')
            ->withDatabaseUri('https://printopia-39edf-default-rtdb.asia-southeast1.firebasedatabase.app/');

        $this->auth = $factory->createAuth();
        $this->database = $factory->createDatabase();
    }

    // ======================
    // SIGNUP
    // ======================
    public function signup()
    { 

       $post = $this->request->getPost();


        $firstName = $post['first_name'] ?? '';
        $lastName = $post['last_name'] ?? '';
        $middleName = $post['middle_name'] ?? '';
        $email = $post['email'] ?? '';
        $password = $post['password'] ?? '';
        $confirmPassword = $post['confirm_password'] ?? '';
        $phone = $post['phone_number'] ?? '';
        $dateCreated = date(DATE_ISO8601);

        // Validation
        if (empty($email) || empty($password)) {
            return redirect()->back()->with('error', 'Email and password are required');
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return redirect()->back()->with('error', 'Invalid email format');
        }
        if (strlen($password) < 6) {
            return redirect()->back()->with('error', 'Password must be at least 6 characters');
        }
        if ($password !== $confirmPassword) {
            return redirect()->back()->with('error', 'Passwords do not match');
        }

        // Special names list
        $adminEmployees = ['John Doe', 'Jane Smith', 'Alice Johnson'];

        // Determine role
        $fullName = trim($firstName . ' ' . $lastName);
        $roleId = 1; // Customer by default
        if (in_array($fullName, $adminEmployees)) {
            if ($fullName === 'John Doe') {
                $roleId = 0; // Admin
            } else {
                $roleId = 2; // Employee
            }
        }

        // Firebase signup
        try {
            try {
                $this->auth->getUserByEmail($email);
                return redirect()->back()->with('error', 'Email already exists');
            } catch (\Kreait\Firebase\Exception\Auth\UserNotFound $e) {}

            $user = $this->auth->createUser([
                'email' => $email,
                'password' => $password,
            ]);

            $uid = $user->uid;

            // Save to Firebase Realtime DB
            $this->database->getReference('users/' . $uid)
                ->set([
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'middle_name' => $middleName,
                    'email' => $email,
                    'phone_number' => $phone,
                    'role_id' => $roleId,
                    'date_created' => $dateCreated
                ]);

            // Save session
            session()->set([
                'uid' => $uid,
                'email' => $email,
                'first_name' => $firstName,
                'middle_name' => $middleName,
                'last_name' => $lastName,
                'role_id' => $roleId,
                'isLoggedIn' => true
            ]);

            return redirect()->to('/dashboard')->with('success', 'Signup successful!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        } 
    }

    public function showSignupForm()
    {
        $data = ['title' => 'Sign Up - Printopia'];

        echo view('include/head_view', $data);
        echo view('include/nav_view');
        echo view('logins/signup_view');
        echo view('include/foot_view');
    }

    // ======================
    // LOGIN
    // ======================
        public function login()
        {
            $email = $this->request->getPost('email');
            $password = $this->request->getPost('password');

            if (empty($email) || empty($password)) {
                return redirect()->back()->with('error', 'Email and password are required');
            }

            try {
                $client = \Config\Services::curlrequest();
                $apiKey = "AIzaSyBiqvBZjwP8l0JMTie7cRd0I3odj86Bujg"; // your Firebase Web API key

                $response = $client->post(
                    "https://identitytoolkit.googleapis.com/v1/accounts:signInWithPassword?key={$apiKey}",
                    [
                        'json' => [
                            'email' => $email,
                            'password' => $password,
                            'returnSecureToken' => true
                        ]
                    ]
                );

                $result = json_decode($response->getBody(), true);

                // 🔹 Map Firebase errors to friendly messages
                if (isset($result['error'])) {
                    $firebaseError = $result['error']['message'] ?? 'Login failed';

                    switch ($firebaseError) {
                        case 'EMAIL_NOT_FOUND':
                            $errorMsg = 'Account does not exist.';
                            break;
                        case 'INVALID_PASSWORD':
                            $errorMsg = 'Incorrect password.';
                            break;
                        case 'USER_DISABLED':
                            $errorMsg = 'This account has been disabled.';
                            break;
                        default:
                            $errorMsg = $firebaseError; // fallback
                    }

                    return redirect()->back()->with('error', $errorMsg);
                }

                $uid = $result['localId'] ?? null;

                if (!$uid) {
                    return redirect()->back()->with('error', 'Firebase UID not found');
                }

                // Fetch user data from Firebase Realtime DB
                $userData = $this->database->getReference('users/' . $uid)->getValue();

                if (!$userData) {
                    return redirect()->back()->with('error', 'User data not found in database');
                }

                // ✅ Set session
                session()->set([
                    'uid'        => $uid,
                    'email'      => $email,
                    'first_name' => $userData['first_name'] ?? '',
                    'last_name'  => $userData['last_name'] ?? '',
                    'role_id'    => $userData['role_id'] ?? 1,
                    'isLoggedIn' => true
                ]);

                return redirect()->to('/dashboard')->with('success', 'Login successful');

            } catch (\Exception $e) {
                log_message('error', 'Firebase login error: ' . $e->getMessage());
                return redirect()->back()->with('error', 'Invalid login credentials');
            }
        }


    public function showLoginForm()
    {
        $data = ['title' => 'Login - Printopia'];

        echo view('include/head_view', $data);
        echo view('include/nav_view');
        echo view('logins/login_view');
        echo view('include/foot_view');
    }

    // ======================
    // LOGOUT
    // ======================
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login')->with('success', 'Logged out successfully');
    }
}