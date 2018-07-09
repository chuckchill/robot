@extends('admin.layouts.base')

@section('title','媒体分类')

@section('pageHeader','控制面板')

@section('pageDesc','DashBoard')

@section('content')
    <div class="row page-title-row" id="dangqian" style="margin:5px;">
        <div class="col-md-12 text-right">
            @if(Gate::forUser(auth('admin')->user())->check('admin.media-type.create'))
                <a href="/admin/media-type/create" class="btn btn-success btn-md"><i
                            class="fa fa-plus-circle"></i> 添加分类 </a>
            @endif
        </div>
    </div>


    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    分类管理:
                </div>
                @include('admin.partials.errors')
                @include('admin.partials.success')
                <div class="box-body">
                    <ul class="list-group">
                        @foreach($tree as $item)
                            @if($item['level']==0)
                                <li class="list-group-item list-group-item-success">
                                    {{$item['name']}}
                                    <a href="/admin/media-type/{{$item['id']}}/edit" class="pull-right label-info label">修改</a>
                                </li>
                            @else
                                <li style="padding-left: {{$item['level']*40}}px"
                                    class="list-group-item list-group-item-info">
                                    {{$item['name']}}
                                    <a href="/admin/media-type/{{$item['id']}}/edit"  class="pull-right label-info label">修改</a>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://cdn.bootcss.com/bootstrap-treeview/1.2.0/bootstrap-treeview.min.js"></script>
    <script>
        $('div#toggle').on("click", function () {
            var active = $(this).hasClass("active")
            var parent = $(this).parent();
            console.log(active)
            if (active) {
                $(this).removeClass("active")
                $(".list-group", parent).addClass("hidden")
            } else {
                $(this).addClass("active")
                $(".list-group", parent).removeClass("hidden")
            }
        });
    </script>
@endsection