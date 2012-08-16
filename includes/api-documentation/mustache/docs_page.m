
<div id="doc-container">

    <div class="example-url">{{url.prefix}}<strong>{{url.rest}}</strong></div>

    <div class="description">
        {{#doc}}
        <div class="api-desc">
            {{#list}}
                {{{text}}}
            {{/list}}
        </div>
        {{/doc}}
        {{^doc}}
        <p class="no-docs">
            Documentation coming soon.
        </p>
        {{/doc}}
    </div>


    {{#params}}
    <h3>Post Parameters</h3>
    <table class="api-table">
        {{#list}}
        <tr>
            <td class="param-type">
                {{type}}
            </td>
            <td class="param-name">
                {{name}}
            </td>
            <td class="param-description">
                {{{description}}}
            </td>
        </tr>
        {{/list}}
    </table>
    {{/params}}

</div>


<div id="doc-sidebar">
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
                    <h5>General</h5>
                    <ul>
                    {{#info.general}}
                        <li>
                            {{> method.m}}
                        </li>
                    {{/info.general}}
                    </ul>
                </div>
                <div class="content-group">
                    <h5>Aspects</h5>
                    <ul>
                    {{#info.aspects}}
                        <li>
                            {{> method.m}}
                        </li>
                    {{/info.aspects}}
                    </ul>
                </div>
            </div>
        </li>
        {{/list}}
    </ul>
    {{/all_docs}}
</div>
