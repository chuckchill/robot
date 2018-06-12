@section('js')
    <script src="/plugins/fileupload/js/fileupload.js"></script>
    <link rel="stylesheet" href="/plugins/fileupload/css/fileupload.css">
    <script>
        $("#file").fileinput({
            overwriteInitial: true,
            maxFileSize: 1500,
            showClose: false,
            showCaption: false,
            showBrowse: false,
            browseOnZoneClick: true,
            removeLabel: '',
            //removeIcon: '<i class="glyphicon glyphicon-remove"></i>',
            removeTitle: 'Cancel or reset changes',
            elErrorContainer: '#kv-avatar-errors-2',
            msgErrorClass: 'alert alert-block alert-danger',
            defaultPreviewContent: '<img src="/images/default_avatar_male.png" alt="Your Avatar"><h6 class="text-muted">Click to select</h6>',
            layoutTemplates: {main2: '{preview} {remove} {browse}'},
            allowedFileExtensions: ["jpg", "png", "gif"],
        });
    </script>
@endsection
<div class="form-group">
    <label for="tag" class="col-md-2 control-label">图片</label>
    <div class="col-md-10">
        <div class="kv-avatar">
            <div class="file-loading">
                <input type="file" multiple id="file" class="file" name="imgsrc[]">
            </div>
        </div>
    </div>
</div>
<div class="form-group">
    <label for="tag" class="col-md-2 control-label">状态</label>
    <div class="col-md-10">
        <select class="form-control" name="status">
            <option value="1" @if($status==1) selected @endif>激活</option>
            <option value="0" @if($status==0) selected @endif>不激活</option>
        </select>
    </div>
</div>

<div class="form-group">
    <label for="tag" class="col-md-2 control-label">描述</label>
    <div class="col-md-10">
        <input type="text" class="form-control" name="remakr" id="tag" value="{{ $remarks }}" autofocus>
    </div>
</div>

