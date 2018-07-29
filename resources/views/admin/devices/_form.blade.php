<div class="form-group">
    <label for="tag" class="col-md-2 control-label">序列号</label>
    <div class="col-md-3">
        <div class="kv-avatar">
            <div class="file-loading">
                <input type="text" class="form-control"  name="sno">
            </div>
        </div>
    </div>
</div>

<div class="form-group">
    <label for="tag" class="col-md-2 control-label">名称</label>
    <div class="col-md-3">
        <input type="text" class="form-control" name="name" id="tag" value="{{ $name }}">
    </div>
</div>

<div class="form-group">
    <label for="tag" class="col-md-2 control-label">状态</label>
    <div class="col-md-3">
        <select class="form-control" name="status">
            <option value="1" @if($status==1) selected @endif>激活</option>
            <option value="0" @if($status==0) selected @endif>不激活</option>
        </select>
    </div>
</div>