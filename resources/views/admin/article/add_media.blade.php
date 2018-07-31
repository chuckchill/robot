@extends('admin.layouts.base')

@section('title','添加文章')

@section('pageHeader','控制面板')

@section('pageDesc','DashBoard')

@section('content')
    <div class="main animsition">
        <div class="container-fluid">

            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">添加媒体文件</h3>
                        </div>
                        <div class="panel-body">
                            <form enctype="multipart/form-data" class="form-horizontal">
                                <div class="form-group">
                                    <label for="tag" class="col-md-2 control-label">pdf文件</label>
                                    <div class="col-md-6">
                                        <div class="kv-avatar">
                                            <div class="file-loading">
                                                <input accept="application/pdf" type="file" id="pdfFile" class="file"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="tag" class="col-md-2 control-label">语音文件</label>
                                    <div class="col-md-6">
                                        <div class="kv-avatar">
                                            <div class="file-loading">
                                                <input accept="audio/*" type="file" multiple id="voiceFile"
                                                       class="file"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="tag" class="col-md-2 control-label">注意</label>
                                    <div class="col-md-6">
                                        <label class="control-label">语音文件名称必须是有序的数字(如1.jpg,2.jpg)</label>
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
                                    <span id="whichFile" class="col-md-2 control-label"></span>
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
            var articleId = '{{$article->id}}'
            var setProgressRate = function (rate) {
                $("div[role='progressbar']").css("width", rate + "%");
                $(".process-remarks").html(rate + "%");
            }
            var subscription = {};
            $("#uploadBtn").on("click", function () {
                var pdfFile = document.getElementById("pdfFile");
                var voiceFile = document.getElementById("voiceFile");
                if (pdfFile.files.length < 1 || voiceFile.files.length < 1) {
                    alert("请选择上传文件");
                }
                var fileList = [];
                fileList[0] = pdfFile.files[0];
                for (var i = 0; i < voiceFile.files.length; i++) {
                    fileList[i + 1] = voiceFile.files[i];
                }
                $(".progress").removeClass("hidden")
                uploadFile(fileList, 0)
            })
            var uploadFile = function (list, cur) {
                $("#whichFile").html("上传第" + (cur + 1) + "个文件");
                fileObj = list.pop();
                var observer = {
                    next: function (res) {
                        var rate = res.total.percent.toFixed(2)
                        setProgressRate(rate)
                    },
                    error: function (err) {
                        setProgressRate(0)
                        if (list.length > 0) {
                            uploadFile(list, cur + 1)
                        }
                    },
                    complete: function (res) {
                        setProgressRate(0)
                        if (list.length > 0) {
                            uploadFile(list, cur + 1)
                        } else {
                            window.location.href = "/admin/article";
                        }
                    }
                }
                if (cur == 0) {
                    key = "pdf_" + articleId;
                } else {
                    key = "voice_" + articleId + "_" + parseInt(GetFileNameNoExt(fileObj.name));
                }
                var observable = qiniu.upload(fileObj, key, "{{$token}}", null, null)
                subscription = observable.subscribe(observer) // 上传开始     //在这里运行代码
            }
            var GetFileNameNoExt = function (filepath) {
                return filepath.replace(/(.*\/)*([^.]+).*/ig, "$2");
            }
        })
    </script>
@endsection