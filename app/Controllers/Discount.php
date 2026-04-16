<?php

namespace App\Controllers;

use App\Models\discount_model;
use App\Models\User_model;
use App\Models\usereligibility_model;
use Throwable;

class Discount extends BaseController
{
    public function list()
    {
        $model = new discount_model();

        $search = trim((string) ($this->request->getGet('search') ?? ''));
        $status = trim((string) ($this->request->getGet('status') ?? 'all'));
        $category = trim((string) ($this->request->getGet('category') ?? 'all'));

        $builder = $model->builder();
        $builder->select('discount_tbl.discount_id, discount_tbl.code, discount_tbl.status, discount_tbl.category, discount_tbl.discount_percent, discount_tbl.discount_type, discount_tbl.discount_value, discount_tbl.shipping_min_value, discount_tbl.segment_type, discount_tbl.segment_min_spend, discount_tbl.segment_min_metric, discount_tbl.max_uses, discount_tbl.selection, discount_tbl.start_at, discount_tbl.end_at, discount_tbl.one_time_only, ue.status AS eligibility_type, ue.segment_name_id');
        $builder->join('user_eligibility_tbl ue', 'ue.discount_id = discount_tbl.discount_id', 'left');

        if ($search !== '') {
            $builder->like('code', $search);
        }

        if ($status !== '' && $status !== 'all') {
            $builder->where('status', $status);
        }

        if ($category !== '' && $category !== 'all') {
            $builder->where('category', $category);
        }

        $builder->orderBy('discount_id', 'DESC');
        $rows = $builder->get()->getResultArray();

        return $this->response->setJSON([
            'data' => $rows,
        ]);
    }

    public function users()
    {
        $query = trim((string) ($this->request->getGet('q') ?? ''));
        $userModel = new User_model();
        $builder = $userModel->builder();

        $builder->select('user_id, first_name, middle_name, last_name, email');

        if ($query !== '') {
            $builder->groupStart()
                ->like('first_name', $query)
                ->orLike('last_name', $query)
                ->orLike('email', $query)
                ->groupEnd();
        }

        $builder->orderBy('first_name', 'ASC');
        $builder->limit(10);

        $rows = $builder->get()->getResultArray();

        $data = array_map(static function (array $row): array {
            $fullName = trim(($row['first_name'] ?? '') . ' ' . ($row['last_name'] ?? ''));

            return [
                'user_id' => (int) $row['user_id'],
                'name' => $fullName !== '' ? $fullName : 'User #' . $row['user_id'],
                'email' => $row['email'] ?? '',
                'label' => ($fullName !== '' ? $fullName : 'User #' . $row['user_id']) . (($row['email'] ?? '') !== '' ? ' (' . $row['email'] . ')' : ''),
            ];
        }, $rows);

        return $this->response->setJSON([
            'data' => $data,
        ]);
    }

