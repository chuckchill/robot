<?php
/**
 * Created by PhpStorm.
 * User: albert
 * Date: 2018/7/28
 * Time: 16:55
 */

namespace App\Http\Controllers\Admin;


use App\Models\Api\DeviceBind;
use App\Models\Api\Devices;
use App\Http\Requests\DevicesCreateRequest;
use DevicesBind;
use Illuminate\Http\Request;

/**
 * Class DevicesController
 * @package App\Http\Controllers\Admin
 */
class DevicesController extends BaseController
{
    protected $fields = [
        'sno' => '',
        'name' => '',
        'status' => '',
    ];

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = array();
            $data['draw'] = $request->get('draw');
            $start = $request->get('start');
            $length = $request->get('length');
            $search = $request->get('search');
            $data['recordsTotal'] = Devices::count();
            $data['recordsFiltered'] = $data['recordsTotal'];
            if (strlen($search['value']) > 0) {
                $data['recordsFiltered'] = Devices::where(function ($query) use ($search) {
                    $query->where('name', 'LIKE', '%' . $search['value'] . '%')
                        ->orWhere('sno', 'like', '%' . $search['value'] . '%');
                })->count();
                $data['data'] = Devices::where(function ($query) use ($search) {
                    $query->where('name', 'LIKE', '%' . $search['value'] . '%')
                        ->orWhere('sno', 'like', '%' . $search['value'] . '%');
                })
                    ->skip($start)->take($length)
                    ->orderBy('id', SORT_DESC)
                    ->get();
            } else {
                $data['recordsFiltered'] = Devices::where(['status' => 1])->count();
                $data['data'] = Devices::skip($start)->take($length)
                    ->orderBy('id', SORT_DESC)
                    ->get();
            }
            return response()->json($data);
        }

        return view('admin.devices.index');
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = [];
        foreach ($this->fields as $field => $default) {
            $data[$field] = old($field, $default);
        }
        return view('admin.devices.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param RoleCreateRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(DevicesCreateRequest $request)
    {
        $device = new Devices();
        foreach (array_keys($this->fields) as $field) {
            $device->$field = $request->get($field);
        }
        $device->save();
        event(new \App\Events\userActionEvent('\App\Models\Admin\Devices', $device->id, 1, "添加设备：" . $device->sno . "{" . $device->id . "}"));
        return redirect('/admin/devices')->withSuccess('添加成功！');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $device = Devices::find($id);
        if (!$device) return redirect()->back()->withErrors("找不到该设备!");
        foreach (array_keys($this->fields) as $field) {
            $data[$field] = old($field, $device->$field);
        }
        $data['id'] = $id;
        return view('admin.devices.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $device = Devices::find((int)$id);
        foreach (array_keys($this->fields) as $field) {
            if ($request->get($field) != "") {
                $device->$field = $request->get($field);
            }
        }
        $device->save();
        event(new \App\Events\userActionEvent('\App\Models\Admin\devices', $device->id, 3, '编辑了设备：' . $device->name));
        return redirect('/admin/devices')->withSuccess('修改成功！');
    }

    public function destroy($id)
    {
        $device = Devices::find((int)$id);
        if (!$device) return redirect()->back()->withErrors("找不到该设备!");
        //$device->delete();
        event(new \App\Events\userActionEvent('\App\Models\Admin\devices', $device->id, 2, '删除了设备：' . $device->id . "({$device->key})"));
        return redirect()->back()
            ->withSuccess("删除成功");
    }

    /**查看设备行情
     * @param $key
     * @return $this|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($key)
    {
        $device = Devices::find($key);
        if (!$device) return redirect()->back()->withErrors("找不到该设备!");
        $deviceBind = DeviceBind::select(['devices_bind.*','app_users_auth.identifier'])
            ->where(['device_id' => $device->id])
            ->leftJoin("app_users_auth", 'app_users_auth.uid', '=', 'devices_bind.uid')
            ->where(['app_users_auth.identity_type' => "sys"])
            ->get();
        return view('admin.devices.show', [
            'deviceBind' => $deviceBind
        ]);
    }

    public function importDevices(Request $request)
    {
        $file = $request->file("devices");
        if (!$file) {
            return redirect()->back()->withErrors("请选择要上传的文件!");
        }
        $content = iconv("GBK", "UTF-8", file_get_contents($file->getRealPath()));
        $data = explode(PHP_EOL, $content);
        for ($i = 1; $i < count($data); $i++) {
            $item = explode(",", $data[$i]);
            if (array_get($item, "0")) {
                $device = Devices::where(["sno" => $item[0]])->first();
                if (!$device) {
                    $device = new Devices();
                }
                $device->sno = $item[1];
                $device->name = array_get($item, "1", "");
                $device->save();
            }
        }
        return redirect("/admin/devices");
    }
}