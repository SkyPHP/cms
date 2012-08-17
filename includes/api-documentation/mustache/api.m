<h1>{{title}}</h1>

{{^method}}
    {{> docs_table.m}}
{{/method}}

{{#method}}
    {{> docs_page.m}}
{{/method}}
