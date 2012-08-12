<div class="error">
    {{error}}
</div>

{{^error}}
<div id="tabs">

    <ul>
        {{#tabs}}
        <li>
            <a href="#tab-{{tab}}">{{tab}}</a>
        </li>
        {{/tabs}}
    </ul>

    {{#tabs}}
    <div id="tab-{{tab}}" class="columns">
        <div class="left-col">
            <h2>
                {{local_database}}
                {{#left_arrow}} &larr; {{/left_arrow}}
                {{#right_arrow}} &rarr; {{/right_arrow}}
                {{remote_database}}
            </h2>
            <h3>{{tab_caps}} -- just add new objects</h3>
            <textarea>{{create}}</textarea>
        </div>
        <div class="right-col">
            <h2>
                {{local_database}}
                {{#left_arrow}} &larr; {{/left_arrow}}
                {{#right_arrow}} &rarr; {{/right_arrow}}
                {{remote_database}}
            </h2>
            <h3>{{tab_caps}} -- add new objects and drop objects not on remote</h3>
            <textarea>{{create_drop}}</textarea>
        </div>
    </div>
    {{/tabs}}

</div>
{{/error}}
