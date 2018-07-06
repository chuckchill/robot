@extends('admin.layouts.base')

@section('title','修改权限')

@section('pageHeader','控制面板')

@section('pageDesc','DashBoard')

@section('content')
    <div class="main animsition">
        <div class="container-fluid">

            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">编辑分类</h3>
                        </div>
                        <div class="panel-body">

                            @include('admin.partials.errors')
                            @include('admin.partials.success')
                            <form class="form-horizontal" role="form" method="POST"
                                  action="/admin/media-type/{{ $id }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="_method" value="PUT">
                                <input type="hidden" name="id" value="{{ $id }}">
                                <div class="form-group">
                                    <label for="tag" class="col-md-3 control-label">上级</label>
                                    <div class="col-md-6">
                                        <select @if(0==$pid) disabled @endif class="form-control" name="pid">
                                            <option value="0">顶级分类</option>
                                            @foreach($types as $type)
                                                <option value="{{$type['id']}}" @if($pid==$type['id']) selected @endif>
                                                    {{$type['name']}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="tag" class="col-md-3 control-label">名称</label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="name" id="tag" value="{{ $name }}"
                                               autofocus>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-7 col-md-offset-3">
                                        <button type="submit" class="btn btn-primary btn-md">
                                            <i class="fa fa-plus-circle"></i>
                                            保存
                                        </button>
                                        <button attr="{{$id}}"  type="button" class="btn btn-danger delBtn">
                                            <i class="fa fa-plus-circle"></i>
                                            删除
                                        </button>
                                    </div>
                                </div>

                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal-delete" tabIndex="-1">
        <div class="modal-dialog modal-warning">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                        ×
                    </button>
                    <h4 class="modal-title">提示</h4>
                </div>
                <div class="modal-body">
                    <p class="lead">
                        <i class="fa fa-question-circle fa-lg"></i>
                        确认要删除这个视频分类吗?
                    </p>
                </div>
                <div class="modal-footer">
                    <form class="deleteForm" method="POST" action="/media-type/list">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                        <button type="submit" class="btn btn-danger">
                            <i class="fa fa-times-circle"></i> 确认
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>
@endsection
@section("js")
    <script>
        $("body").delegate('.delBtn', 'click', function () {
            var id = $(this).attr('attr');
            $('.deleteForm').attr('action', '/admin/media-type/' + id);
            $("#modal-delete").modal();
        });
    </script>
@endsection