@extends('admin.layouts.base')

@section('title','查看视频')

@section('pageHeader','控制面板')

@section('pageDesc','DashBoard')
@section('content')
    <div class="main animsition">
        <div class="container-fluid">

            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">查看设备详情</h3>
                        </div>
                        <div class="panel-body">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>绑定账号</th>
                                    <th>是否监护人</th>
                                    <th>是否激活</th>
                                    <th>绑定时间</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($deviceBind as $item)
                                    <tr>
                                        <td>{{$item->account? $item->account:"未设置"}}</td>
                                        <td>{{$item->is_master==1? "是":"否"}}</td>
                                        <td>{{$item->is_enable==1? "是":"否"}}</td>
                                        <td>{{$item->created_at}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection