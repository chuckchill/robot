<?php
/**
 * Created by PhpStorm.
 * User: hp
 * Date: 2018/7/6
 * Time: 10:07
 */

namespace App\Models\Common;


use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $table = 'article';

    protected $dateFormat = "Y-m-d H:i:s";

    public function contents()
    {
        return $this->hasOne(ArticleContent::class);
    }
}