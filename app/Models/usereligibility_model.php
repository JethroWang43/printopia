<?php
namespace App\Models;

use CodeIgniter\Model;

class usereligibility_model extends Model {
    protected $table = 'user_eligibility_tbl';
    protected $primaryKey = 'eligibility_id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = [
        'discount_id','usage_left','eligibility_status_id',
        'status','granted_at','segment_name_id'
    ];

    protected bool $allowEmptyInserts = false;
}
?>