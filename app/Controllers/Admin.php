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

    public function galleryFiles() {
        $uploadBase = WRITEPATH . 'uploads';
        if (!is_dir($uploadBase)) {
            return $this->response->setJSON([
                'files' => [],
                'summary' => [
                    'totalImages' => 0,
                    'storageBytes' => 0,
                    'categories' => 0,
                    'thisMonth' => 0,
                ],
            ]);
        }

        $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp', 'gif', 'svg'];
        $files = [];
        $categoryMap = [];
        $totalBytes = 0;
        $thisMonth = 0;

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($uploadBase, \FilesystemIterator::SKIP_DOTS)
        );

        foreach ($iterator as $fileInfo) {
            if (!$fileInfo->isFile()) {
                continue;
            }

            $extension = strtolower($fileInfo->getExtension());
            if (!in_array($extension, $allowedExtensions, true)) {
                continue;
            }

            if (strtolower($fileInfo->getFilename()) === 'index.html') {
                continue;
            }

            $absolutePath = $fileInfo->getPathname();
            $relativePath = ltrim(str_replace(['\\', '/'], '/', substr($absolutePath, strlen($uploadBase))), '/');
            if ($relativePath === '') {
                continue;
            }

            $pathParts = explode('/', $relativePath);
            $category = 'general';
            if (count($pathParts) > 1) {
                if (strtolower($pathParts[0]) === 'users' && !empty($pathParts[1])) {
                    $category = $pathParts[1];
                } else {
                    $category = $pathParts[0];
                }
            }
            $categoryMap[$category] = true;

            $size = (int) $fileInfo->getSize();
            $modified = (int) $fileInfo->getMTime();
            $totalBytes += $size;

            if (date('Y-m', $modified) === date('Y-m')) {
                $thisMonth++;
            }

            $encodedPath = rtrim(strtr(base64_encode($relativePath), '+/', '-_'), '=');
            $files[] = [
                'id' => $encodedPath,
                'title' => pathinfo($fileInfo->getFilename(), PATHINFO_FILENAME),
                'category' => $category,
                'extension' => strtoupper($extension),
                'sizeBytes' => $size,
                'updatedAt' => date(DATE_ATOM, $modified),
                'previewUrl' => base_url('admin/gallery/file/' . rawurlencode($encodedPath)),
            ];
        }

        usort($files, static function (array $a, array $b): int {
            return strcmp($b['updatedAt'], $a['updatedAt']);
        });

        return $this->response->setJSON([
            'files' => $files,
            'summary' => [
                'totalImages' => count($files),
                'storageBytes' => $totalBytes,
                'categories' => count($categoryMap),
                'thisMonth' => $thisMonth,
            ],
        ]);
    }

    public function galleryFile(string $encodedPath = '') {
        $decoded = base64_decode(strtr($encodedPath, '-_', '+/'), true);
        if ($decoded === false || $decoded === '') {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $relativePath = ltrim(str_replace('..', '', str_replace('\\', '/', $decoded)), '/');
        if ($relativePath === '') {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $basePath = realpath(WRITEPATH . 'uploads');
        if ($basePath === false) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $filePath = realpath($basePath . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $relativePath));
        if (!$filePath || strpos($filePath, $basePath) !== 0 || !is_file($filePath)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $mimeType = mime_content_type($filePath) ?: 'application/octet-stream';

        return $this->response
            ->setStatusCode(200)
            ->setHeader('Content-Type', $mimeType)
            ->setBody(file_get_contents($filePath));
    }
}
?>