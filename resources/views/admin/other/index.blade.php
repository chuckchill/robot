@extends('admin.layouts.base')

@section('title','控制面板')

@section('pageHeader','控制面板')

@section('pageDesc','DashBoard')
@section('css')
    <style>
        .title {
            border-bottom: 1px solid #d2d6de
        }

        .content, button {
            margin-top: 20px;
        }
    </style>
@endsection
@section('content')

    <div class="row">
        <div class="col-xs-12">
            @if(Gate::forUser(auth('admin')->user())->check('admin.other.edit'))
                <div class="panel panel-default">
                    <div class="panel-heading">
                        热门关键词设置:
                    </div>
                    <div class="table-responsive">
                        <div class="content col-xs-6">
                            <textarea class="form-control" rows="3" id="keyword">{{$keyword}}</textarea>
                            <p>每个关键词用,分开例如(a,b,c)</p>
                            <button type="button" id="submitKeyword" class="btn btn-primary btn-md">
                                <i class="fa fa-plus-circle"></i>
                                提交
                            </button>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@section('js')
    <script>
        var token = '{{csrf_token()}}';
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': token
            }
        });
        $(function () {
            $("#submitKeyword").on('click', function () {
                var keyword = $("#keyword").val()
                $.ajax({
                    url: "/admin/other/keyword",
                    type: "post",
                    data: {
                        keyword: keyword
                    },
                    success: function (data) {
                        if (data.code == 200) {
                            alert("保存成功")
                        } else {
                            alert("保存失败")
                        }
                    }
                });
            });
        });
    </script>
@endsection