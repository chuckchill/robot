@extends('webview.evaluate.layout')
@section('content')
    <style>
        html, body, .container {
            height: 100% !important;
        }

        .container {
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }
        .container .fa-fw{
            font-size: 50px;
            color: red;
        }
    </style>
    <div class="container">
        <div>
            <i class="fa fa-fw fa-times-circle"></i>
            <h1>{{request('error','访问异常')}}</h1>
        </div>
    </div>
@endsection