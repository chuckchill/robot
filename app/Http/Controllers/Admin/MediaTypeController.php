<?php
/**
 * Created by PhpStorm.
 * User: hp
 * Date: 2018/6/26
 * Time: 15:08
 */

namespace App\Http\Controllers\Admin;


use App\Events\videotypeChangeEvent;
use App\Models\Common\MediaType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;

/**
 * Class MediaTypeController
 * @package App\Http\Controllers\Admin
 */
class MediaTypeController extends BaseController
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
        $tree = \App\Services\ModelService\MediaType::getTypeTree(true);
        return view('admin.mediatype.index', compact("tree"));
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

        $data['types'] = MediaType::where(['pid' => 0])->get()->toArray();
        return view('admin.mediatype.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param PremissionCreateRequest|Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $videosType = new MediaType();
        if (!$request->get('name')) {
            return redirect()->back()
                ->withErrors("名称不能为空");
        }
        foreach (array_keys($this->fields) as $field) {
            $videosType->$field = $request->get($field, $this->fields[$field]);
        }

        $videosType->save();
        Event::fire(new videotypeChangeEvent());
        event(new \App\Events\userActionEvent('\App\Models\Admin\MediaType', $videosType->id, 1, '添加了媒体分类:' . $videosType->name . '(' . $videosType->id . ')'));
        return redirect('/admin/media-type')->withSuccess('添加成功！');
    }


    /**
     * @param $id
     * @return $this|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $videosType = MediaType::find((int)$id);
        if (!$videosType) return redirect()->back()->withErrors("找不到该项目!");
        foreach (array_keys($this->fields) as $field) {
            $data[$field] = old($field, $videosType->$field);
        }
        $data['id'] = $id;
        return view('admin.mediatype.edit', $data);
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
        $videosType = MediaType::find((int)$id);
        foreach (array_keys($this->fields) as $field) {
            $videosType->$field = $request->get($field, $this->fields[$field]);
        }
        $videosType->save();
        Event::fire(new videotypeChangeEvent());
        event(new \App\Events\userActionEvent('\App\Models\Admin\MediaType', $videosType->id, 3, '修改了媒体分类:' . $videosType->name . '(' . $videosType->id . ')'));

        return redirect('admin/media-type')->withSuccess('修改成功！');
    }

    /**
     * @param $id
     * @return $this
     */
    public function destroy($id)
    {
        $mediaType = MediaType::find((int)$id);
        if (!$mediaType) return redirect()->back()->withErrors("找不到分类!");
        if (MediaType::where(['pid'=>$id])->count()) return redirect()->back()->withErrors("该分类下面还有子分类!");
        $mediaType->delete();
        Event::fire(new videotypeChangeEvent());
        event(new \App\Events\userActionEvent('\App\Models\Admin\MediaType', $mediaType->id, 3, '删除了媒体分类：' . $mediaType->id));
        return redirect('admin/media-type')->withSuccess("删除成功");
    }

}