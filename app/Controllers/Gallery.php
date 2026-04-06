<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;

class Gallery extends BaseController
{
    use ResponseTrait;

    public function files() {
        $db = \Config\Database::connect();
        $builder = $db->table('gallery_designs');
        
        // Fetch all records ordered by newest first
        $files = $builder->orderBy('created_at', 'DESC')->get()->getResult();

        return $this->response->setJSON([
            'files' => $files,
            'summary' => [
                'totalImages' => count($files),
                'storageBytes' => array_sum(array_column($files, 'bytes')),
                'categories' => 1, // Adjust if you have categories
                'thisMonth' => count($files) 
            ]
        ]);
    }

    public function save_to_db() {
        
        /** @var \CodeIgniter\HTTP\IncomingRequest $request */
        $request = $this->request;
        // Now Intelephense will recognize getJSON()
        $json = $request->getJSON(true);

        if (empty($json)) {
            return $this->response->setJSON([
                'status' => 'error', 
                'message' => 'No data received',
                'token' => csrf_hash() // Return token even on error
            ]);
        }

        $items = isset($json['image_url']) ? [$json] : $json;
        $insertData = [];
        foreach ($items as $item) {
            $insertData[] = [
                'filename'      => $item['filename'] ?? 'Untitled',
                'image_url'     => $item['image_url'],
                'public_id'     => $item['public_id'],
                'format'        => $item['format'] ?? 'jpg',
                'bytes'         => $item['bytes'] ?? 0,
                'customer_name' => 'Admin Upload'
            ];
        }

        $db = \Config\Database::connect();
        if ($db->table('gallery_designs')->insertBatch($insertData)) {
            return $this->response->setJSON([
                'status' => 'success', 
                'count'  => count($insertData),
                'token'  => csrf_hash() // <--- NEW FRESH TOKEN
            ]);
        }

        return $this->response->setJSON([
            'status' => 'error', 
            'message' => 'Database insert failed',
            'token'  => csrf_hash()
        ]);
    }

    public function delete($id = null)
    {
        if (!$id) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'No ID provided']);
        }

        $db = \Config\Database::connect();
        $builder = $db->table('gallery_designs');
        
        // 1. Get file details
        $file = $builder->where('id', $id)->get()->getRow();

        if ($file && !empty($file->public_id)) {
            $cloudName = 'dik33xzef'; 
            $apiKey    = '561229386672246'; 
            $apiSecret = 'hkjnNoLn0PwITfBszyy6nTGQoYs'; 
            $timestamp = time();
            
            // 2. Correct Signature Generation
            // Parameters MUST be in alphabetical order for the signature
            $params_to_sign = [
                'public_id' => $file->public_id,
                'timestamp' => $timestamp,
            ];
            ksort($params_to_sign); // Sort alphabetically

            $string_to_sign = "";
            foreach ($params_to_sign as $key => $value) {
                $string_to_sign .= "$key=$value&";
            }
            // Remove trailing '&' and append the API Secret
            $string_to_sign = rtrim($string_to_sign, "&") . $apiSecret;
            $signature = sha1($string_to_sign);

            // 3. Cloudinary API Call
            $url = "https://api.cloudinary.com/v1_1/{$cloudName}/image/destroy";
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, [
                'public_id' => $file->public_id,
                'timestamp' => $timestamp,
                'api_key'   => $apiKey,
                'signature' => $signature
            ]);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            curl_close($ch);
            
            // Optional: Log response for debugging
            // log_message('debug', 'Cloudinary Delete Response: ' . $response);
        }

        // 4. Delete from local database
        if ($builder->where('id', $id)->delete()) {
            return $this->response->setJSON([
                'status' => 'success',
                'token'  => csrf_hash() // <--- NEW FRESH TOKEN
            ]);
        }

        return $this->response->setJSON([
            'status' => 'error', 
            'message' => 'Database delete failed',
            'token'  => csrf_hash()
        ]);
    }
}