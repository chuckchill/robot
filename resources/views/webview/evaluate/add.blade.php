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
    </style>
    <div class="container">
        <form action="" method="post">
            {{csrf_field()}}
            <input type="hidden" name="sicker_id" value="{{$sickerId}}">
            <button class="btn btn-success btn-sm">
                保存
            </button>
            <textarea class="form-control" name="content" id="content">{{$temp}}</textarea>
        </form>
    </div>
    <script src="/plugins/tinymce/tinymce.min.js"></script>
    <script>
        tinymce.init({
            selector: '#content',
            menubar: false,
            language: "zh_CN",
            convert_urls: false,
            plugins: 'image code',
            file_picker_types: 'image',
            height: "600",
            toolbar: ' |link image | undo redo |  formatselect | bold italic backcolor  | bullist numlist outdent indent ',
            // we override default upload handler to simulate successful upload
            images_upload_handler: function (blobInfo, success, failure) {

            }
        });
    </script>
@endsection