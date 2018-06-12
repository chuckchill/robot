@extends('admin.layouts.base')

@section('title','控制面板')

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
                                    <label for="tag" class="col-md-2 control-label">图片</label>
                                    <div class="col-md-6">
                                        <div class="kv-avatar">
                                            <div class="file-loading">
                                                <input type="file" id="videoFile" class="file" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="tag" class="col-md-2 control-label">名称</label>
                                    <div class="col-md-4">
                                        <input placeholder="前端显示的名称" type="text" class="form-control" id="fileName">
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
    <script src="https://unpkg.com/qiniu-js@2.3.0/dist/qiniu.min.js"></script>
    <script>
        $(function () {
            var observer = {
                next: function (res) {
                    console.log(res)
                    var rate = res.total.percent.toFixed(2)
                    $("div[role='progressbar']").css("width", rate + "%");
                    $(".process-remarks").html(rate + "%");
                },
                error: function (err) {
                    $(".progress").addClass("hidden")
                },
                complete: function (res) {
                    $(".progress").addClass("hidden")
                }
            }
            var config = {
                // useCdnDomain: false,
                //region: "up-z0.qiniup.com"
            };
            $("#uploadBtn").on("click", function () {
                var fileObj = document.getElementById("videoFile");
                var fileName = document.getElementById("fileName").value;
                if (fileObj.files.length != 1) {
                    alert("请选择上传文件");
                }
                var putExtra = {
                    fname: fileObj.files[0].name,
                    params: {name: fileName},
                    // mimeType: ["video/quicktime", "video/x-mpeg2", "video/x-msvideo"]
                };
                $(".progress").removeClass("hidden")
                var observable = qiniu.upload(fileObj.files[0], null, "{{$token}}", putExtra, config)
                var subscription = observable.subscribe(observer) // 上传开始
            })
        })
    </script>
@endsection