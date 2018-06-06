@extends('admin.layouts.base')

@section('title','控制面板')

@section('pageHeader','控制面板')

@section('pageDesc','DashBoard')

@section('content')
    <div class="row page-title-row" id="dangqian" style="margin:5px;">
        <div class="col-md-12 text-right">
            @if(Gate::forUser(auth('admin')->user())->check('admin.startup-page.create'))
                <a href="/admin/startup-page/create" class="btn btn-success btn-md"><i
                            class="fa fa-plus-circle"></i> 添加启动页 </a>
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
            <div class="box">

                @include('admin.partials.errors')
                @include('admin.partials.success')
                <div class="box-body">
                    <table id="tags-table" class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th data-sortable="false" class="hidden-sm"></th>
                            <th class="hidden-sm">状态</th>
                            <th class="hidden-sm">地址</th>
                            <th class="hidden-md">新增时间</th>
                            <th class="hidden-md">更新时间</th>
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
                            url: '/admin/startup-page/index',
                            type: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                            }
                        },
                        "columns": [
                            {"data": "id"},
                            {"data": "status"},
                            {"data": "imgsrc"},
                            {"data": "created_at"},
                            {"data": "updated_at"},
                        ],
                        columnDefs: [
                            {
                                'targets': 1,
                                "render": function (data, type, row) {
                                    switch (data) {
                                        case '0':
                                            return "未激活";
                                            break;
                                        case '1':
                                            return "激活";
                                            break;
                                        default:
                                            return "位置状态";
                                    }
                                }
                            },
                            {
                                'targets': 2,
                                "render": function (data, type, row) {
                                    return "<img style='width:20px' src='/upload/startup/"+data+"'>"
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
                });
            </script>
@endsection