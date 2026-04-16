<?php
namespace App\Models;

use CodeIgniter\Model;

class discount_model extends Model {
    protected $table = 'discount_tbl';
    protected $primaryKey = 'discount_id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = [
        'eligibility_id',
        'discount_percent',
        'code',
        'selection',
        'status',
        'category',
        'start_at',
        'end_at',
        'max_uses',
        'one_time_only',
        'updated_at'
    ];

    protected bool $allowEmptyInserts = false;
}
?>