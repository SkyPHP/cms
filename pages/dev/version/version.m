<table class="listing">

    <tr>
        <th>Codebase</th>
        <th>Version</th>
        <th>Requries</th>
        <th></th>
    </tr>

    {{#codebases}}
    <tr>
        {{#found}}
        <td>
            <div class="codebase-name">
                {{name}}
            </div>
            <div>
                <small><code>{{path}}</code></small>
            </div>
        </td>
        <td class="version">
            {{version}}
        </td>
        <td>
            {{#requires}}
                <dl>
                    {{#list}}
                    <dt class="codebase-name">{{name}}</dt>
                    <dd class="version">{{version}}</dt>
                    {{/list}}
                </dl>
            {{/requires}}
        </td>
        <td>
            <div class="status {{status.class}}">
                {{status.text}}
            </div>
        </td>
        {{/found}}
        {{^found}}
        <td colspan="4">
            <div class="error ini-not-found">
                Did not find <code>version.ini</code> in <code>{{path}}</code>
            </div>
        </td>
        {{/found}}

    </tr>
    {{/codebases}}

</table>
