<?php
/**
 * Created by PhpStorm.
 * User: hp
 * Date: 2018/6/26
 * Time: 15:08
 */

namespace App\Http\Controllers\Admin;


use App\Events\videotypeChangeEvent;
use App\Models\Common\VideosType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;

/**
 * Class VideosTypeController
 * @package App\Http\Controllers\Admin
 */
class VideosTypeController extends BaseController
{
    /**
     * @var array
     */
    protected $fields = [
        'name' => '',
        'pid' => 0,
    ];

    /**
     * @param Request $request
     * @param int $pid
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function index(Request $request, $pid = 0)
    {
        $tree = \App\Services\ModelService\VideosType::getTypeTree();
        return view('admin.videostype.index', compact("tree"));
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

        $data['types'] = VideosType::where(['pid' => 0])->get()->toArray();
        return view('admin.videostype.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param PremissionCreateRequest|Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $videosType = new VideosType();
        if (!$request->get('name')) {
            return redirect()->back()
                ->withErrors("名称不能为空");
        }
        foreach (array_keys($this->fields) as $field) {
            $videosType->$field = $request->get($field, $this->fields[$field]);
        }

        $videosType->save();
        Event::fire(new videotypeChangeEvent());
        event(new \App\Events\userActionEvent('\App\Models\Admin\VideosType', $videosType->id, 1, '添加了视频分类:' . $videosType->name . '(' . $videosType->id . ')'));
        return redirect('/admin/videos-type')->withSuccess('添加成功！');
    }


    /**
     * @param $id
     * @return $this|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $videosType = VideosType::find((int)$id);
        if (!$videosType) return redirect()->back()->withErrors("找不到该项目!");
        foreach (array_keys($this->fields) as $field) {
            $data[$field] = old($field, $videosType->$field);
        }
        $data['id'] = $id;

        $data['types'] = \App\Services\ModelService\VideosType::getTypeTree();
        return view('admin.videostype.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param PermissionUpdateRequest|Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $videosType = VideosType::find((int)$id);
        if ($videosType->pid == 0) {
            unset($this->fields['pid']);
        }
        foreach (array_keys($this->fields) as $field) {
            $videosType->$field = $request->get($field, $this->fields[$field]);
        }
        $videosType->save();
        Event::fire(new videotypeChangeEvent());
        event(new \App\Events\userActionEvent('\App\Models\Admin\VideosType', $videosType->id, 3, '修改了视频分类:' . $videosType->name . '(' . $videosType->id . ')'));

        return redirect('admin/videos-type')->withSuccess('修改成功！');
    }

    /**
     * @param $id
     * @return $this
     */
    public function destroy($id)
    {
        $StartupPage = VideosType::find((int)$id);
        if (!$StartupPage) return redirect()->back()->withErrors("找不到分类!");
        $StartupPage->delete();
        Event::fire(new videotypeChangeEvent());
        event(new \App\Events\userActionEvent('\App\Models\Admin\VideosType', $StartupPage->id, 3, '删除了启动页：' . $StartupPage->id));
        return redirect('admin/videos-type')->withSuccess("删除成功");
    }

}