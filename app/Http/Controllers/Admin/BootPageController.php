<?php
/**
 * Created by PhpStorm.
 * User: hp
 * Date: 2018/6/7
 * Time: 11:05
 */

namespace App\Http\Controllers\Admin;

use App\Models\Common\BootPage;
use Illuminate\Http\Request;

class BootPageController extends BaseController
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
            $data['recordsTotal'] = BootPage::count();
            $data['recordsFiltered'] = $data['recordsTotal'];
            $data['data'] = BootPage::skip($start)->take($length)
                ->orderBy('id', SORT_DESC)
                ->get();
            return response()->json($data);
        }

        return view('admin.bootpage.index');

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
        return view('admin.bootpage.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param PremissionCreateRequest|Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $startup = new BootPage();
        foreach (array_keys($this->fields) as $field) {
            $startup->$field = $request->get($field, $this->fields[$field]);
        }
        $file = $request->file('imgsrc');
        if (!$file || count($file) > 5) {
            return redirect()->back()
                ->withErrors("图片不能为空或者超过5张");
        }
        $startup->status = (int)$startup->status;
        $startup->imgsrc = $this->uploadFile($file);
        $startup->save();
        event(new \App\Events\userActionEvent('\App\Models\Admin\BootPage', $startup->id, 1, '添加了引导页:' . $startup->name . '(' . $startup->id . ')'));
        return redirect('/admin/boot-page/')->withSuccess('添加成功！');
    }

    public function edit($id)
    {
        $BootPage = BootPage::find((int)$id);
        if (!$BootPage) return redirect()->back()->withErrors("找不到引导页!");
        foreach (array_keys($this->fields) as $field) {
            $data[$field] = old($field, $BootPage->$field);
        }
        $data['id'] = $id;
        return view('admin.startup.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $startup = BootPage::find((int)$id);
        foreach (array_keys($this->fields) as $field) {
            $startup->$field = $request->get($field);
        }
        $file = $request->file('imgsrc');
        if ($file) {
            return redirect()->back()
                ->withErrors("图片不能为空");
        }
        $startup->status = (int)$startup->status;
        $startup->imgsrc = $this->uploadFile($file);
        $startup->save();
        event(new \App\Events\userActionEvent('\App\Models\Admin\BootPage', $startup->id, 3, '编辑了引导页：' . $startup->name));
        return redirect('/admin/boot-page')->withSuccess('修改成功！');
    }

    public function destroy($id)
    {
        $BootPage = BootPage::find((int)$id);
        if (!$BootPage) return redirect()->back()->withErrors("找不到引导页!");
        $BootPage->delete();
        event(new \App\Events\userActionEvent('\App\Models\Admin\BootPage', $BootPage->id, 2, '删除了引导页：' . $BootPage->id));
        return redirect()->back()
            ->withSuccess("删除成功");
    }


    public function uploadFile($files)
    {
        $filenames = [];
        foreach ($files as $file) {
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
                $filename = time() . '_' . uniqid() . '.' . $kuoname;
                //保存文件          配置文件存放文件的名字  ，文件名，路径
                $bool = $file->move(public_path("/upload/boot"), $filename);
            }
            $filenames[] = $filename;
        }
        return implode("@",$filenames);
    }
}