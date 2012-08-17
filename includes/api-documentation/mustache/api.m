{{#breadcrumb}}
<div id="api-documentation-breadcrumb">
    {{#list}}
        {{#uri}}
            <a href="{{uri}}">{{label}}</a> &rsaquo;
        {{/uri}}
        {{^uri}}
            <strong>{{label}}</strong>
        {{/uri}}
    {{/list}}

    {{title}}
</div>
{{/breadcrumb}}

<h1>{{title}}</h1>

{{^method}}
    {{> docs_table.m}}
{{/method}}

{{#method}}
    {{> docs_page.m}}
{{/method}}
