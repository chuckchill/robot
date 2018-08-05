@extends('admin.layouts.base')

@section('title','修改视频')

@section('pageHeader','控制面板')

@section('pageDesc','DashBoard')

@section('content')
    <div class="main animsition">
        <div class="container-fluid">

            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">编辑视频</h3>
                        </div>
                        <div class="panel-body">
                            @include('admin.partials.errors')
                            @include('admin.partials.success')
                            <form enctype="multipart/form-data" action="/admin/videos/{{ $id }}" class="form-horizontal"
                                  role="form" method="POST">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="_method" value="PUT">
                                <input type="hidden" name="id" value="{{ $id }}">
                                <div class="form-group">
                                    <label for="tag" class="col-md-2 control-label">视频</label>
                                    <div class="col-md-6">
                                        <div class="kv-avatar">
                                            <div class="file-loading">
                                                <input type="file" id="videoFile" class="file"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="tag" class="col-md-2 control-label">名称(前端显示的名称)</label>
                                    <div class="col-md-4">
                                        <input type="text" name="name" value="{{$name}}" class="form-control"
                                               id="fileName">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="tag" class="col-md-2 control-label">状态</label>
                                    <div class="col-md-4">
                                        <select id="videoStatus" class="form-control" name="status">
                                            <option value="1" @if($status==1) selected @endif>激活</option>
                                            <option value="0" @if($status==0) selected @endif>不激活</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="tag" class="col-md-2 control-label">类型</label>
                                    <div class="col-md-4">
                                        @include('admin.common.typeselect',['select'=>$type])
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-4 col-md-offset-2">
                                        <div class="progress progress-striped active hidden">
                                            <div class="progress-bar progress-bar-success" role="progressbar"
                                                 aria-valuenow="60"
                                                 aria-valuemin="0" aria-valuemax="100" style="width: 0%;">
                                                <p class="text-primary process-remarks">0% </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-4 col-md-offset-3">
                                        <button type="button" id="uploadBtn" class="btn btn-primary btn-md">
                                            <i class="fa fa-plus-circle"></i>
                                            保存修改
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
@section("js")
    <script src="/js/backend.js"></script>
    <script src="https://unpkg.com/qiniu-js@2.3.0/dist/qiniu.min.js"></script>
    <script>
        $(function () {
            var setProgressRate = function (rate) {
                $("div[role='progressbar']").css("width", rate + "%");
                $(".process-remarks").html(rate + "%");
            }
            var observer = {
                next: function (res) {
                    var rate = res.total.percent.toFixed(2)
                    setProgressRate(rate)
                },
                error: function (err) {
                    qiniuError(err)
                    $(".progress").addClass("hidden")
                },
                complete: function (res) {
                    $(".progress").addClass("hidden")
                    window.location.href = "/admin/videos/index";
                }
            }
            var config = {
                // useCdnDomain: false,
                //region: "up-z0.qiniup.com"
            };
            $("#uploadBtn").on("click", function () {
                var fileObj = document.getElementById("videoFile");;
                if (fileObj.files.length < 1) {
                    $(".form-horizontal").submit()
                    return true;
                }
                var fileName = document.getElementById("fileName").value;
                var videoStatus = document.getElementById("videoStatus").value;
                var videoType = document.getElementById("videoType").value;
                var putExtra = {
                    fname: fileObj.files[0].name,
                    params: {
                        "x:name": fileName,
                        "x:status": videoStatus,
                        "x:type": videoType
                    },
                   // mimeType: ["video/x-flv", "video/quicktime", "video/3gpp", "video/x-msvideo", "video/x-ms-wmv", "video/mp4"]
                };
                setProgressRate(0)
                $(".progress").removeClass("hidden")
                var observable = qiniu.upload(fileObj.files[0], null, "{{$token}}", putExtra, config)
                var subscription = observable.subscribe(observer) // 上传开始
            })
        })
    </script>
@endsection