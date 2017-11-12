{% if hotNodes|length %}
<div class="module {% if myself %} sidebar-margin-top {% endif %}">
    <div class="module-head">热门节点</div>
    <div class="module-body">
        <table class="table-stats">
            {%- for node in hotNodes -%}
            <tr>
                <td width="40px">
                    <a href="{{ url("node/" ~ node.slug) }}" title="{{ node.name }}" class="avatar-link">
                        {{ node.iconNormal() }}
                    </a>
                </td>
                <td>
                    <a href="{{ url("node/" ~ node.slug) }}" title="{{ node.name }}" class="left">
                        {{ node.name }}
                    </a>
                </td>
            </tr>
            {%- endfor -%}
        </table>
    </div>
</div>
{% endif %}
