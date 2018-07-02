<select id="videoType" class="form-control" name="type">
    @foreach(config('admin.videos.type') as $key=>$item)
        <option @if($key==$select) selected @endif value="{{$key}}">
            {{$item}}
        </option>
    @endforeach
</select>