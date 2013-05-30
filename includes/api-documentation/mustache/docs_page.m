
<div id="doc-container">

    <div class="example-url">{{url.prefix}}<strong>{{url.rest}}</strong></div>

    <h3>Description</h3>
    <div class="description">
        {{#doc}}
        <div class="api-desc">
            {{{content}}}
        </div>
        {{/doc}}
        {{^doc}}
        <p class="no-docs">
            Documentation coming soon.
        </p>
        {{/doc}}
    </div>


    {{#params}}
    <h3><code>POST</code> Parameters</h3>
    <table class="api-table">
        {{#list}}
        <tr>
            <td class="param-type">
                {{type}}
                {{^type}}
                --
                {{/type}}
            </td>
            <td class="param-name">
                {{name}}
                {{^name}}
                --
                {{/name}}
            </td>
            <td class="param-description">
                {{{description}}}
            </td>
        </tr>
        {{/list}}
    </table>
    {{/params}}

    <h3>Sample Response</h3>
    <pre><code>{{response}}</code></pre>

</div>


<div id="doc-sidebar">
    <a href="{{page_path}}" id="back-to-grid">Back</a>
    {{#all_docs}}
    <ul>
        {{#list}}
        <li class="resource-group" data-state="{{state}}">
            <h4>
                <a href="{{page_path}}/{{name}}">
                    {{name}}
                </a>
            </h4>

            <div class="content">

                <div class="content-group">
                    {{#info.general}}
                        <h5>General</h5>
                        {{> methods_list.m }}
                    {{/info.general}}
                </div>
                <div class="content-group">
                    {{#info.aspects}}
                        <h5>Aspects</h5>
                        {{> methods_list.m }}
                    {{/info.aspects}}
                </div>
            </div>
        </li>
        {{/list}}
    </ul>
    {{/all_docs}}
</div>
