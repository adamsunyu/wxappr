{%
    set currentUser = session.get('identity')
%}
{% if currentUser %}
<div class="module">
    <div class="module-head">我关注的节点</div>
    <div class="module-body">
        <table class="table-stats">
            {% if myNodes|length %}
                {%- for userNode in myNodes -%}
                <tr>
                    <td width="40px">
                        <a href="{{ url("node/" ~ userNode.node.slug) }}" title="{{ userNode.node.name }}" class="avatar-link">
                            {{ userNode.node.iconNormal() }}
                        </a>
                    </td>
                    <td>
                        <a href="{{ url("node/" ~ userNode.node.slug) }}" title="{{ userNode.node.name }}" class="left">
                            {{ userNode.node.name }}
                        </a>
                    </td>
                </tr>
                {%- endfor -%}
            {% else %}
                <tr><td></td><tr>
                <tr><td style="text-align:center;">-_- 空空如也 -_-</td></tr>
                <tr><td></td><tr>
            {% endif %}
        </table>
    </div>
</div>
{% endif %}
