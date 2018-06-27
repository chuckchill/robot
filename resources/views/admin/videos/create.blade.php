@extends('admin.layouts.base')

@section('title','上传视频')

@section('pageHeader','控制面板')

@section('pageDesc','DashBoard')

@section('content')
    <div class="main animsition">
        <div class="container-fluid">

            <div class="row">
                <div class="col-md-12 ">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">添加视频</h3>
                        </div>
                        <div class="panel-body">
                            <form enctype="multipart/form-data" class="form-horizontal">
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
                                        <input type="text" class="form-control" id="fileName">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="tag" class="col-md-2 control-label">状态</label>
                                    <div class="col-md-4">
                                        <select id="videoStatus" class="form-control" name="status">
                                            <option value="1">激活</option>
                                            <option value="0">不激活</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="tag" class="col-md-2 control-label">类型</label>
                                    <div class="col-md-4">
                                        @include('admin.videos.typeselect',['select'=>0])
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
                                            上传视频
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
                var fileObj = document.getElementById("videoFile");
                var fileName = document.getElementById("fileName").value;
                var videoStatus = document.getElementById("videoStatus").value;
                var videoType = document.getElementById("videoType").value;
                if (fileObj.files.length != 1) {
                    alert("请选择上传文件");
                }
                var putExtra = {
                    fname: fileObj.files[0].name,
                    params: {
                        "x:name": fileName,
                        "x:status": videoStatus,
                        "x:type": videoType
                    },
                    mimeType: ["video/x-flv", "video/quicktime", "video/3gpp", "video/x-msvideo", "video/x-ms-wmv", "video/mp4"]
                };
                setProgressRate(0)
                $(".progress").removeClass("hidden")
                var observable = qiniu.upload(fileObj.files[0], null, "{{$token}}", putExtra, config)
                var subscription = observable.subscribe(observer) // 上传开始
            })
        })
    </script>
@endsection