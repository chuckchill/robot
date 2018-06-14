@extends('admin.layouts.base')

@section('title','查看视频')

@section('pageHeader','控制面板')

@section('pageDesc','DashBoard')
@section("css")
    <style>
        .video {
            max-height: 500px;
        }
    </style>
@endsection
@section('content')
    <div class="main animsition">
        <div class="container-fluid">

            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">查看视频</h3>
                        </div>
                        <div class="panel-body">
                            <div id="video"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section("js")
    <script src="/plugins/ckplayer/ckplayer.js"></script>
    <script type="text/javascript">
        var videoObject = {
            //playerID:'ckplayer01',//播放器ID，第一个字符不能是数字，用来在使用多个播放器时监听到的函数将在所有参数最后添加一个参数用来获取播放器的内容
            container: '#video', //容器的ID或className
            variable: 'player', //播放函数名称
            loaded: 'loadedHandler', //当播放器加载后执行的函数
            loop: false, //播放结束是否循环播放
            config: '', //指定配置函数
            debug: true, //是否开启调试模式
            //flashplayer: true, //强制使用flashplayer
            drag: 'start', //拖动的属性
            seek: 0, //默认跳转的时间
            adendlink: '',
            video: [
                ['{{$video_url}}', 'video/mp4', '中文标清', 0],
            ]
        };
        var player = new ckplayer(videoObject);
    </script>
@endsection