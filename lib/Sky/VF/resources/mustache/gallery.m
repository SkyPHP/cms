<div class="vf-gallery has-floats {{empty}}"
    id="{{id}}"
    token="{{token}}"
    folders_path="{{folder_path}}"
    {{{context_menu}}}
    >
    {{#empty}}
        {{empty_message}}
    {{/empty}}

    {{^empty}}
        {{#list}}
        <div class="vf-gallery-item" ide="{{ide}}">
            {{{html}}}
        </div>
        {{/list}}
    {{/empty}}

</div>
