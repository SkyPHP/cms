{{#all_docs}}
    <table id="api-docs" class="api-table">

        <tr>
            <th></th>
            <th>General</th>
            <th>Aspects / Actions</th>
        </tr>

        {{#list}}
        <tr>
            <td>
                <a href="/developers/api/{{name}}" class="resource-name">
                    {{name}}
                </a>
            </td>
            <td>
                {{#info.general}}
                    {{> method.m}}
                {{/info.general}}
            </td>
            <td>
                {{#info.aspects}}
                    {{> method.m}}
                {{/info.aspects}}
            </td>
        </tr>
        {{/list}}

    </table>
{{/all_docs}}
