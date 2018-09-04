<?php
/**
 * Created by PhpStorm.
 * User: hp
 * Date: 2018/7/6
 * Time: 10:04
 */

namespace App\Http\Controllers\Admin;


use App\Events\ArticleEvent;
use App\Models\Common\Article;
use App\Services\Helper;
use App\Services\Qiniu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Event;
use Illuminate\View\View;

/**
 * Class ArticleController
 * @package App\Http\Controllers\Admin
 */
class ArticleController extends BaseController
{
    /**
     * @var array
     */
    protected $fields = [
        'title' => '',
        'name' => '',
        'status' => '',
        'type' => '',
    ];

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = array();
            $data['draw'] = $request->get('draw');
            $start = $request->get('start');
            $length = $request->get('length');
            $data['recordsTotal'] = Article::count();
            $data['recordsFiltered'] = $data['recordsTotal'];
            $data['data'] = Article::skip($start)->take($length)
                ->orderBy('id', SORT_DESC)
                ->get();
            return response()->json($data);
        }

        return view('admin.article.index');

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        foreach ($this->fields as $field => $default) {
            $data[$field] = old($field, $default);
        }
        $data['id'] = 0;
        return view('admin.article.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param PremissionCreateRequest|Request $request
     * @return mixed
     */
    public function store(Request $request)
    {
        $article = new Article();
        foreach (array_keys($this->fields) as $field) {
            if ($request->has($field) && $field != "content") {
                $article->$field = $request->get($field, $this->fields[$field]);
            }
        }
        $article->status = (int)$article->status;
        if (!$article->title) return redirect()->back()->withErrors("标题不能为空!");
        $article->save();
        event(new \App\Events\userActionEvent('\App\Models\Admin\Article', $article->id, 1, '添加了文章:' . $article->title . '(' . $article->id . ')'));
        return redirect('/admin/article/upload-media?articleId=' . $article->id);
    }


    /**
     * @param Request $request
     * @return array
     */
    public function postUploadImage(Request $request)
    {
        $file = $request->file('file');
        if ($file) {
            $path = "/upload/article/images/" . date("Y-m-d") . "/";
            $name = time() . "_" . $file->getClientOriginalName();
            if ($file->move(Helper::mkDir(public_path($path)), $name)) {
                return ["code" => 0, "location" => url($path . $name)];
            }
        }
        return ["code" => 1, "message" => "上传失败"];
    }

    /**
     * @param $id
     * @return mixed
     */
    public function edit($id)
    {
        $article = Article::find((int)$id);
        if (!$article) return redirect()->back()->withErrors("找不到该项目!");
        foreach (array_keys($this->fields) as $field) {
            $data[$field] = old($field, $article->$field);
        }
        $data['id'] = $id;
        return view('admin.article.edit', $data);
    }


    /**
     * @param Request $request
     * @param $id
     * @return mixed
     */
    public function update(Request $request, $id)
    {
        $article = Article::find((int)$id);
        if (!$article) return redirect()->back()->withErrors("找不到该项目!");
        foreach (array_keys($this->fields) as $field) {
            if ($request->has($field) & $field != "content") {
                $article->$field = $request->get($field);
            }
        }
        $article->save();
        event(new \App\Events\userActionEvent('\App\Models\Admin\Article', $article->id, 3, '编辑了文章：' . $article->name));
        return redirect('/admin/article')->withSuccess('修改成功！');
    }

    /**
     * @param Request $request
     * @return array
     */
    public function getUploadMedia(Request $request)
    {
        $articleId = $request->get('articleId');
        $article = Article::find((int)$articleId);
        if (!$article) return redirect("admin/article")->withErrors("找不到该项目!");
        $qn = new Qiniu();
        $returnBody = [
            "key" => "$(key)",
            "hash" => "$(hash)",
            "fsize" => "$(fsize)",
            "fname" => "$(fname)",
        ];
        $policy = array(
            'callbackUrl' => route('qiniu.common-callback'),
            'callbackBody' => json_encode($returnBody),
            'callbackBodyType' => 'application/json',
            'saveKey' => "prad_" . $articleId,
        );
        $token = $qn->getToken(config('qiniu.bucket.article.bucket'), $policy);
        return view('admin.article.add_media', [
            'token' => $token,
            'article' => $article
        ]);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function destroy($id)
    {
        $article = Article::find((int)$id);
        if (!$article) return redirect()->back()->withErrors("找不到文章!");
        \App\Services\ModelService\Article::deleteContent($article->id);
        $key = "prad_" . $id;
        $qn = new Qiniu();
        $qn->deleteKey(config('qiniu.bucket.article.bucket'), $key);
        $article->delete();
        event(new \App\Events\userActionEvent('\App\Models\Admin\Article', $article->id, 3, '删除了文章：' . $article->id));
        return redirect()->back()
            ->withSuccess("删除成功");
    }

    public function postDeleteMedia(Request $request)
    {
        $key = "prad_" . $request->get('articleId');
        $qn = new Qiniu();
        if ($qn->deleteKey(config('qiniu.bucket.article.bucket'), $key)) {
            return ["code" => 1, "message" => "旧文件删除失败"];
        }
        return ["code" => 200, "message" => "删除成功"];
    }

}