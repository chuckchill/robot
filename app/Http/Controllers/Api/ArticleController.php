<?php
/**
 * Created by PhpStorm.
 * User: hp
 * Date: 2018/7/6
 * Time: 13:07
 */

namespace App\Http\Controllers\Api;


use App\Facades\Logger;
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
        $paginate = $request->get('paginate', 'yes');
        $searchName = Helper::Participle($searchName);
        $query = Article::select("title", "id", "created_at", "file_type");
        $query->where(['status' => 1]);
        if ($typeCode) {
            $query->where(['type' => $typeCode]);
        }
        if (count($searchName) > 0) {
            $query->where(function ($query) use ($searchName) {
                foreach ($searchName as $name) {
                    $query->where('title', 'like', '%' . $name . '%');
                }
            });
        }
        if ($paginate == 'no') {
            $data['data'] = $query->get()->toArray();
            $curPage = 1;
        } else {
            $data = $query->paginate(15)->toArray();
            $curPage = $data['current_page'];
        }
        /*foreach ($data['data'] as $key => $items) {
            $data['data'][$key]['url']=\App\Services\ModelService\Article::getArticleSrc($items['id']);
        }*/

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