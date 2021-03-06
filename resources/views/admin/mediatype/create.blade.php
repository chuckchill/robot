@extends('admin.layouts.base')

@section('title','添加权限')

@section('pageHeader','控制面板')

@section('pageDesc','DashBoard')

@section('content')
    <div class="main animsition">
        <div class="container-fluid">

            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">添加分类</h3>
                        </div>
                        <div class="panel-body">

                            @include('admin.partials.errors')
                            @include('admin.partials.success')

                            <form enctype="multipart/form-data" class="form-horizontal" role="form"
                                  method="POST" action="/admin/media-type">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <div class="form-group">
                                    <label for="tag" class="col-md-3 control-label">上级</label>
                                    <div class="col-md-6">
                                        @include("admin.common.typeselect",["select"=>0,"fname"=>"pid","hasHead"=>true,'tt'=>''])
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
                                    <label for="tag" class="col-md-3 control-label">展示图</label>
                                    <div class="col-md-6">
                                        <input type="file" class="" accept="image/*" name="thumbImg">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-7 col-md-offset-3">
                                        <button type="submit" class="btn btn-primary btn-md">
                                            <i class="fa fa-plus-circle"></i>
                                            添加
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
@endsection