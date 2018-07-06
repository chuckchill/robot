<select id="videoType" class="form-control" name="type">
    @foreach(\App\Services\ModelService\MediaType::getTypeTree() as $trees)
        <optgroup label="{{$trees['name']}}">
            @foreach(array_get($trees,"children",[]) as $key=>$item)
                <option @if($item['id']==$select) selected @endif value="{{$item['id']}}">
                        {{$item['name']}}
                </option>
            @endforeach
        </optgroup>
    @endforeach
</select>