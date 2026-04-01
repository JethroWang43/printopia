<?php
namespace App\Controllers;

class Admin extends BaseController {
    public function index() {
        $data = array(
            'title' => 'Printopia - Admin Dashboard'
        );

        return view('admin_view', $data);
    }

    public function trelloTaskIndex() {
        return $this->trelloTaskAsset('index.html');
    }

    public function trelloTaskLibAsset(string $asset = '') {
        return $this->trelloTaskAsset('lib/' . ltrim($asset, '/\\'));
    }

    public function trelloTaskAsset(string $asset = '') {
        $safeAsset = ltrim(str_replace('..', '', $asset), '/\\');
        $basePath = APPPATH . 'Models' . DIRECTORY_SEPARATOR . 'trello-task' . DIRECTORY_SEPARATOR;
        $filePath = realpath($basePath . $safeAsset);
        $realBase = realpath($basePath);

        if (!$realBase || !$filePath || strpos($filePath, $realBase) !== 0 || !is_file($filePath)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        $mimeTypes = [
            'html' => 'text/html; charset=UTF-8',
            'js' => 'application/javascript; charset=UTF-8',
            'css' => 'text/css; charset=UTF-8',
            'json' => 'application/json; charset=UTF-8',
        ];
        $mimeType = $mimeTypes[$extension] ?? (mime_content_type($filePath) ?: 'application/octet-stream');

        return $this->response
            ->setStatusCode(200)
            ->setHeader('Content-Type', $mimeType)
            ->setBody(file_get_contents($filePath));
    }
}
?>