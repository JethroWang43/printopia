<?php

namespace App\Controllers;

use App\Models\User_model;
use CodeIgniter\HTTP\ResponseInterface;

class Account extends BaseController
{
    protected $userModel;
    protected $request;
    protected $response;

    // Map role names to role IDs
    protected $roleMap = [
        'user' => 1,
        'employee' => 2,
    ];

    public function __construct()
    {
        $this->userModel = new User_model();
        $this->request = service('request');
        $this->response = service('response');
    }

    /**
     * Get all users from Supabase
     */
    public function list(): ResponseInterface
    {
        try {
            log_message('info', 'Account list request');
            $users = $this->userModel->findAll();

            // Map database fields to frontend representation
            $formattedUsers = array_map(function ($user) {
                return [
                    'user_id' => $user['user_id'],
                    'first_name' => $user['first_name'] ?? '',
                    'middle_name' => $user['middle_name'] ?? '',
                    'last_name' => $user['last_name'] ?? '',
                    'email' => $user['email'] ?? '',
                    'phone_number' => $user['phone_number'] ?? '',
                    'password' => $user['password'] ?? '',
                    'role_id' => $user['role_id'] ?? '',
                    'date_created' => $user['date_created'] ?? '',
                    'date_updated' => $user['date_updated'] ?? '',
                    'last_entered' => $user['last_entered'] ?? $user['date_updated'] ?? '',
                ];
            }, $users);

            log_message('info', 'Account list - returned ' . count($formattedUsers) . ' users');

            return $this->response->setJSON([
                'success' => true,
                'data' => $formattedUsers,
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Account list error: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => 'Failed to fetch accounts',
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Save (create/update) user account
     */
    public function save(): ResponseInterface
    {
        try {
            // Log all POST data for debugging
            log_message('info', 'Account save request - POST data: ' . json_encode($this->request->getPost()));
            
            $firstName = trim($this->request->getPost('first_name') ?? '');
            $lastName = trim($this->request->getPost('last_name') ?? '');
            $email = trim($this->request->getPost('email') ?? '');
            $phoneNumber = trim($this->request->getPost('phone_number') ?? '');
            $password = $this->request->getPost('password') ?? '';
            $roleInput = trim($this->request->getPost('role_id') ?? '');
            $userId = $this->request->getPost('user_id');
            $middleName = trim($this->request->getPost('middle_name') ?? '');

            // Convert role name to role_id
            $roleId = $this->roleMap[$roleInput] ?? null;
            if ($roleId === null) {
                return $this->response->setStatusCode(422)->setJSON([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => ['Invalid role selected'],
                ]);
            }

            // Validation
            $errors = [];

            if (empty($firstName)) {
                $errors[] = 'First name is required';
            }

            if (empty($lastName)) {
                $errors[] = 'Last name is required';
            }

            if (empty($email)) {
                $errors[] = 'Email is required';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Email format is invalid';
            }

            if (empty($phoneNumber)) {
                $errors[] = 'Phone number is required';
            }

            // Only require password for new accounts (create mode)
            if (empty($userId) && empty($password)) {
                $errors[] = 'Password is required for new accounts';
            }

            if (!empty($errors)) {
                return $this->response->setStatusCode(422)->setJSON([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $errors,
                ]);
            }

            if (!empty($userId)) {
                // Update existing user
                $userData = [
                    'first_name' => $firstName,
                    'middle_name' => $middleName,
                    'last_name' => $lastName,
                    'email' => $email,
                    'phone_number' => $phoneNumber,
                    'role_id' => $roleId,
                    'date_updated' => date('Y-m-d H:i:s'),
                    'last_entered' => date('Y-m-d'),
                ];

                // Only update password if provided
                if (!empty($password)) {
                    $userData['password'] = password_hash($password, PASSWORD_BCRYPT);
                }

                $updateResult = $this->userModel->update($userId, $userData);
                
                if (!$updateResult) {
                    $dbError = $this->userModel->errors();
                    log_message('error', 'Account update failed: ' . json_encode($dbError));
                    return $this->response->setStatusCode(500)->setJSON([
                        'success' => false,
                        'message' => 'Failed to update account',
                        'error' => !empty($dbError) ? implode(', ', $dbError) : 'Database error',
                    ]);
                }

                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Account updated successfully',
                    'user_id' => $userId,
                ]);
            } else {
                // Create new user
                $userData = [
                    'first_name' => $firstName,
                    'middle_name' => $middleName,
                    'last_name' => $lastName,
                    'email' => $email,
                    'phone_number' => $phoneNumber,
                    'password' => password_hash($password, PASSWORD_BCRYPT),
                    'role_id' => $roleId,
                    'date_created' => date('Y-m-d H:i:s'),
                    'date_updated' => date('Y-m-d H:i:s'),
                    'last_entered' => date('Y-m-d'),
                ];

                $userId = $this->userModel->insert($userData);
                
                if (!$userId) {
                    $dbError = $this->userModel->errors();
                    log_message('error', 'Account insert failed: ' . json_encode($dbError));
                    return $this->response->setStatusCode(500)->setJSON([
                        'success' => false,
                        'message' => 'Failed to create account',
                        'error' => !empty($dbError) ? implode(', ', $dbError) : 'Database error',
                    ]);
                }

                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Account created successfully',
                    'user_id' => $userId,
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Account save error: ' . $e->getMessage() . ' - ' . $e->getTraceAsString());
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => 'Failed to save account',
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Delete user account
     */
    public function delete($userId): ResponseInterface
    {
        try {
            if (empty($userId) || !is_numeric($userId)) {
                return $this->response->setStatusCode(400)->setJSON([
                    'success' => false,
                    'message' => 'Invalid user ID',
                ]);
            }

            $user = $this->userModel->find($userId);
            if (!$user) {
                return $this->response->setStatusCode(404)->setJSON([
                    'success' => false,
                    'message' => 'User not found',
                ]);
            }

            $this->userModel->delete($userId);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Account deleted successfully',
                'user_id' => $userId,
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Account delete error: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => 'Failed to delete account',
                'error' => $e->getMessage(),
            ]);
        }
    }
}
?>
