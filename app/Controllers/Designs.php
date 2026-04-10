<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use Cloudinary\Configuration\Configuration;
use Cloudinary\Api\Upload\UploadApi;

class Designs extends Controller
{
    public function __construct() {
        Configuration::instance([
            'cloud' => [
                'cloud_name' => 'dik33xzef', 
                'api_key'    => '561229386672246', 
                'api_secret' => 'hkjnNoLn0PwITfBszyy6nTGQoYs'
            ],
            'url' => ['secure' => true]
        ]);
    }

    public function index()
    {
        $db = \Config\Database::connect();
        
        // Ensure this session key matches your login logic (e.g., 'user_name' or 'name')
        $userName = session()->get('user_name') ?? 'Guest';

        $data['designs'] = $db->table('gallery_designs')
                              ->where('customer_name', $userName)
                              ->orderBy('created_at', 'DESC')
                              ->get()
                              ->getResultArray();

        // Path matches: app/Views/customer_tab/my_designs.php
        return view('customer_tab/my_designs', $data);
    }

    public function upload()
    {
        $file = $this->request->getFile('design_file');

        if ($file && $file->isValid() && !$file->hasMoved()) {
            try {
                $upload = new UploadApi();
                $result = $upload->upload($file->getRealPath(), [
                    'folder' => 'customer_uploads'
                ]);

                $dbData = [
                    'filename'      => $file->getName(),
                    'image_url'     => $result['secure_url'],
                    'public_id'     => $result['public_id'],
                    'format'        => $result['format'],
                    'customer_name' => session()->get('user_name') ?? 'Guest', 
                    'bytes'         => $result['bytes'],
                    'created_at'    => date('Y-m-d H:i:s')
                ];

                $db = \Config\Database::connect();
                // Ensure table is 'gallery_designs' and column is 'customer_name'
                $db->table('gallery_designs')->insert($dbData);

                return $this->response->setJSON(['status' => 'success']);

            } catch (\Exception $e) {
                return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
            }
        }
        return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid file.']);
    }

    public function delete($id)
    {
        $db = \Config\Database::connect();
        $userName = session()->get('user_name') ?? 'Guest';

        // Security: Only allow users to delete their own designs
        $design = $db->table('gallery_designs')
                    ->where('id', $id)
                    ->where('customer_name', $userName)
                    ->get()
                    ->getRowArray();

        if ($design) {
            // Option: You could also delete from Cloudinary here using $design['public_id']
            // but for now, we'll just remove the database record
            $db->table('gallery_designs')->where('id', $id)->delete();
            return $this->response->setJSON(['status' => 'success']);
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'Design not found or unauthorized.']);
    }
}