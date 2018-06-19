<?php
/**
 * Created by PhpStorm.
 * User: albert
 * Date: 2018/6/6
 * Time: 22:47
 */

namespace App\Http\Controllers\Admin;


use App\Events\permChangeEvent;
use App\Models\Common\StartupPage;
use Illuminate\Http\Request;

class StartupPageController extends BaseController
{
    protected $fields = [
        'remarks' => '',
        'imgsrc' => '',
        'status' => '',
    ];

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = array();
            $data['draw'] = $request->get('draw');
            $start = $request->get('start');
            $length = $request->get('length');
            $data['recordsTotal'] = StartupPage::count();
            $data['recordsFiltered'] = $data['recordsTotal'];
            $data['data'] = StartupPage::skip($start)->take($length)
                ->orderBy('id', SORT_DESC)
                ->get();
            return response()->json($data);
        }

        return view('admin.startup.index');

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
        return view('admin.startup.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param PremissionCreateRequest|Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $startup = new StartupPage();
        foreach (array_keys($this->fields) as $field) {
            if ($request->has($field)) {
                $startup->$field = $request->get($field, $this->fields[$field]);
            }
        }
        $file = $request->file('imgsrc');
        if (!$file) {
            return redirect()->back()
                ->withErrors("图片不能为空");
        }
        $startup->status = (int)$startup->status;
        $startup->imgsrc = $this->uploadFile($file);
        $startup->save();
        event(new \App\Events\userActionEvent('\App\Models\Admin\StartupPage', $startup->id, 1, '添加了启动页:' . $startup->name . '(' . $startup->id . ')'));
        return redirect('/admin/startup-page/')->withSuccess('添加成功！');
    }

    public function edit($id)
    {
        $StartupPage = StartupPage::find((int)$id);
        if (!$StartupPage) return redirect()->back()->withErrors("找不到该项目!");
        foreach (array_keys($this->fields) as $field) {
            $data[$field] = old($field, $StartupPage->$field);
        }
        $data['id'] = $id;
        return view('admin.startup.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $startup = StartupPage::find((int)$id);
        foreach (array_keys($this->fields) as $field) {
            if ($request->has($field)) {
                $startup->$field = $request->get($field);
            }
        }
        $file = $request->file('imgsrc');
        if ($file) {
            $startup->imgsrc = $this->uploadFile($file);
        }
        $startup->status = (int)$startup->status;
        $startup->save();
        event(new \App\Events\userActionEvent('\App\Models\Admin\StartupPage', $startup->id, 3, '编辑了启动页：' . $startup->name));
        return redirect('/admin/startup-page')->withSuccess('修改成功！');
    }

    public function destroy($id)
    {
        $StartupPage = StartupPage::find((int)$id);
        if (!$StartupPage) return redirect()->back()->withErrors("找不到启动页!");
        $StartupPage->delete();
        event(new \App\Events\userActionEvent('\App\Models\Admin\StartupPage', $StartupPage->id, 3, '删除了启动页：' . $StartupPage->id));
        return redirect()->back()
            ->withSuccess("删除成功");
    }


    public function uploadFile($file)
    {
        if ($file->isValid()) {
            //获取文件的原文件名 包括扩展名
            $yuanname = $file->getClientOriginalName();
            //获取文件的扩展名
            $kuoname = $file->getClientOriginalExtension();

            //获取文件的类型
            $type = $file->getClientMimeType();

            //获取文件的绝对路径，但是获取到的在本地不能打开
            $path = $file->getRealPath();

            //要保存的文件名 时间+扩展名
            $filename = "startup_" . time() . '_' . uniqid() . '.' . $kuoname;
            //保存文件          配置文件存放文件的名字  ，文件名，路径
            $bool = $file->move(public_path("/upload/startup"), $filename);
        }
        return $filename;
    }

}