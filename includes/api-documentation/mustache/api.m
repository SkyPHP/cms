<div class="has-floats">

    {{#breadcrumb}}
    <div id="api-documentation-breadcrumb">
        {{#list}}
            <a href="{{uri}}">{{label}}</a> &rsaquo;
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

</div>