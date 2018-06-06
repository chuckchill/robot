<?php
/**
 * Created by PhpStorm.
 * User: hp
 * Date: 2018/5/29
 * Time: 17:34
 */

namespace App\Http\Controllers\Admin;


use App\Models\Admin\AdminLog;
use Illuminate\Http\Request;

class LogController extends BaseController
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = array();
            $data['draw'] = $request->get('draw');
            $start = $request->get('start');
            $length = $request->get('length');
            $search = $request->get('search');
            $data['recordsTotal'] = AdminLog::count();
            if (strlen($search['value']) > 0) {
                $data['recordsFiltered'] = AdminLog::where(function ($query) use ($search) {
                    $query->where('name', 'LIKE', '%' . $search['value'] . '%')
                        ->orWhere('remarks', 'like', '%' . $search['value'] . '%');
                })->count();
                $data['data'] = AdminLog::where(function ($query) use ($search) {
                    $query->where('name', 'LIKE', '%' . $search['value'] . '%')
                        ->orWhere('remarks', 'like', '%' . $search['value'] . '%');
                })
                    ->skip($start)->take($length)
                    ->orderBy('id', SORT_DESC)
                    ->get();
            } else {
                $data['recordsFiltered'] = AdminLog::count();
                $data['data'] = AdminLog::skip($start)->take($length)
                    ->orderBy('id', SORT_DESC)
                    ->get();
            }

            return response()->json($data);
        }

        return view('admin.log.index');
    }
}