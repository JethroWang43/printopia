<?php

namespace App\Controllers;

use Cloudinary\Api\Upload\UploadApi;
use Cloudinary\Configuration\Configuration;

class Gallery extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        
        // Update with your actual Cloudinary Secret
        Configuration::instance([
            'cloud' => [
                'cloud_name' => 'dik33xzef', 
                'api_key'    => '561229386672246', 
                'api_secret' => 'hkjnNoLn0PwITfBszyy6nTGQoYs' 
            ],
            'url' => ['secure' => true]
        ]);
    }

    public function index($customerName = null)
    {
        if ($customerName === null) {
            // FOLDER MODE: Group by customer_name
            $query = $this->db->table('gallery_designs')
                              ->select('customer_name, COUNT(id) as total')
                              ->groupBy('customer_name')
                              ->get();

            $data['folders'] = $query->getResultArray();
            $data['view_mode'] = 'folders';
        } else {
            // CARD MODE: Show specific customer images
            $customerName = urldecode($customerName);
            $query = $this->db->table('gallery_designs')
                              ->where('customer_name', $customerName)
                              ->orderBy('created_at', 'DESC')
                              ->get();

            $data['images'] = $query->getResultArray();
            $data['customer_name'] = $customerName;
            $data['view_mode'] = 'cards';
        }

        return view('gallery_view', $data);
    }

    public function upload()
    {
        $file = $this->request->getFile('userfile');
        $customer = $this->request->getPost('customer_name') ?: 'Walk-in';

        if ($file && $file->isValid()) {
            $uploadApi = new UploadApi();
            try {
                $result = $uploadApi->upload($file->getRealPath());

                $this->db->table('gallery_designs')->insert([
                    'public_id'     => $result['public_id'],
                    'image_url'     => $result['secure_url'],
                    'filename'      => $file->getName(),
                    'format'        => $result['format'],
                    'customer_name' => $customer
                ]);

                return redirect()->to(base_url('gallery'))->with('success', 'Uploaded successfully!');
            } catch (\Exception $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }
        }
    }

    public function save_to_db() {
        $json = $this->request->getJSON(); // CI4 gets the array

        if ($json && is_array($json)) {
            $db = \Config\Database::connect();
            $builder = $db->table('gallery_designs');
            $successCount = 0;

            foreach ($json as $item) {
                $data = [
                    'public_id'     => $item->public_id,
                    'image_url'     => $item->image_url,
                    'filename'      => $item->filename,
                    'format'        => $item->format,
                    'customer_name' => $item->customer_name,
                    'created_at'    => date('Y-m-d H:i:s')
                ];

                if ($builder->insert($data)) {
                    $successCount++;
                }
            }

            if ($successCount > 0) {
                return $this->response->setJSON(['status' => 'success', 'count' => $successCount]);
            }
        }
        return $this->response->setJSON(['status' => 'error', 'message' => 'No images saved']);
    }

    public function delete($id)
    {
        $row = $this->db->table('gallery_designs')->getWhere(['id' => $id])->getRow();
        if ($row) {
            $uploadApi = new UploadApi();
            $uploadApi->destroy($row->public_id);
            $this->db->table('gallery_designs')->delete(['id' => $id]);
        }
        return redirect()->back();
    }
}