<?php

/**
 * Usage of api-documentation generator:
 *      // in a page of your choosing (pages/api/api.php)
 *      $this->inherit([
 *          'api' => new \MyPath\To\Namespace\Api, // should extend \Sky\Api
 *          'title' => 'My API Docs', // default: Developer API,
 *          'template' => 'my-template-name', // the template alias to (default: website)
 *          'config' => [
 *              'url' => [
 *                  'protocol' => 'https',
 *                  'domain' => 'api.mydomain.com',
 *                  'url' => '/api/version2'
 *              ]
 *          ]
 *      ]);
 *
 * Use `@apiDoc` and `@apiParam` do describe your publicly available methods
 * in your Api\Resources
 *
 * @see \Sky\DocParser
 * @see \Sky\Api\Documentor
 * @see \Sky\Api
 * @see \Sky\Api\Resource
 */

include_once 'lib/markdown/markdown.php';

$template = $template ?: 'html5';
$title = $title ?: 'Developer API';

$this->css[] = '/lib/codemirror/lib/codemirror.css';
$this->js = array_merge($this->js, array(
    '/lib/codemirror/lib/codemirror.js',
    '/lib/codemirror/lib/util/runmode.js',
    '/lib/codemirror/mode/xml/xml.js',
    '/lib/codemirror/mode/javascript/javascript.js',
    '/lib/codemirror/mode/clike/clike.js',
    '/lib/codemirror/mode/php/php.js'

));

if (!$api) {
    throw new Exception('Must Pass An API Object to this inherited page.');
}

$config = [
    'url' => $api::$url
];

$keys = array('protocol', 'domain', 'url');
$url_prefix = rtrim(vsprintf(
    '%s://%s%s',
    array_filter(array_map(function($k) use($config) {
        return $config['url'][$k];
    }, $keys))
), '/');

if (!$config || !is_assoc($config) || !$url_prefix) {
    throw new Exception(
        'Must pass in api configuration array {url: {protocol, domain uri}}'
    );
}

$docs = new Sky\Api\Documentor($api);

$qf = $this->queryfolders;
list($resource, $endpoint) = $qf;

$this->title = $qf ?  implode('/', $qf) : $title;

if ($resource) {

    try {

        $method = $docs->getResourceDoc($resource, $endpoint);
        $method['url'] = array(
            'prefix' => $url_prefix,
            'rest' => rtrim(sprintf(
                '/%s/%s/%s',
                $resource,
                $method['aspects'] || !$endpoint ? 'ID' : $endpoint,
                $method['aspects'] ? $endpoint : ''
            ), '/')
        );

        $doc = trim(Markdown(\Sky\DocParser::docAsString($method['doc'])));

        $method['doc'] = $doc ? array('content' => $doc) : null;
        $method['params'] = $method['params']
            ? array(
                'list' => array_map(function($ea) {

                    $ea['description'] = Markdown(implode(PHP_EOL, $ea['description']));

                    return $ea;
                }, $method['params'])
            )
            : array();

        // get sample response

        // TODO: get these values from the specific resource
        #$oauth_token = $api::$documentation_settings['oauth_token'];
        #$sampleID = $api::$documentation_settings['sampleID'];
        $sampleID = -1;

        $url = $method['url']['prefix'] . $method['url']['rest'];
        $url = str_replace('ID', $sampleID, $url);
        $url .= '?oauth_token=' . $oauth_token;
        $cache_key = 'api-responses/' . slugize($url);
        $response = disk($cache_key);
        if (!$response || $_GET['disk-refresh']) {
            $response = GetCurlPage($url, [
                'documentation' => true
            ]);
            disk($cache_key, $response, '30 days');
        }
        $method['response'] = json_beautify($response);

    } catch (\Exception $e) {
        include 'pages/404.php';
        return;
    }

} else {
    $method = null;
}



$parsed = $docs->walkResources();
ksort($parsed);

$d = array();
$types = array('general', 'aspects');

foreach ($parsed as $k => $value) {
    $d[] = array(
        'name' => $k,
        'state' => $k == $resource ? 'open' : 'closed', // used for right nav
        'info' => call_user_func(function() use($value, $types) {

            $data = array(
                'construct' => $value['construct']
            );

            foreach ($types as $type) {

                $t = array_values($value[$type]);

                if ($t) {
                    $data[$type] = array('list' => $t);
                }
            }

            return $data;
        })
    );
    $i++;
}

$data = array(
    'conf' => $conf,
    'page_path' => $this->urlpath,
    'title' => $this->title,
    'breadcrumb' => mustachify($breadcrumb, 'label', 'uri', 'list'),
    'all_docs' => $d ? array('list' => $d) : null,
    'api_doc' => Markdown($docs->getApiDoc()),
    'method' => $method
);

$this->template($template, 'top');

echo $this->mustache('api.m', $data, $this->incpath . '/mustache');

$this->template($template, 'bottom');
