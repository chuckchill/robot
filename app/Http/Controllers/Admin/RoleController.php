<?php

namespace App\Http\Controllers\Admin;

<<<<<<< HEAD
use App\Models\Admin\Permission;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\RoleCreateRequest;
use App\Http\Requests\RoleUpdateRequest;
use App\Http\Controllers\Controller;
use App\Models\Admin\Role;
use Log;
use Auth;

class RoleController extends Controller
{
    protected $fields = [
        'name' => '',
        'description' => '',
        'permissions' => [],
    ];

=======
use Illuminate\Http\Request;
use App\Http\Requests\RoleRequest;
use App\Http\Requests\PermissionRequest;
use App\Http\Controllers\Controller;
use App\Repositories\Eloquent\RoleRepositoryEloquent as RoleRepository;
use App\Repositories\Eloquent\PermissionRepositoryEloquent as PermissionRepository;

class RoleController extends Controller
{
    public $role;
    public $permission;
    public function __construct(RoleRepository $roleRepository,PermissionRepository $permissionRepository)
    {
        $this->middleware('CheckPermission:role');
        $this->role = $roleRepository;
        $this->permission = $permissionRepository;
    }
>>>>>>> 9f6be1fd51e379122e42c5f5be2d6ce8955c112a

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
<<<<<<< HEAD
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = array();
            $data['draw'] = $request->get('draw');
            $start = $request->get('start');
            $length = $request->get('length');
            $order = $request->get('order');
            $columns = $request->get('columns');
            $search = $request->get('search');
            $data['recordsTotal'] = Role::count();
            if (strlen($search['value']) > 0) {
                $data['recordsFiltered'] = Role::where(function ($query) use ($search) {
                    $query->where('name', 'LIKE', '%' . $search['value'] . '%')
                        ->orWhere('description', 'like', '%' . $search['value'] . '%');
                })->count();
                $data['data'] = Role::where(function ($query) use ($search) {
                    $query->where('name', 'LIKE', '%' . $search['value'] . '%')
                        ->orWhere('description', 'like', '%' . $search['value'] . '%');
                })
                    ->skip($start)->take($length)
                    ->orderBy($columns[$order[0]['column']]['data'], $order[0]['dir'])
                    ->get();
            } else {
                $data['recordsFiltered'] = Role::count();
                $data['data'] = Role::
                skip($start)->take($length)
                    ->orderBy($columns[$order[0]['column']]['data'], $order[0]['dir'])
                    ->get();
            }
            return response()->json($data);
        }
=======
    public function index()
    {
>>>>>>> 9f6be1fd51e379122e42c5f5be2d6ce8955c112a
        return view('admin.role.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
<<<<<<< HEAD
        $data = [];
        foreach ($this->fields as $field => $default) {
            $data[$field] = old($field, $default);
        }
        $arr = Permission::all()->toArray();
        foreach ($arr as $v) {
            $data['permissionAll'][$v['cid']][] = $v;
        }
        return view('admin.role.create', $data);
=======
        $permission = $this->permission->all(['id','display_name']);
        return view('admin.role.create',compact('permission'));
>>>>>>> 9f6be1fd51e379122e42c5f5be2d6ce8955c112a
    }

    /**
     * Store a newly created resource in storage.
<<<<<<< HEAD
     *
     * @param RoleCreateRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(RoleCreateRequest $request)
    {
        // dd($request->get('permission'));
        $role = new Role();
        foreach (array_keys($this->fields) as $field) {
            $role->$field = $request->get($field);
        }
        unset($role->permissions);
        // dd($request->get('permission'));
        $role->save();
        if (is_array($request->get('permissions'))) {
            $role->permissions()->sync($request->get('permissions',[]));
        }
        event(new \App\Events\userActionEvent('\App\Models\Admin\Role',$role->id,1,"用户".auth('admin')->user()->username."{".auth('admin')->user()->id."}添加角色".$role->name."{".$role->id."}"));
        return redirect('/admin/role')->withSuccess('添加成功！');
=======
     * @param Request PermissionRequest
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(RoleRequest $request)
    {
        $this->role->createRole($request->all());
        return redirect('admin/role');
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
        $role = Role::find((int)$id);
        if (!$role) return redirect('/admin/role')->withErrors("找不到该角色!");
        $permissions = [];
        if ($role->permissions) {
            foreach ($role->permissions as $v) {
                $permissions[] = $v->id;
            }
        }
        $role->permissions = $permissions;
        foreach (array_keys($this->fields) as $field) {
            $data[$field] = old($field, $role->$field);
        }
        $arr = Permission::all()->toArray();
        foreach ($arr as $v) {
            $data['permissionAll'][$v['cid']][] = $v;
        }
        $data['id'] = (int)$id;
        return view('admin.role.edit', $data);
=======
        $data = $this->role->editViewData($id);
        return view('admin.role.edit',compact('data'));
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
    public function update(RoleUpdateRequest $request, $id)
    {
        $role = Role::find((int)$id);
        foreach (array_keys($this->fields) as $field) {
            $role->$field = $request->get($field);
        }
        unset($role->permissions);
        $role->save();

        $role->permissions()->sync($request->get('permissions',[]));
        event(new \App\Events\userActionEvent(\App\Models\Admin\Role::class,$role->id,3,"用户".auth('admin')->user()->username."{".auth('admin')->user()->id."}编辑角色".$role->name."{".$role->id."}"));
        return redirect('/admin/role')->withSuccess('修改成功！');
=======
     * @param RoleRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(RoleRequest $request, $id)
    {
        $this->role->updateRole($request->all(),$id);
        return redirect('admin/role');
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
        $role = Role::find((int)$id);
        foreach ($role->users as $v){
            $role->users()->detach($v);
        }

        foreach ($role->permissions as $v){
            $role->permissions()->detach($v);
        }

        if ($role) {
            $role->delete();
        } else {
            return redirect()->back()
                ->withErrors("删除失败");
        }
        event(new \App\Events\userActionEvent('\App\Models\Admin\Role',$role->id,2,"用户".auth('admin')->user()->username."{".auth('admin')->user()->id."}删除角色".$role->name."{".$role->id."}"));
        return redirect()->back()
            ->withSuccess("删除成功");
=======

    }

    public function ajaxIndex(Request $request)
    {
        $result = $this->role->ajaxIndex($request);
        return response()->json($result,JSON_UNESCAPED_UNICODE);
>>>>>>> 9f6be1fd51e379122e42c5f5be2d6ce8955c112a
    }
}
