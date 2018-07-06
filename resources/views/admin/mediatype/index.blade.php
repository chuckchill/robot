@extends('admin.layouts.base')

@section('title','视频分类')

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
                    @foreach($tree as $item)
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <a href="/admin/media-type/{{$item['id']}}/edit">{{$item["name"]}}</a>
                            </div>
                            @if(count(array_get($item,"children",[])))
                                <div class="panel-body">
                                    @foreach(array_get($item,"children") as $child)
                                        <span class="label label-default mt-left-5">
                                            <a href="/admin/media-type/{{$child['id']}}/edit">{{$child['name']}}</a>
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endforeach
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