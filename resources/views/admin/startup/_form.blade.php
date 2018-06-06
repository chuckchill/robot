<div class="form-group">
    <label for="tag" class="col-md-3 control-label">图片</label>
    <div class="col-md-6">
        <input type="file" name="imgsrc">
</div>
</div>
<div class="form-group">
    <label for="tag" class="col-md-3 control-label">图片</label>
    <div class="col-md-6">
        <select name="status">
            <option value="1" @if($status==1) selected @endif>激活</option>
            <option value="0" @if($status==0) selected @endif>不激活</option>
        </select>
    </div>
</div>

<div class="form-group">
    <label for="tag" class="col-md-3 control-label">描述</label>
    <div class="col-md-6">
        <input type="text" class="form-control" name="remakr" id="tag" value="{{ $remarks }}" autofocus>
    </div>
</div>
