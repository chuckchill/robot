<?php
/**
 * Created by PhpStorm.
 * User: albert
 * Date: 2018/10/1
 * Time: 11:30
 */

namespace App\Models\Api;


use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    protected $table = 'evaluation';
    protected $dateFormat = "Y-m-d H:i:s";
}