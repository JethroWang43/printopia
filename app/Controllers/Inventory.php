<?php

namespace App\Controllers;

use App\Models\inventory_model;
use Throwable;

class Inventory extends BaseController
{
    public function list()
    {
        $model = new inventory_model();

        $rows = $model
            ->orderBy('inventory_id', 'DESC')
            ->findAll();

        return $this->response->setJSON([
            'data' => $rows,
        ]);
    }

    public function save()
    {
        try {
            $payload = $this->request->getJSON(true);
            if (!is_array($payload)) {
                $payload = $this->request->getPost();
            }

            $inventoryId = isset($payload['inventory_id']) ? (int) $payload['inventory_id'] : 0;
            $productName = isset($payload['product_name']) ? trim((string) $payload['product_name']) : '';
            $stockQty = isset($payload['stock_qty']) ? (int) $payload['stock_qty'] : 0;
            $reorderLevel = isset($payload['reorder_level']) ? (int) $payload['reorder_level'] : 0;
            $description = isset($payload['description']) ? trim((string) $payload['description']) : '';

            if (mb_strlen($productName) < 1) {
                return $this->response->setStatusCode(422)->setJSON([
                    'error' => 'product_name is required.',
                ]);
            }

            if (mb_strlen($description) < 3) {
                return $this->response->setStatusCode(422)->setJSON([
                    'error' => 'description is required (minimum 3 characters).',
                ]);
            }

            if ($stockQty < 0 || $reorderLevel < 0) {
                return $this->response->setStatusCode(422)->setJSON([
                    'error' => 'stock_qty and reorder_level must be 0 or greater.',
                ]);
            }

            $model = new inventory_model();
            $now = date('Y-m-d H:i:s');

            $record = [
                'product_name' => $productName,
                'stock_qty' => $stockQty,
                'reorder_level' => $reorderLevel,
                'description' => $description,
                'date_updated' => $now,
            ];

            if ($inventoryId <= 0) {
                $record['date_created'] = $now;
                unset($record['date_updated']);

                try {
                    $ok = $model->insert($record, true);
                } catch (Throwable $e) {
                    // Fallback for schemas without the optional description column.
                    unset($record['description']);
                    $ok = $model->insert($record, true);
                }

                if ($ok === false) {
                    $dbError = $model->db->error();
                    return $this->response->setStatusCode(500)->setJSON([
                        'error' => 'Failed to create inventory record.',
                        'details' => $model->errors() ?: ($dbError['message'] ?? null),
                    ]);
                }

                return $this->response->setStatusCode(201)->setJSON([
                    'message' => 'Inventory record created.',
                    'inventory_id' => (int) $ok,
                ]);
            }

            try {
                $ok = $model->update($inventoryId, $record);
            } catch (Throwable $e) {
                unset($record['description']);
                $ok = $model->update($inventoryId, $record);
            }

            if ($ok === false) {
                $dbError = $model->db->error();
                return $this->response->setStatusCode(500)->setJSON([
                    'error' => 'Failed to update inventory record.',
                    'details' => $model->errors() ?: ($dbError['message'] ?? null),
                ]);
            }
        } catch (Throwable $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'error' => 'Server error: ' . $e->getMessage(),
                'details' => $e->getFile() . ':' . $e->getLine(),
            ]);
        }

        return $this->response->setJSON([
            'message' => 'Inventory record updated.',
            'inventory_id' => $inventoryId,
        ]);
    }

    public function delete(int $inventoryId)
    {
        if ($inventoryId <= 0) {
            return $this->response->setStatusCode(422)->setJSON([
                'error' => 'inventory_id is required.',
            ]);
        }

        $model = new inventory_model();
        $ok = $model->delete($inventoryId);

        if ($ok === false) {
            $dbError = $model->db->error();
            return $this->response->setStatusCode(500)->setJSON([
                'error' => 'Failed to delete inventory record.',
                'details' => $model->errors() ?: ($dbError['message'] ?? null),
            ]);
        }

        return $this->response->setJSON([
            'message' => 'Inventory record deleted.',
            'inventory_id' => $inventoryId,
        ]);
    }
}
