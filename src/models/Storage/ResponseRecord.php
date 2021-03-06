<?php
namespace App\Models\Storage;

use App\Models\BaseModel;
/**
 * ResponseRecord Database Model
 * Class ResponseRecord
 * @package App\Models
 */
class ResponseRecord extends BaseModel {
    protected $table = 'response_records'; // Set a Table Name

    public function disaster(){
        return $this->belongsTo('App\Models\Storage\Disaster');
    }

    public function uploads(){
        return $this->morphMany('App\Models\File', 'resource');
    }

    // enable params when called create method.
    protected $fillable = [
        'disaster_id',
        'division',
        'section',
        'status',
        'comments',
    ];
}