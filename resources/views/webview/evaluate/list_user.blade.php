@extends('webview.evaluate.layout')
@section('content')
    <style>
        html, body, .container {
            height: 100% !important;
        }

        .container {
            text-align: center;
            padding-top: 50px;
        }

        th {
            text-align: center;
        }
    </style>
    <div class="container">
        <h3>请选择病人</h3>
        <table class="table table-bordered">
            <tbody>
            <tr>
                <th>姓名</th>
                <th>医生</th>
                <th>医生工号</th>
                <th>添加时间</th>
                <th>选择</th>
            </tr>
            @foreach ($sicker as $item)
                <tr>
                    <td>{{$item->sicker_name}}</td>
                    <td>{{$doctor->nick_name}}</td>
                    <td>{{$doctor->docker_no}}</td>
                    <td>{{$item->created_at}}</td>
                    <td><a href="/webview/eva-history?sicker_id={{$item->sicker_id}}">选择</a></td>
                </tr>
            @endforeach
            @if($sicker->lastPage()>1)
                <tr>
                    <td colspan="6">{!! $sicker->links() !!}</td>
                </tr>
            @endif
            </tbody>

        </table>
    </div>
@endsection