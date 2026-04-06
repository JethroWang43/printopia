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

    public function trelloTaskProxy() {
        $payload = $this->request->getJSON(true);
        if (!is_array($payload)) {
            $payload = $this->request->getPost();
        }

        $endpoint = isset($payload['endpoint']) ? trim((string) $payload['endpoint']) : '';
        $method = strtoupper((string) ($payload['method'] ?? 'GET'));
        $body = $payload['body'] ?? null;

        if ($endpoint === '' || str_contains($endpoint, '..') || $endpoint[0] !== '/') {
            return $this->response->setStatusCode(400)->setJSON([
                'error' => 'Invalid Trello endpoint.',
            ]);
        }

        $apiUrl = rtrim((string) env('TRELLO_API_URL', 'https://api.trello.com/1'), '/');
        $apiKey = (string) env('TRELLO_API_KEY', '');
        $apiToken = (string) env('TRELLO_API_TOKEN', '');

        if ($apiKey === '' || $apiToken === '') {
            return $this->response->setStatusCode(500)->setJSON([
                'error' => 'Trello credentials are not configured on the server.',
            ]);
        }

        $url = $apiUrl . $endpoint;
        $separator = str_contains($url, '?') ? '&' : '?';
        $url .= $separator . http_build_query([
            'key' => $apiKey,
            'token' => $apiToken,
        ]);

        $headers = ['Accept: application/json'];
        $options = [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => true,
            CURLOPT_TIMEOUT => 20,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => $headers,
        ];

        if ($body !== null && !in_array($method, ['GET', 'HEAD'], true)) {
            if (is_array($body)) {
                $options[CURLOPT_POSTFIELDS] = http_build_query($body);
                $headers[] = 'Content-Type: application/x-www-form-urlencoded';
            } else {
                $options[CURLOPT_POSTFIELDS] = (string) $body;
                $headers[] = 'Content-Type: application/json';
            }

            $options[CURLOPT_HTTPHEADER] = $headers;
        }

        $curl = curl_init($url);
        curl_setopt_array($curl, $options);
        $response = curl_exec($curl);

        if ($response === false) {
            $error = curl_error($curl);
            curl_close($curl);

            return $this->response->setStatusCode(502)->setJSON([
                'error' => 'Failed to reach Trello.',
                'details' => $error,
            ]);
        }

        $statusCode = curl_getinfo($curl, CURLINFO_RESPONSE_CODE) ?: 200;
        $headerSize = curl_getinfo($curl, CURLINFO_HEADER_SIZE) ?: 0;
        $contentType = curl_getinfo($curl, CURLINFO_CONTENT_TYPE) ?: 'application/json';
        curl_close($curl);

        $responseBody = substr($response, $headerSize);

        return $this->response
            ->setStatusCode($statusCode)
            ->setContentType($contentType)
            ->setBody($responseBody);
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