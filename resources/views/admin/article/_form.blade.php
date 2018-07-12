@section('js')
    <script src="/plugins/tinymce/tinymce.min.js"></script>
    <script>
        tinymce.init({
            selector: '#content',
            menubar: false,
            convert_urls: false,
            height: 450,
            plugins: 'image code',
            // enable automatic uploads of images represented by blob or data URIs
            automatic_uploads: true,
            // URL of our upload handler (for more details check: https://www.tinymce.com/docs/configure/file-image-upload/#images_upload_url)
            // images_upload_url: 'postAcceptor.php',
            // here we add custom filepicker only to Image dialog
            file_picker_types: 'image',
            toolbar: ' |link image | undo redo |  formatselect | bold italic backcolor  | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent  | removeformat',
            file_picker_callback: function(cb, value, meta) {
                var input = document.createElement('input');
                input.setAttribute('type', 'file');
                input.setAttribute('accept', 'image/*');

                // Note: In modern browsers input[type="file"] is functional without
                // even adding it to the DOM, but that might not be the case in some older
                // or quirky browsers like IE, so you might want to add it to the DOM
                // just in case, and visually hide it. And do not forget do remove it
                // once you do not need it anymore.

                input.onchange = function() {
                    var file = this.files[0];

                    var reader = new FileReader();
                    reader.onload = function () {
                        // Note: Now we need to register the blob in TinyMCEs image blob
                        // registry. In the next release this part hopefully won't be
                        // necessary, as we are looking to handle it internally.
                        var id = 'blobid' + (new Date()).getTime();
                        var blobCache =  tinymce.activeEditor.editorUpload.blobCache;
                        var base64 = reader.result.split(',')[1];
                        var blobInfo = blobCache.create(id, file, base64);
                        blobCache.add(blobInfo);

                        // call the callback and populate the Title field with the file name
                        cb(blobInfo.blobUri(), { title: file.name });
                    };
                    reader.readAsDataURL(file);
                };

                input.click();
            }
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
       <input type="file" name="content-file" >
    </div>
</div>
<div class="form-group">
    <label for="tag" class="col-md-2 control-label">内容</label>
    <div class="col-md-6">
        <textarea class="form-control" name="content" id="content">
            {{$content}}
        </textarea>
    </div>
</div>

<div class="form-group">
    <label class="col-md-2 control-label">
       注意
    </label>
    <div class="col-md-6">
        <label  class="control-label">
            <span class="label-danger">文件和内容必须选择一个</span>
        </label>
    </div>
</div>