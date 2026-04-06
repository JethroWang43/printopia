<?php
namespace App\Models;

use CodeIgniter\Model;

class product_model extends Model {
    protected $table = 'product_tbl';
    protected $primaryKey = 'product_id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = [
        'product_type_id','product_name','product_image',
        'base_price','date_created','date_updated'
    ];

    protected bool $allowEmptyInserts = false;
}
?>