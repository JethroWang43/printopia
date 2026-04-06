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
        // Always ensure a token is returned even on early errors
        if (!$id) {
            return $this->response->setJSON([
                'status' => 'error', 
                'message' => 'No ID provided',
                'token'  => csrf_hash()
            ]);
        }

        $db = \Config\Database::connect();
        $builder = $db->table('gallery_designs');
        
        // 1. Get file details first
        $file = $builder->where('id', $id)->get()->getRow();

        if ($file && !empty($file->public_id)) {
            $cloudName = 'dik33xzef'; 
            $apiKey    = '561229386672246'; 
            $apiSecret = 'hkjnNoLn0PwITfBszyy6nTGQoYs'; 
            $timestamp = time();
            
            // 2. Optimized Signature Generation
            // Cloudinary expects: parameter1=value1&parameter2=value2SECRET
            $params = [
                'public_id' => $file->public_id,
                'timestamp' => $timestamp,
            ];
            ksort($params);

            $sign_string = "";
            foreach ($params as $key => $value) {
                $sign_string .= "$key=$value&";
            }
            $sign_string = rtrim($sign_string, "&") . $apiSecret;
            $signature = sha1($sign_string);

            // 3. API Call
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
            $response = json_decode(curl_exec($ch), true); // Decode to check result
            curl_close($ch);
            
            // Log for your own debugging if needed
            // log_message('debug', 'Cloudinary result: ' . json_encode($response));
        }

        // 4. Delete from local database (printopia_database)
        if ($builder->where('id', $id)->delete()) {
            return $this->response->setJSON([
                'status' => 'success',
                'token'  => csrf_hash() 
            ]);
        }

        return $this->response->setJSON([
            'status' => 'error', 
            'message' => 'Database delete failed',
            'token'  => csrf_hash()
        ]);
    }
}