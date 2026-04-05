<?php
namespace App\Models;

use CodeIgniter\Model;

class eligibilitystatus_model extends Model {
    protected $table = 'eligibility_status_tbl';
    protected $primaryKey = 'eligibility_status_id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = [
        'eligibility_status_name'
    ];

    protected bool $allowEmptyInserts = false;
}
?>