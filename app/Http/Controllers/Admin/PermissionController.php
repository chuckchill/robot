<?php

namespace App\Http\Controllers\Admin;

<<<<<<< HEAD
use App\Events\permChangeEvent;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\PermissionCreateRequest;
use App\Http\Requests\PermissionUpdateRequest;
use App\Http\Controllers\Controller;
use App\Models\Admin\Permission;
use Cache, Event;
use Illuminate\Support\Facades\URL;

class PermissionController extends Controller
{
    protected $fields = [
        'name' => '',
        'label' => '',
        'description' => '',
        'cid' => 0,
        'icon' => '',
    ];

=======
use Illuminate\Http\Request;
use App\Http\Requests\PermissionRequest;
use App\Http\Controllers\Controller;
use App\Repositories\Eloquent\PermissionRepositoryEloquent as PermissionRepository;

class PermissionController extends Controller
{
    public $permission;

    public function __construct(PermissionRepository $permissionRepository)
    {
        $this->middleware('CheckPermission:permission');
        $this->permission = $permissionRepository;
    }
>>>>>>> 9f6be1fd51e379122e42c5f5be2d6ce8955c112a

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
<<<<<<< HEAD
    public function index(Request $request, $cid = 0)
    {
        $cid = (int)$cid;
        if ($request->ajax()) {
            $data = array();
            $data['draw'] = $request->get('draw');
            $start = $request->get('start');
            $length = $request->get('length');
            $order = $request->get('order');
            $columns = $request->get('columns');
            $search = $request->get('search');
            $cid = $request->get('cid', 0);
            $data['recordsTotal'] = Permission::where('cid', $cid)->count();
            if (strlen($search['value']) > 0) {
                $data['recordsFiltered'] = Permission::where('cid', $cid)->where(function ($query) use ($search) {
                    $query
                        ->where('name', 'LIKE', '%' . $search['value'] . '%')
                        ->orWhere('description', 'like', '%' . $search['value'] . '%')
                        ->orWhere('label', 'like', '%' . $search['value'] . '%');
                })->count();
                $data['data'] = Permission::where('cid', $cid)->where(function ($query) use ($search) {
                    $query->where('name', 'LIKE', '%' . $search['value'] . '%')
                        ->orWhere('description', 'like', '%' . $search['value'] . '%')
                        ->orWhere('label', 'like', '%' . $search['value'] . '%');
                })
                    ->skip($start)->take($length)
                    ->orderBy($columns[$order[0]['column']]['data'], $order[0]['dir'])
                    ->get();
            } else {
                $data['recordsFiltered'] = Permission::where('cid', $cid)->count();
                $data['data'] = Permission::where('cid', $cid)->
                skip($start)->take($length)
                    ->orderBy($columns[$order[0]['column']]['data'], $order[0]['dir'])
                    ->get();
            }

            return response()->json($data);
        }
        $datas['cid'] = $cid;
        if ($cid > 0) {
            $datas['data'] = Permission::find($cid);
        }

        return view('admin.permission.index', $datas);
=======
    public function index()
    {
        return view('admin.permission.index');
>>>>>>> 9f6be1fd51e379122e42c5f5be2d6ce8955c112a
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
<<<<<<< HEAD
    public function create($cid)
    {
        $data = [];
        foreach ($this->fields as $field => $default) {
            $data[$field] = old($field, $default);
        }
        $data['cid'] = $cid;

        return view('admin.permission.create', $data);
=======
    public function create()
    {
        return view('admin.permission.create');
>>>>>>> 9f6be1fd51e379122e42c5f5be2d6ce8955c112a
    }

    /**
     * Store a newly created resource in storage.
<<<<<<< HEAD
     *
     * @param PremissionCreateRequest|Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(PermissionCreateRequest $request)
    {
        $permission = new Permission();
        try {
            URL::route($request->get('name'));
        } catch (\Exception $exception) {
            return redirect()->back()->withErrors("路由{$request->get('name')}不存在！");
        }
        foreach (array_keys($this->fields) as $field) {
            $permission->$field = $request->get($field, $this->fields[$field]);
        }
        $permission->save();
        Event::fire(new permChangeEvent());
        event(new \App\Events\userActionEvent('\App\Models\Admin\Permission', $permission->id, 1, '添加了权限:' . $permission->name . '(' . $permission->label . ')'));

        return redirect('/admin/permission/' . $permission->cid)->withSuccess('添加成功！');
=======
     * @param Request PermissionRequest
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(PermissionRequest $request)
    {
        $this->permission->createPermission($request->all());
        return redirect('admin/permission');
>>>>>>> 9f6be1fd51e379122e42c5f5be2d6ce8955c112a
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
<<<<<<< HEAD
        $permission = Permission::find((int)$id);
        if (!$permission) return redirect('/admin/permission')->withErrors("找不到该权限!");
        $data = ['id' => (int)$id];
        foreach (array_keys($this->fields) as $field) {
            $data[$field] = old($field, $permission->$field);
        }

        //dd($data);
        return view('admin.permission.edit', $data);
=======
        $permission = $this->permission->find($id,['id','name','display_name','description'])->toArray();
        return view('admin.permission.edit',compact('permission'));
>>>>>>> 9f6be1fd51e379122e42c5f5be2d6ce8955c112a
    }

    /**
     * Update the specified resource in storage.
<<<<<<< HEAD
     *
     * @param PermissionUpdateRequest|Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(PermissionUpdateRequest $request, $id)
    {
        $permission = Permission::find((int)$id);
        foreach (array_keys($this->fields) as $field) {
            $permission->$field = $request->get($field, $this->fields[$field]);
        }
        $permission->save();
        Event::fire(new permChangeEvent());
        event(new \App\Events\userActionEvent('\App\Models\Admin\Permission', $permission->id, 3, '修改了权限:' . $permission->name . '(' . $permission->label . ')'));

        return redirect('admin/permission/' . $permission->cid)->withSuccess('修改成功！');
=======
     * @param MenuRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(PermissionRequest $request, $id)
    {
        $this->permission->updatePermission($request->all(),$id);
        return redirect('admin/permission');
>>>>>>> 9f6be1fd51e379122e42c5f5be2d6ce8955c112a
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
<<<<<<< HEAD
        $child = Permission::where('cid', $id)->first();

        if ($child) {
            return redirect()->back()
                ->withErrors("请先将该权限的子权限删除后再做删除操作!");
        }
        $tag = Permission::find((int)$id);
        foreach ($tag->roles as $v) {
            $tag->roles()->detach($v->id);
        }
        if ($tag) {
            $tag->delete();
        } else {
            return redirect()->back()
                ->withErrors("删除失败");
        }
        Event::fire(new permChangeEvent());
        event(new \App\Events\userActionEvent('\App\Models\Admin\Permission', $tag->id, 2, '删除了权限:' . $tag->name . '(' . $tag->label . ')'));

        return redirect()->back()
            ->withSuccess("删除成功");
=======

    }

    public function ajaxIndex(Request $request)
    {
        $result = $this->permission->ajaxIndex($request);
        return response()->json($result,JSON_UNESCAPED_UNICODE);
>>>>>>> 9f6be1fd51e379122e42c5f5be2d6ce8955c112a
    }
}
