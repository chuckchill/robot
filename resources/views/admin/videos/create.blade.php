@extends('admin.layouts.base')

@section('title','控制面板')

@section('pageHeader','控制面板')

@section('pageDesc','DashBoard')

@section('content')
    <div class="main animsition">
        <div class="container-fluid">

            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">添加引导页</h3>
                        </div>
                        <div class="panel-body">
                            <form enctype="multipart/form-data" class="form-horizontal">
                                <div class="form-group">
                                    <label for="tag" class="col-md-2 control-label">图片</label>
                                    <div class="col-md-10">
                                        <div class="kv-avatar">
                                            <div class="file-loading">
                                                <input type="file" id="videoFile" class="file">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="tag" class="col-md-2 control-label">名称</label>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" id="fileName">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-md-7 col-md-offset-3">
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
                },
                error: function (err) {
                    console.log(err)
                },
                complete: function (res) {
                    console.log(err)
                }
            }
            var config = {
                useCdnDomain: true,
                region: qiniu.region.z2
            };
            $("#uploadBtn").on("click", function () {
                var putExtra = {
                    fname: "",
                    params: {name:fileName},
                    mimeType: ["video/quicktime","video/x-mpeg2","video/x-msvideo"]
                };
                var fileObj = document.getElementById("videoFile");
                var fileName = document.getElementById("fileName").value;
                var observable = qiniu.upload(fileObj.files[0], '', "{{$token}}", putExtra, config)
                var subscription = observable.subscribe(observer) // 上传开始
            })
        })
    </script>
@endsection