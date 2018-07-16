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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Event;

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
     * @return \Illuminate\Http\Response
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

        $file = $request->file('content-file');
        $article->save();
        $content = $request->get("content");
        if ($file) {
            if ($file->getMimeType() == "text/plain") {
                $content = file_get_contents($file->getRealPath());
            } elseif ($file->getMimeType() == "application/msword") {
                //$path = public_path("upload/article/word/" . ($article->id % 10) . "/");
                //$file->move($path, $article->id . ".doc");
            }
        }
        \App\Services\ModelService\Article::saveContent($article->id, $content);
        event(new \App\Events\userActionEvent('\App\Models\Admin\Article', $article->id, 1, '添加了文章:' . $article->title . '(' . $article->id . ')'));
        return redirect('/admin/article/')->withSuccess('添加成功！');
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
     * @return $this|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
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
     * @return $this
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
        $file = $request->file('content-file');
        $content = $request->get("content");
        if ($file) {
            if ($file->getMimeType() == "text/plain") {
                $content = file_get_contents($file->getRealPath());
            } elseif ($file->getMimeType() == "application/msword") {
                //$path = public_path("upload/article/word/" . ($article->id % 10) . "/");
                //$file->move($path, $article->id . ".doc");
            }
        }
        \App\Services\ModelService\Article::saveContent($article->id, $content);
        $article->save();
        event(new \App\Events\userActionEvent('\App\Models\Admin\Article', $article->id, 3, '编辑了文章：' . $article->name));
        return redirect('/admin/article')->withSuccess('修改成功！');
    }

    /**
     * @param $id
     * @return $this
     */
    public function destroy($id)
    {
        $article = Article::find((int)$id);
        if (!$article) return redirect()->back()->withErrors("找不到文章!");
        \App\Services\ModelService\Article::deleteContent($article->id);
        $article->delete();
        event(new \App\Events\userActionEvent('\App\Models\Admin\Article', $article->id, 3, '删除了文章：' . $article->id));
        return redirect()->back()
            ->withSuccess("删除成功");
    }

}