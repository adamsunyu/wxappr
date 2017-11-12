{% if hotPosts|length %}
{% set orderText = 0 %}
<div class="module">
    <div class="module-head">72小时热门主题</div>
    <div class="module-body">
        <table class="table-stats">
            {%- for postActivity in hotPosts -%}
            <tr>
                <td>
                    {% set orderText = orderText + 1 %}
                    <span>{{ orderText }}.</span>
                    <a href="{{ url("topic/" ~ postActivity.post.id) }}" title="{{ postActivity.post.title}}">
                        {{ postActivity.post.title }}
                    </a>
                </td>
            </tr>
            {%- endfor -%}
        </table>
    </div>
</div>
{% endif %}
