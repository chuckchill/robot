@extends('webview.evaluate.layout')
@section('content')
    <style>
        html, body, .container {
            height: 100% !important;
        }

        .container {
            text-align: center;
            padding-top: 50px;
            font-size: 20px;
        }

        .date {
            margin-left: 20px;
        }

        .fa-plus {
            margin-bottom: 20px;
        }
        .item{
            margin-bottom: 10px;
        }
    </style>
    <div class="container">
        <a href="/webview/eva-add?sicker_id={{$sickerId}}">
            <i class="fa fa-plus fa-times-circle">
                添加检测记录
            </i>
        </a>
        @if($evaluation->count()<1)
            <h3>没有检测记录</h3>
        @else
            @foreach($evaluation as $item)
                <div class="item">
                    <a href="{{$item->file_path}}">
                        {{$item->name}}
                    </a>
                    <span class="date">{{$item->created_at}}</span>
                </div>
            @endforeach
            <div>{!! $evaluation->links() !!}</div>
        @endif
    </div>
@endsection