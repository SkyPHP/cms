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
                {{^dump}}
                    {{remote_database}}
                {{/dump}}
            </h2>
            {{^dump}}
            <h3>{{tab_caps}} -- just add new objects</h3>
            {{/dump}}
            <textarea>{{left_sql}}</textarea>
        </div>
        <div class="right-col">
            <h2>
                {{^dump}}
                    {{local_database}}
                {{/dump}}
                {{#left_arrow}} &larr; {{/left_arrow}}
                {{#right_arrow}} &rarr; {{/right_arrow}}
                {{remote_database}}
            </h2>
            {{^dump}}
            <h3>{{tab_caps}} -- add new objects and
                <span style="color: red; font-weight: bold;">drop</span>
                extra tables/columns/etc.
            </h3>
            {{/dump}}
            <textarea>{{right_sql}}</textarea>
        </div>
    </div>
    {{/tabs}}

</div>
{{/error}}
