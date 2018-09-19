<?php
/**
 * Created by PhpStorm.
 * User: albert
 * Date: 2018/9/17
 * Time: 20:44
 */

namespace App\Models\Common;


use Illuminate\Database\Eloquent\Model;

/**
 * Class Sicker
 * @package App\Models\Common
 */
class Sicker extends Model
{
    /**
     * @var string
     */
    protected $table = 'sicker';
    /**
     * @var string
     */
    protected $dateFormat = 'Y-m-d H:i:s';
}