    public function save()
    {
        try {
            $payload = $this->request->getJSON(true);
            if (!is_array($payload)) {
                $payload = $this->request->getPost();
            }

            $discountId = isset($payload['discount_id']) ? (int) $payload['discount_id'] : 0;
            $code = strtoupper(trim((string) ($payload['code'] ?? '')));
            $discountType = trim((string) ($payload['discount_type'] ?? 'discount'));
            $discountPercent = isset($payload['discount_percent']) ? (float) $payload['discount_percent'] : 0;
            $discountValue = isset($payload['discount_value']) ? (float) $payload['discount_value'] : $discountPercent;
            $shippingMinValue = isset($payload['shipping_min_value']) && $payload['shipping_min_value'] !== ''
                ? (float) $payload['shipping_min_value']
                : null;
            $segmentType = trim((string) ($payload['segment_type'] ?? ($payload['segment_condition_type'] ?? '')));
            $segmentMinSpend = isset($payload['segment_min_spend'])
                ? (float) $payload['segment_min_spend']
                : (isset($payload['segment_condition_min_spend']) ? (float) $payload['segment_condition_min_spend'] : null);
            $segmentMinMetric = isset($payload['segment_min_metric'])
                ? (int) $payload['segment_min_metric']
                : (isset($payload['segment_condition_min_orders']) && $payload['segment_condition_min_orders'] !== null
                    ? (int) $payload['segment_condition_min_orders']
                    : (isset($payload['segment_condition_min_bulk_qty']) && $payload['segment_condition_min_bulk_qty'] !== null
                        ? (int) $payload['segment_condition_min_bulk_qty']
                        : null));
            $selection = trim((string) ($payload['selection'] ?? 'code'));
            $status = trim((string) ($payload['status'] ?? 'active'));
            $category = trim((string) ($payload['category'] ?? 'general'));
            $startAt = trim((string) ($payload['start_at'] ?? ''));
            $endAt = trim((string) ($payload['end_at'] ?? ''));
            $maxUses = isset($payload['max_uses']) && $payload['max_uses'] !== '' ? (int) $payload['max_uses'] : null;
            $oneTimeOnly = !empty($payload['one_time_only']) ? 1 : 0;
            $eligibilityType = trim((string) ($payload['eligibility_type'] ?? 'all'));
            $segmentNameId = trim((string) ($payload['segment_name_id'] ?? ''));
            $specificCustomer = trim((string) ($payload['specific_customer'] ?? ''));
            $specificCustomerId = isset($payload['specific_customer_id']) && $payload['specific_customer_id'] !== ''
                ? (int) $payload['specific_customer_id']
                : null;
            $eligibilityStatusId = isset($payload['eligibility_status_id']) && $payload['eligibility_status_id'] !== ''
                ? (int) $payload['eligibility_status_id']
                : 1;

            $errors = [];

            if ($selection === 'code' && $code === '') {
                $errors[] = 'Discount code is required for code-based discounts.';
            }

            if ($discountType === 'discount' && ($discountPercent <= 0 || $discountPercent > 100)) {
                $errors[] = 'Discount percent must be greater than 0 and less than or equal to 100.';
            }

            if ($discountType === 'free_shipping' && $discountValue <= 0) {
                $errors[] = 'Discount amount must be greater than 0 for free shipping option.';
            }

            if ($discountType === 'free_shipping' && ($shippingMinValue === null || $shippingMinValue < 0)) {
                $errors[] = 'Shipping minimum value must be 0 or higher.';
            }

            if ($startAt !== '' && $endAt !== '') {
                $startTs = strtotime($startAt);
                $endTs = strtotime($endAt);

                if ($startTs !== false && $endTs !== false && $endTs < $startTs) {
                    $errors[] = 'End date must be later than start date.';
                }
            }

            if ($maxUses !== null && $maxUses <= 0) {
                $errors[] = 'Max uses must be greater than 0.';
            }

            if ($maxUses === null && $oneTimeOnly !== 1) {
                $errors[] = 'Please choose a usage option: Limit of total Uses or One time use ONLY.';
            }

            if (!empty($errors)) {
                return $this->response->setStatusCode(422)->setJSON([
                    'error' => implode(' ', $errors),
                ]);
            }

            $record = [
                'code' => $code,
                'discount_percent' => $discountPercent,
                'discount_type' => $discountType,
                'discount_value' => $discountValue,
                'shipping_min_value' => $shippingMinValue,
                'segment_type' => $segmentType !== '' ? $segmentType : null,
                'segment_min_spend' => $segmentMinSpend,
                'segment_min_metric' => $segmentMinMetric,
                'selection' => $selection,
                'status' => $status,
                'category' => $category,
                'start_at' => $startAt !== '' ? date('Y-m-d H:i:s', strtotime($startAt)) : null,
                'end_at' => $endAt !== '' ? date('Y-m-d H:i:s', strtotime($endAt)) : null,
                'max_uses' => $maxUses,
                'one_time_only' => $oneTimeOnly,
                'updated_at' => date('Y-m-d H:i:s'),
            ];

            $model = new discount_model();

            if ($discountId > 0) {
                $ok = $model->update($discountId, $record);

                if ($ok === false) {
                    $dbError = $model->db->error();
                    return $this->response->setStatusCode(500)->setJSON([
                        'error' => 'Failed to update discount.',
                        'details' => $model->errors() ?: ($dbError['message'] ?? null),
                    ]);
                }

                $this->upsertEligibility(
                    $discountId,
                    $eligibilityType,
                    $segmentNameId,
                    $specificCustomer,
                    $specificCustomerId,
                    $eligibilityStatusId,
                    $maxUses,
                    $oneTimeOnly
                );

                return $this->response->setJSON([
                    'message' => 'Discount updated.',
                    'discount_id' => $discountId,
                ]);
            }

            $newId = $model->insert($record, true);
            if ($newId === false) {
                $dbError = $model->db->error();
                return $this->response->setStatusCode(500)->setJSON([
                    'error' => 'Failed to create discount.',
                    'details' => $model->errors() ?: ($dbError['message'] ?? null),
                ]);
            }

            $eligibilityId = $this->upsertEligibility(
                (int) $newId,
                $eligibilityType,
                $segmentNameId,
                $specificCustomer,
                $specificCustomerId,
                $eligibilityStatusId,
                $maxUses,
                $oneTimeOnly
            );

            if ($eligibilityId !== null) {
                $model->update((int) $newId, ['eligibility_id' => $eligibilityId]);
            }

            return $this->response->setStatusCode(201)->setJSON([
                'message' => 'Discount created.',
                'discount_id' => (int) $newId,
            ]);
        } catch (Throwable $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'error' => 'Server error: ' . $e->getMessage(),
            ]);
        }
    }

    public function delete(int $discountId)
    {
        if ($discountId <= 0) {
            return $this->response->setStatusCode(422)->setJSON([
                'error' => 'discount_id is required.',
            ]);
        }

        $model = new discount_model();
        $ok = $model->delete($discountId);

        if ($ok === false) {
            $dbError = $model->db->error();
            return $this->response->setStatusCode(500)->setJSON([
                'error' => 'Failed to delete discount.',
                'details' => $model->errors() ?: ($dbError['message'] ?? null),
            ]);
        }

        return $this->response->setJSON([
            'message' => 'Discount deleted.',
            'discount_id' => $discountId,
        ]);
    }

    private function upsertEligibility(
        int $discountId,
        string $eligibilityType,
        string $segmentNameId,
        string $specificCustomer,
        ?int $specificCustomerId,
        int $eligibilityStatusId,
        ?int $maxUses,
        int $oneTimeOnly
    ): ?int {
        $eligibilityModel = new usereligibility_model();
        $existing = $eligibilityModel->where('discount_id', $discountId)->first();

        $segmentValue = 'all';
        if ($eligibilityType === 'segment' && $segmentNameId !== '') {
            $segmentValue = $segmentNameId;
        } elseif ($eligibilityType === 'specific' && $specificCustomerId !== null) {
            $segmentValue = 'user:' . $specificCustomerId;
        } elseif ($eligibilityType === 'specific' && $specificCustomer !== '') {
            // Supabase schema currently has no user_id field on user_eligibility_tbl,
            // so we temporarily store a user reference string in segment_name_id.
            $segmentValue = $specificCustomer;
        }

        $usageLeft = 0;
        if ($oneTimeOnly === 1) {
            $usageLeft = 1;
        } elseif ($maxUses !== null && $maxUses > 0) {
            $usageLeft = $maxUses;
        }

        $data = [
            'discount_id' => $discountId,
            'usage_left' => $usageLeft,
            'eligibility_status_id' => $eligibilityStatusId,
            'status' => $eligibilityType,
            'granted_at' => date('Y-m-d H:i:s'),
            'segment_name_id' => $segmentValue,
        ];

        if ($existing) {
            $eligibilityModel->update((int) $existing['eligibility_id'], $data);
            return (int) $existing['eligibility_id'];
        }

        $insertedId = $eligibilityModel->insert($data, true);
        if ($insertedId === false) {
            return null;
        }

        return (int) $insertedId;
    }

    public function saveShipping()
    {
        try {
            $payload = $this->request->getJSON(true);
            if (!is_array($payload)) {
                $payload = $this->request->getPost();
            }

            $minOrderValue = isset($payload['min_order_value']) ? (float) $payload['min_order_value'] : 0;
            $status = trim((string) ($payload['status'] ?? 'active'));
            $startAt = trim((string) ($payload['start_at'] ?? ''));
            $endAt = trim((string) ($payload['end_at'] ?? ''));
            $eligibilityType = trim((string) ($payload['eligibility_type'] ?? 'all'));
            $segmentNameId = trim((string) ($payload['segment_name_id'] ?? ''));
            $specificCustomer = trim((string) ($payload['specific_customer'] ?? ''));
            $specificCustomerId = isset($payload['specific_customer_id']) && $payload['specific_customer_id'] !== ''
                ? (int) $payload['specific_customer_id']
                : null;
            $eligibilityStatusId = isset($payload['eligibility_status_id']) && $payload['eligibility_status_id'] !== ''
                ? (int) $payload['eligibility_status_id']
                : 1;

            $errors = [];

            if ($minOrderValue < 0) {
                $errors[] = 'Minimum order value must be greater than or equal to 0.';
            }

            if ($startAt !== '' && $endAt !== '') {
                $startTs = strtotime($startAt);
                $endTs = strtotime($endAt);

                if ($startTs !== false && $endTs !== false && $endTs < $startTs) {
                    $errors[] = 'End date must be later than start date.';
                }
            }

            if (!empty($errors)) {
                return $this->response->setStatusCode(422)->setJSON([
                    'error' => implode(' ', $errors),
                ]);
            }

            $record = [
                'min_order_value' => $minOrderValue,
                'status' => $status,
                'start_at' => $startAt !== '' ? date('Y-m-d H:i:s', strtotime($startAt)) : null,
                'end_at' => $endAt !== '' ? date('Y-m-d H:i:s', strtotime($endAt)) : null,
                'updated_at' => date('Y-m-d H:i:s'),
            ];

            // For now, create a simple shipping record in the database
            // This will be updated when you provide the final schema
            $db = \Config\Database::connect();
            $result = $db->table('free_shipping_tbl')->insert($record);

            if (!$result) {
                $dbError = $db->error();
                return $this->response->setStatusCode(500)->setJSON([
                    'error' => 'Failed to create free shipping rule.',
                    'details' => $dbError['message'] ?? null,
                ]);
            }

            $shippingId = $db->insertID();

            // Store eligibility information
            $segmentValue = 'all';
            if ($eligibilityType === 'segment' && $segmentNameId !== '') {
                $segmentValue = $segmentNameId;
            } elseif ($eligibilityType === 'specific' && $specificCustomerId !== null) {
                $segmentValue = 'user:' . $specificCustomerId;
            } elseif ($eligibilityType === 'specific' && $specificCustomer !== '') {
                $segmentValue = $specificCustomer;
            }

            $eligibilityData = [
                'shipping_id' => $shippingId,
                'eligibility_status_id' => $eligibilityStatusId,
                'status' => $eligibilityType,
                'granted_at' => date('Y-m-d H:i:s'),
                'segment_name_id' => $segmentValue,
            ];

            $db->table('shipping_eligibility_tbl')->insert($eligibilityData);

            return $this->response->setStatusCode(201)->setJSON([
                'message' => 'Free shipping rule created.',
                'shipping_id' => $shippingId,
            ]);
        } catch (Throwable $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'error' => 'Server error: ' . $e->getMessage(),
            ]);
        }
    }
}
