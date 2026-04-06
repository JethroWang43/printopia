<?php
namespace App\Models;

use CodeIgniter\Model;

class producttype_model extends Model {
    protected $table = 'product_type_tbl';
    protected $primaryKey = 'product_type_id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = ['type_name'];

    protected bool $allowEmptyInserts = false;
}
?>