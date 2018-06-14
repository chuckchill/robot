<?php
/**
 * Created by PhpStorm.
 * User: albert
 * Date: 2017/8/4
 * Time: 18:31
 */

if (!function_exists('config_path')) {
    /**
     * Get the configuration path.
     *
     * @param  string $path
     * @return string
     */
    function config_path($path = '')
    {
        return app()->basePath() . '/config' . ($path ? '/' . $path : $path);
    }
}

if (!function_exists('auth_user')) {
    /**
     * Get the auth_user.
     *
     * @return mixed
     */
    function auth_user()
    {
        return app('Dingo\Api\Auth\Auth')->user();
    }
}

if (!function_exists('dingo_route')) {
    /**
     * 根据别名获得url.
     *
     * @param string $version
     * @param string $name
     * @param string $params
     *
     * @return string
     */
    function dingo_route($version, $name, $params = [])
    {
        return app('Dingo\Api\Routing\UrlGenerator')
            ->version($version)
            ->route($name, $params);
    }
}

if (!function_exists('trans')) {
    /**
     * Translate the given message.
     *
     * @param string $id
     * @param array $parameters
     * @param string $domain
     * @param string $locale
     *
     * @return string
     */
    function trans($id = null, $parameters = [], $domain = 'messages', $locale = null)
    {
        if (is_null($id)) {
            return app('translator');
        }

        return app('translator')->trans($id, $parameters, $domain, $locale);
    }
}

if (!function_exists('app_path')) {
    /**
     * Get the path to the application folder.
     *
     * @param  string $path
     * @return string
     */
    function app_path($path = '')
    {
        return app('path') . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }
}

if (!function_exists('vendor_path')) {
    /**
     * vendor path
     *
     * @param  string $path
     * @return string
     */
    function vendor_path($path = '')
    {
        return realpath(__DIR__ . '/../') . DIRECTORY_SEPARATOR . 'vendor' . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }
}
if (!function_exists('bad_request')) {
    /**
     * vendor path
     *
     * @param  string $path
     * @return string
     */
    function bad_request($message = '')
    {
        return app('\Dingo\Api\Http\Response\Factory')->errorBadRequest($message);
    }
}
if (!function_exists('forbidden')) {
    /**
     * vendor path
     *
     * @param  string $path
     * @return string
     */
    function forbidden($message = '')
    {
        return app('\Dingo\Api\Http\Response\Factory')->errorForbidden($message);
    }
}
if (!function_exists('img_url')) {
    /**
     * vendor path
     *
     * @param  string $path
     * @return string
     */
    function img_url($path)
    {
        $path = base64_encode($path);
        return app('request')->getSchemeAndHttpHost() . ("/api/general-image/{$path}");
    }
}
if (!function_exists('environment')) {
    /**
     * User: Terry Lucas
     * @param $env
     * @return bool
     */
    function environment($env)
    {
        return $env == \Illuminate\Support\Facades\App::environment();
    }
}

if (!function_exists('startup_img')) {

    function startup_img($path)
    {
        if (is_file(public_path("/upload/startup/" . $path))) {
            return url("/upload/startup/" . $path);
        }
        return "";
    }
}
if (!function_exists('link_img')) {

    function link_img($path)
    {
        if (is_file(public_path("/upload/boot/" . $path))) {
            return url("/upload/boot/" . $path);
        }
    }
}
if (!function_exists('build_api_url')) {

    function build_api_url($url)
    {
        return str_replace("{{BaseURL}}", "http://" . request()->getHttpHost() . "/api", $url);
    }
}
if (!function_exists('build_api_query')) {

    function build_api_query($data)
    {
        return http_build_query(collect($data)->pluck("value", "key")->toArray());
    }
}

if (!function_exists('get_api_body')) {

    function get_api_body($item)
    {
        $mode = array_get($item, "request.body.mode");
        return array_get($item, "request.body." . $mode, []);
    }
}
if (!function_exists('code_exception')) {

    function code_exception($codes)
    {
        app('App\Services\Helper')->codeException($codes);
    }
}