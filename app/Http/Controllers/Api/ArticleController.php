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
use App\Services\Helper;
use Illuminate\Http\Request;

/**
 * Class ArticleController
 * @package App\Http\Controllers\Api
 */
class ArticleController extends BaseController
{
    /**
     * @param Request $request
     * @return mixed
     */
    public function getArticle(Request $request)
    {
        $typeCode = $request->get('type_code');
        $searchName = $request->get('name');
        $searchName = Helper::Participle($searchName);
        $query = Article::select("title", "id", "created_at", "file_type");
        $query->where(['status' => 1]);
        if ($typeCode) {
            $query->where(['type' => $typeCode]);
        }
        if (count($searchName) > 0) {
            foreach ($searchName as $name) {
                $query->orWhere('title', 'like', '%' . $name . '%');
            }
        }
        $data = $query->paginate(15)->toArray();
        /*foreach ($data['data'] as $key => $items) {
            $data['data'][$key]['url']=\App\Services\ModelService\Article::getArticleSrc($items['id']);
        }*/
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

    /**获取文件资源
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getArticleSrc(Request $request)
    {
        $articleId = $request->get('article_id');
        $src = \App\Services\ModelService\Article::getArticleSrc($articleId);
        return $this->response->array([
            'code' => 0,
            'message' => '查询成功',
            'data' => [
                'src' => $src,
            ]
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getArticleContent(Request $request)
    {
        $articleId = $request->get('article_id');
        $content = \App\Services\ModelService\Article::getContent($articleId);
        return view("article", compact("content"));
    }
}