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

$template = $template ?: 'website';
$title = $title ?: 'Developer API';

if (!$api) {
    throw new Exception('Must Pass An API Object to this inherited page.');
}

$keys = array('protocol', 'domain', 'url');
$url_prefix = vsprintf(
    '%s://%s%s',
    array_filter(array_map(function($k) use($config) {
        return $config['url'][$k];
    }, $keys))
);

if (!$config || !is_assoc($config) || !$url_prefix) {
    throw new Exception(
        'Must pass in api configuration array {url: {protocol, domain uri}}'
    );
}

$docs = new Sky\Api\Documentor($api);

$qf = $this->queryfolders;
list($resource, $endpoint) = $qf;

$this->title = ($this->queryfolders) ?  implode('/', $qf) . ' | '  . $title : $title;

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

        $to_text = function($val) {
            return array('list' => array_map(function($a) {
                return array('text' => $a);
            }, $val ?: array()));
        };

        $method['doc'] = array_map($to_text, $method['doc'] ?: array());
        $method['params'] = $method['params']
            ? array(
                'list' => array_map(function($ea) {
                    $ea['description'] = implode(PHP_EOL, $ea['description']);
                    return $ea;
                }, $method['params'])
            )
            : array();

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
    'all_docs' => $d ? array('list' => $d) : null,
    'method' => $method
);

$this->template($template, 'top');

echo $this->mustache('api.m', $data, $this->incpath . '/mustache');

$this->template($template, 'bottom');
