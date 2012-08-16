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
                <a href="{{page_path}}/{{name}}" class="resource-name">
                    {{name}}
                </a>
            </td>
            <td>
                {{#info.general}}
                    {{> methods_list.m }}
                {{/info.general}}
            </td>
            <td>
                {{#info.aspects}}
                    {{> methods_list.m }}
                {{/info.aspects}}
            </td>
        </tr>
        {{/list}}

    </table>
{{/all_docs}}
