<div class="module">
    <div class="list-group">
{%- if session.get('identity') -%}
    {{- link_to(
        'create',
        '<span class="octicon octicon-megaphone"></span> Start a Discussion',
        'class': 'btn btn-sm btn-default',
        'rel': 'nofollow'
    ) -}}
{%- endif -%}
    </div>
</div>

<div class="module">
    <div class="input-group">
        <input type="text" name="" id="" placeholder="搜索" class="form-control">
        <div class="input-group-btn">
            <button class="btn btn-primary">搜索</button>
        </div>
    </div>
</div>
