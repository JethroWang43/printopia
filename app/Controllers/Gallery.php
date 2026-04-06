<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;

class Gallery extends BaseController
{
    use ResponseTrait;

    private function getCloudinaryConfig(): array
    {
        return [
            'cloudName' => 'dik33xzef',
            'apiKey' => '561229386672246',
            'apiSecret' => 'hkjnNoLn0PwITfBszyy6nTGQoYs',
        ];
    }

    private function buildCloudinarySignedDownloadUrl(string $publicId, string $format): string
    {
        $cfg = $this->getCloudinaryConfig();
        $timestamp = time();

        $params = [
            'format' => $format,
            'public_id' => $publicId,
            'timestamp' => $timestamp,
            'type' => 'upload',
        ];
        ksort($params);

        // Cloudinary signatures must use unescaped key=value pairs.
        $signatureParts = [];
        foreach ($params as $key => $value) {
            $signatureParts[] = $key . '=' . $value;
        }
        $toSign = implode('&', $signatureParts);
        $signature = sha1($toSign . $cfg['apiSecret']);

        $query = $params;
        $query['signature'] = $signature;
        $query['api_key'] = $cfg['apiKey'];

        return 'https://api.cloudinary.com/v1_1/' . $cfg['cloudName'] . '/image/download?' . http_build_query($query, '', '&', PHP_QUERY_RFC3986);
    }

    private function getGalleryTableBuilder()
    {
        $dbConfig = config('Database');
        $defaultParams = $dbConfig->default;

        try {
            $defaultDb = \Config\Database::connect();
            if ($defaultDb->tableExists('gallery_designs')) {
                return $defaultDb->table('gallery_designs');
            }
        } catch (\Throwable $e) {
            // Fallback to schema discovery below when default DB is invalid.
        }

        $serverParams = $defaultParams;
        $serverParams['database'] = '';
        $serverDb = \Config\Database::connect($serverParams, false);

        $query = $serverDb->query(
            "SELECT TABLE_SCHEMA FROM information_schema.tables WHERE TABLE_NAME = 'gallery_designs' LIMIT 1"
        );
        $row = $query->getRowArray();

        if (!$row || empty($row['TABLE_SCHEMA'])) {
            throw new \RuntimeException('Could not locate gallery_designs table in any database schema.');
        }

        $galleryParams = $defaultParams;
        $galleryParams['database'] = $row['TABLE_SCHEMA'];
        $galleryDb = \Config\Database::connect($galleryParams, false);

        return $galleryDb->table('gallery_designs');
    }

    public function files() {
        try {
            $builder = $this->getGalleryTableBuilder();
        } catch (\Throwable $e) {
            return $this->respond([
                'files' => [],
                'summary' => [
                    'totalImages' => 0,
                    'storageBytes' => 0,
                    'categories' => 0,
                    'thisMonth' => 0,
                ],
                'error' => $e->getMessage(),
            ], 500);
        }
        
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

        try {
            $builder = $this->getGalleryTableBuilder();
        } catch (\Throwable $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => $e->getMessage(),
                'token' => csrf_hash(),
            ]);
        }

        if ($builder->insertBatch($insertData)) {
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

        try {
            $builder = $this->getGalleryTableBuilder();
        } catch (\Throwable $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => $e->getMessage(),
                'token' => csrf_hash(),
            ]);
        }
        
        // 1. Get file details
        $file = $builder->where('id', $id)->get()->getRow();

        if ($file && !empty($file->public_id)) {
            $cfg = $this->getCloudinaryConfig();
            $cloudName = $cfg['cloudName'];
            $apiKey = $cfg['apiKey'];
            $apiSecret = $cfg['apiSecret'];
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

    public function open($id = null)
    {
        if (!$id) {
            return redirect()->back();
        }

        try {
            $builder = $this->getGalleryTableBuilder();
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        $file = $builder->where('id', $id)->get()->getRow();
        if (!$file) {
            return redirect()->back()->with('error', 'File not found.');
        }

        $format = strtolower((string) ($file->format ?? ''));
        $fileUrl = trim((string) ($file->image_url ?? ''));
        $publicId = trim((string) ($file->public_id ?? ''));

        if ($format === 'pdf' && $publicId !== '') {
            $signedUrl = $this->buildCloudinarySignedDownloadUrl($publicId, 'pdf');
            return redirect()->to($signedUrl);
        }

        if ($fileUrl !== '') {
            return redirect()->to($fileUrl);
        }

        return redirect()->back()->with('error', 'File URL is missing.');
    }
}