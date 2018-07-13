@section('js')
    <script src="/plugins/tinymce/tinymce.min.js"></script>
    <script>
        tinymce.init({
            selector: '#content',
            menubar: false,
            language: "zh_CN",
            convert_urls: false,
            plugins: 'image code',
            file_picker_types: 'image',
            toolbar: ' |link image | undo redo |  formatselect | bold italic backcolor  | bullist numlist outdent indent ',
            // we override default upload handler to simulate successful upload
            images_upload_handler: function (blobInfo, success, failure) {
                var xhr, formData;
                xhr = new XMLHttpRequest();
                xhr.withCredentials = false;
                xhr.open('POST', '{{route('admin.article.upload-img')}}');
                xhr.setRequestHeader('X-CSRF-TOKEN','{{csrf_token()}}')
                xhr.onload = function () {
                    var json;
                    if (xhr.status != 200) {
                        failure('HTTP Error: ' + xhr.status);
                        return;
                    }
                    json = JSON.parse(xhr.responseText);

                    if (!json || typeof json.location != 'string') {
                        failure('Invalid JSON: ' + xhr.responseText);
                        return;
                    }
                    success(json.location);
                };
                formData = new FormData();
                formData.append('file', blobInfo.blob(), blobInfo.filename());
                xhr.send(formData);
            },

            /*init_instance_callback: function (ed) {
                ed.execCommand('mceImage');
            }*/
        });
    </script>
@endsection
<div class="form-group">
    <label for="tag" class="col-md-2 control-label">标题</label>
    <div class="col-md-6">
        <div class="kv-avatar">
            <div class="file-loading">
                <input class="form-control" value="{{$title}}" type="text" id="file" name="title">
            </div>
        </div>
    </div>
</div>
<div class="form-group">
    <label for="tag" class="col-md-2 control-label">状态</label>
    <div class="col-md-6">
        <select class="form-control" name="status">
            <option value="1" @if($status==1) selected @endif>激活</option>
            <option value="2" @if($status==2) selected @endif>不激活</option>
        </select>
    </div>
</div>
<div class="form-group">
    <label for="tag" class="col-md-2 control-label">分类</label>
    <div class="col-md-6">
        @include('admin.common.typeselect',['select'=>$type])
    </div>
</div>
<div class="form-group">
    <label for="tag" class="col-md-2 control-label">文件</label>
    <div class="col-md-6">
        <input type="file" name="content-file">
    </div>
</div>
<div class="form-group">
    <label for="tag" class="col-md-2 control-label">内容</label>
    <div class="col-md-6">
        <textarea class="form-control" name="content" id="content">
            {{\App\Services\ModelService\Article::getContent($id)}}
        </textarea>
    </div>
</div>

<div class="form-group">
    <label class="col-md-2 control-label">
        注意
    </label>
    <div class="col-md-6">
        <label class="control-label">
            <span class="label-danger">文件和内容必须选择一个</span>
        </label>
    </div>
</div>