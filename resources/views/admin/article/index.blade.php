@extends('admin.layouts.base')

@section('title','文章')

@section('pageHeader','控制面板')

@section('pageDesc','DashBoard')

@section('content')
    <div class="row page-title-row" id="dangqian" style="margin:5px;">
        <div class="col-md-12 text-right">
            @if(Gate::forUser(auth('admin')->user())->check('admin.article.create'))
                <a href="/admin/article/create" class="btn btn-success btn-md"><i
                            class="fa fa-plus-circle"></i> 添加文章 </a>
            @endif
        </div>
    </div>
    <div class="row page-title-row" style="margin:5px;">
        <div class="col-md-6">
        </div>
    </div>
    <div class="row page-title-row" style="margin:5px;">
        <div class="col-md-6">
        </div>
        <div class="col-md-6 text-right">
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    文章:
                </div>
                @include('admin.partials.errors')
                @include('admin.partials.success')
                <div class="box-body">
                    <table id="tags-table" class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th data-sortable="false" class="hidden-sm"></th>
                            <th class="hidden-sm">标题</th>
                            <th class="hidden-sm">状态</th>
                            <th class="hidden-md">新增时间</th>
                            <th class="hidden-md">更新时间</th>
                            <th class="hidden-md">操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
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
                        确认要删除这个文章吗?
                    </p>
                </div>
                <div class="modal-footer">
                    <form class="deleteForm" method="POST" action="/article/list">
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

        @endsection

        @section('js')
            <script>
                $(function () {
                    var table = $("#tags-table").DataTable({
                        ordering: false,
                        searching: false,
                        language: {
                            "sProcessing": "处理中...",
                            "sLengthMenu": "显示 _MENU_ 项结果",
                            "sZeroRecords": "没有匹配结果",
                            "sInfo": "显示第 _START_ 至 _END_ 项结果，共 _TOTAL_ 项",
                            "sInfoEmpty": "显示第 0 至 0 项结果，共 0 项",
                            "sInfoFiltered": "(由 _MAX_ 项结果过滤)",
                            "sInfoPostFix": "",
                            "sUrl": "",
                            "sEmptyTable": "表中数据为空",
                            "sLoadingRecords": "载入中...",
                            "sInfoThousands": ",",
                            "oPaginate": {
                                "sFirst": "首页",
                                "sPrevious": "上页",
                                "sNext": "下页",
                                "sLast": "末页"
                            },
                            "oAria": {
                                "sSortAscending": ": 以升序排列此列",
                                "sSortDescending": ": 以降序排列此列"
                            }
                        },
                        order: [[1, "desc"]],
                        serverSide: true,
                        ajax: {
                            url: '/admin/article/index',
                            type: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                            }
                        },
                        "columns": [
                            {"data": "id"},
                            {"data": "title"},
                            {"data": "status"},
                            {"data": "created_at"},
                            {"data": "updated_at"},
                            {"data": "action"},
                        ],
                        columnDefs: [
                            {
                                'targets': 2,
                                "render": function (data, type, row) {
                                    switch (data) {
                                        case 2:
                                            return "未激活";
                                            break;
                                        case 1:
                                            return "激活";
                                            break;
                                        default:
                                            return "未知状态";
                                    }
                                }
                            },
                            {
                                'targets': -1, "render": function (data, type, row) {
                                    var row_edit = {{Gate::forUser(auth('admin')->user())->check('admin.article.edit') ? 1 : 0}};
                                    var row_delete = {{Gate::forUser(auth('admin')->user())->check('admin.article.destroy') ? 1 :0}};
                                    var str = '';
                                    //编辑
                                    if (row_edit) {
                                        str += '<a style="margin:3px;" href="/admin/article/' + row['id'] + '/edit" class="X-Small btn-xs text-success "><i class="fa fa-edit"></i> 编辑</a>';
                                    }
                                    //添加文章内容
                                    if (1) {
                                        str += '<a style="margin:3px;" href="/admin/article/upload-media?articleId=' + row['id'] + '" class="X-Small btn-xs text-success "><i class="fa fa-edit"></i> 添加文件</a>';
                                    }
                                    //删除
                                    if (row_delete) {
                                        str += '<a style="margin:3px;" href="#" attr="' + row['id'] + '" class="delBtn X-Small btn-xs text-danger"><i class="fa fa-times-circle"></i> 删除</a>';
                                    }

                                    return str;

                                }
                            }
                        ]
                    });

                    table.on('preXhr.dt', function () {
                        loadShow();
                    });

                    table.on('draw.dt', function () {
                        loadFadeOut();
                    });

                    table.on('order.dt search.dt', function () {
                        table.column(0, {search: 'applied', order: 'applied'}).nodes().each(function (cell, i) {
                            cell.innerHTML = i + 1;
                        });
                    }).draw();
                    $("table").delegate('.delBtn', 'click', function () {
                        var id = $(this).attr('attr');
                        $('.deleteForm').attr('action', '/admin/article/' + id);
                        $("#modal-delete").modal();
                    });
                });
            </script>
@endsection