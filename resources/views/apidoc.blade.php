<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>接口文档</title>
    <link rel="stylesheet" href="/css/apidoc.css">
</head>
<body>
<div>
    <div class="docs-nav">
        <ul class="docs-nav__items">
            @foreach(array_get($docs,"item",[]) as $key=>$doc)
                <li>
                    <ul>
                        <li class="docs-nav__item docs-nav__item--tocItem">
                            <div class="docs-nav__head">
                                <div class="docs-nav__icon docs-nav__icon--tocItem"></div>
                                <div class="docs-nav__name docs-nav__name--tocItem">
                                    <a class="docs-nav__link no-select" href="#introduction"
                                       title="Introduction">{{$doc['name']}}</a>
                                </div>
                            </div>
                        </li>
                    </ul>
                </li>
                @foreach(array_get($doc,"item",[]) as $key=>$item)
                    <li class="docs-nav__item docs-nav__item--request">
                        <div class="docs-nav__head">
                            <div class="docs-nav__icon docs-nav__icon--request">
                            <span class="docs-nav__method pm-method-color-post">
                                {{$item["request"]["method"]}}
                            </span>
                            </div>
                            <div class="docs-nav__name docs-nav__name--request">
                                <a class="docs-nav__link no-select" href="#jump_{{$doc['name']}}_{{$key}}" title="{{$item["name"]}}">
                                    {{$item["name"]}}
                                </a>
                            </div>
                        </div>
                    </li>
                @endforeach
            @endforeach
        </ul>
    </div>
    <div class="docs-body docs-body--double-col">
        @foreach(array_get($docs,"item",[]) as $key=>$doc)
            @foreach(array_get($doc,"item",[]) as $key=>$item)
                <div class="docs-item" id="jump_{{$doc['name']}}_{{$key}}">
                    <div class="docs-desc">
                        <div><h2 class="pm-h2 docs-desc-title docs-desc-title--request">
                            <span class="pm-method-color-post">
                                {{$item["request"]["method"]}}
                            </span>{{$item["name"]}}</h2>
                            <div class="docs-desc-title__url">{{build_api_url($item["request"]["url"])}}</div>
                        </div>
                        <div class="description">
                            <p>
                                {{array_get($item,'request.description')}}
                            </p>
                        </div>
                        <div class="docs-desc-body">
                            <div class="pm-markdown"></div>
                            <div class="docs-request-headers">
                                <h4 class="pm-h4">Headers</h4>
                                <table class="pm-table docs-request-table">
                                    <tbody>
                                    @foreach($item["request"]["header"] as $head)
                                        <tr>
                                            <td class="weight--medium">{{$head["key"]}}</td>
                                            <td>{{$head["value"]}}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="docs-request-body">
                                <h4 class="pm-h4 docs-request-body__title">Body</h4>
                                <span class="docs-request-body__mode push-half--left">urlencoded</span>
                                <table class="pm-table docs-request-table">
                                    <tbody>
                                    @foreach(get_api_body($item) as $body)
                                        <tr>
                                            <td class="weight--medium">{{$body["key"]}}</td>
                                            <td>{{array_get($body,"value")}}</td>
                                            <td>{{array_get($body,"description")}}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="docs-example">
                        <div class="docs-example__request">
                            <div class="docs-example__snippet-header">
                                <span class="docs-example__snippet-type">Example Request</span>
                                <span class="docs-example__response-title"
                                      title="{{$item["name"]}}">{{$item["name"]}}</span>
                            </div>
                            <div class="pm-snippet-container">
                                <div class="pm-snippet pm-snippet-wrap">
                                <pre class="pm-snippet-body">
                                    <code class="hljs curl bash">curl --request POST \
  --url <span class="hljs-string">{{build_api_url($item["request"]["url"])}}</span> \
  --header <span class="hljs-string">'Content-Type: application/x-www-form-urlencoded'</span> \
  --data <span class="hljs-string">{{build_api_query(get_api_body($item))}}</span>
                                </code>
                            </pre>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endforeach
    </div>
</div>
</body>
</html>
