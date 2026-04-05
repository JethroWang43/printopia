<?php
namespace App\Models;

use CodeIgniter\Model;

class discount_model extends Model {
    protected $table = 'discount_tbl';
    protected $primaryKey = 'discount_id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = [
        'eligibility_id','discount_percent','code','selection'
    ];

    protected bool $allowEmptyInserts = false;
}
?>