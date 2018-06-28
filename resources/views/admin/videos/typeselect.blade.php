<select id="videoType" class="form-control" name="type">
    @foreach(\App\Services\ModelService\VideosType::getTypeTree() as $trees)
        <optgroup label="{{$trees['name']}}">
            @foreach($trees['children'] as $key=>$item)
                <option @if($key==$select) selected @endif value="{{$key}}">
                        {{$item['name']}}
                </option>
            @endforeach
        </optgroup>
    @endforeach
</select>