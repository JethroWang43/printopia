<?php
namespace App\Models;

use CodeIgniter\Model;

class order_model extends Model {
    protected $table = 'order_tbl';
    protected $primaryKey = 'order_id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = [
        'user_id','product_id','3d_design_id','discount_id','transaction_id',
        'order_name','order_type','material_type','description',
        'delivery_method','address','image_file','internal_notes',
        'installments','order_date','total_price','quantity',
        'order_status_id','payment_status_id'
    ];

    protected bool $allowEmptyInserts = false;
}
?>