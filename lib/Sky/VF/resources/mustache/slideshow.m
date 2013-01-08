<div class="vf-slideshow has-floats"
    folders_path="{{folders_path}}"
    transition="{{transition}}"
    delay="{{delay}}"
    autohide="{{autohide}}"
    autostart="{{autostart}}"
    w="{{width}}"
    h="{{height}}"
    >

    <div class="vf-slideshow-main" style="width:{{width}}px; height:{{height}}px;">
        {{! keep the following loop without whitespace to prevent padding }}
        <div class="vf-slideshow-image">{{#main}}{{{html}}}{{/main}}</div>
        <div class="vf-slideshow-controls has-floats">
            {{#captions}}
            <div class="vf-slideshow-caption float-left"></div>
            {{/captions}}
            {{#controls}}
                {{#enlarge}}
                <a href="#enlarge" class="vf-slideshow-enlarge"></a>
                {{/enlarge}}
                <a href="#next" class="vf-slideshow-next"></a>
                <a href="#playpause" class="vf-slideshow-pause"></a>
                <a href="#prev" class="vf-slideshow-prev"></a>
            {{/controls}}
        </div>
    </div>

    <div class="vf-slideshow-thumbs has-floats" style="width:{{width}}px;">
        {{#thumbs}}
        <div class="vf-slideshow-thumb {{class}}" caption="{{caption}}" ide="{{ide}}">
            {{{html}}}
        </div>
        {{/thumbs}}
    </div>

</div>
