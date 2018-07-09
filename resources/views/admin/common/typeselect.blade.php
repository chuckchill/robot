<select id="videoType" class="form-control" name="{{isset($fname)? $fname:"type"}}">
    @if(isset($hasHead) &&$hasHead)
        <option value="0">顶级分类</option>
    @endif
    @foreach(\App\Services\ModelService\MediaType::getTypeTree(true) as $item)
        <option @if($item['id']==$select) selected @endif value="{{$item['id']}}">
           {{str_repeat("————",$item['level']).$item['name']}}
        </option>
    @endforeach
</select>