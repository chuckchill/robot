<?php
/**
 * Created by PhpStorm.
 * User: hp
 * Date: 2018/7/6
 * Time: 10:04
 */

namespace App\Http\Controllers\Admin;


use App\Models\Common\Article;
use App\Models\Common\ArticleContent;
use Illuminate\Http\Request;

class ArticleController extends BaseController
{
    protected $fields = [
        'title' => '',
        'name' => '',
        'status' => '',
        'content' => '',
        'type' => '',
    ];

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
        $contentObj = new ArticleContent(['content' => $request->get('content')]);

        if (!$article->title) return redirect()->back()->withErrors("标题不能为空!");
        if (!$contentObj->content) return redirect()->back()->withErrors("文章内容不能为空!");
        $article->save();
        $article->contents()->save($contentObj);
        event(new \App\Events\userActionEvent('\App\Models\Admin\Article', $article->id, 1, '添加了文章:' . $article->title . '(' . $article->id . ')'));
        return redirect('/admin/article/')->withSuccess('添加成功！');
    }

    public function edit($id)
    {
        $article = Article::find((int)$id);
        if (!$article) return redirect()->back()->withErrors("找不到该项目!");
        foreach (array_keys($this->fields) as $field) {
            $data[$field] = old($field, $article->$field);
        }
        $data['content'] = $article->contents->content;
        $data['id'] = $id;
        return view('admin.article.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $article = Article::find((int)$id);
        if (!$article) return redirect()->back()->withErrors("找不到该项目!");
        foreach (array_keys($this->fields) as $field) {
            if ($request->has($field) & $field != "content") {
                $article->$field = $request->get($field);
            }
        }
        $content = $request->get('content');
        if (!$article->title) return redirect()->back()->withErrors("标题不能为空!");
        if (!$content) return redirect()->back()->withErrors("文章内容不能为空!");

        $article->save();
        $article->contents->update(['content' => $content]);
        event(new \App\Events\userActionEvent('\App\Models\Admin\Article', $article->id, 3, '编辑了文章：' . $article->name));
        return redirect('/admin/article')->withSuccess('修改成功！');
    }

    public function destroy($id)
    {
        $article = Article::find((int)$id);
        if (!$article) return redirect()->back()->withErrors("找不到文章!");
        $article->delete();
        $article->contents->delete();
        event(new \App\Events\userActionEvent('\App\Models\Admin\Article', $article->id, 3, '删除了文章：' . $article->id));
        return redirect()->back()
            ->withSuccess("删除成功");
    }

}