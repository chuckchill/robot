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
            $data['recordsFiltered'] = StartupPage::count();
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
            $startup->$field = $request->get($field, $this->fields[$field]);
        }
        $img = $request->file('imgsrc');
        if ($img->isValid()) {
            //获取文件的原文件名 包括扩展名
            $yuanname = $img->getClientOriginalName();
            //获取文件的扩展名
            $kuoname = $img->getClientOriginalExtension();

            //获取文件的类型
            $type = $img->getClientMimeType();

            //获取文件的绝对路径，但是获取到的在本地不能打开
            $path = $img->getRealPath();

            //要保存的文件名 时间+扩展名
            $filename = date('Y-m-d-H-i-s') . '_' . uniqid() . '.' . $kuoname;
            //保存文件          配置文件存放文件的名字  ，文件名，路径
            $bool = $img->move(public_path("/upload/startup"), $filename);
        }
        $startup->imgsrc = $filename;
        $startup->save();
        event(new \App\Events\userActionEvent('\App\Models\Admin\StartupPage', $startup->id, 1, '添加了启动页:' . $startup->name . '(' . $startup->id . ')'));
        return redirect('/admin/permission/' . $startup->cid)->withSuccess('添加成功！');
    }

}