<?php
/**
 * Created by PhpStorm.
 * User: hp
 * Date: 2018/7/6
 * Time: 13:07
 */

namespace App\Http\Controllers\Api;


use App\Models\Common\Article;
use App\Models\Common\ArticleContent;
use Illuminate\Http\Request;

class ArticleController extends BaseController
{
    public function getArticle(Request $request)
    {
        $typeCode = $request->get('type_code');
        $searchName = $request->get('search');
        $query = Article::select("title", "id", "created_at");
        $query->where(['status' => 1]);
        if ($typeCode) {
            $query->where(['type' => $typeCode]);
        }
        if ($searchName) {
            $query->where('title', 'like', $searchName . '%');
        }
        $data = $query->paginate(15)->toArray();
        $curPage = $data['current_page'];
        $items = $data['data'];
        return $this->response->array([
            'code' => 0,
            'message' => '查询成功',
            'data' => [
                'article' => $items,
                'current_page' => $curPage
            ]
        ]);
    }

    public function getArticleContent(Request $request)
    {
        $articleId = $request->get('article_id');
        $wordPath = \App\Services\ModelService\Article::getWordPath($articleId);
        if (file_exists(public_path($wordPath . $articleId . ".doc"))) {
            $type = "word";
            $content = url($wordPath . $articleId . ".doc");
        } else {
            $type = "web";
            $content = \App\Services\ModelService\Article::getContent($articleId);
        }

        return $this->response->array([
            'code' => 0,
            'message' => '查询成功',
            'data' => [
                'type' => 'web',
                'content' => $content,
            ]
        ]);
    }
}