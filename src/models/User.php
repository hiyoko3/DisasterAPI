<?php
namespace App\Models;

/**
 * User Database Model
 * Class User
 * @package App\Models
 */
class User extends BaseModel {
    protected $table = 'users'; // Set a Table Name

    // enable params when called create method.
    protected $fillable = [
        'name',
        'user_id',
        'password',
        'division',
        'admin',
    ];
}