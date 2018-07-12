@section('js')
    <script src="/plugins/tinymce/tinymce.min.js"></script>
    <script>
        tinymce.init({
            selector: '#content',
            menubar: false,
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
{{--<div class="form-group">
    <label for="tag" class="col-md-2 control-label">文件</label>
    <div class="col-md-6">
       <input type="file" name="content-file" >
    </div>
</div>--}}
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