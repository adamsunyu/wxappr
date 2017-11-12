{% if whoFollowed|length %}
<div class="module sidebar-margin-top">
    <div class="module-head">关注{{ node.name}}的用户</div>
    <div class="module-body">
        <table class="table-stats">
            {%- for userNode in whoFollowed -%}
            <tr>
                <td width="40px">
                    <a href="{{ url("user/" ~ userNode.user.login) }}" title="{{ userNode.user.name }}" class="avatar-link">
                        {{ userNode.user.avatarNormal() }}
                    </a>
                </td>
                <td>
                    <a href="{{ url("user/" ~ userNode.user.login) }}" title="{{ userNode.user.name }}" class="left">
                    {{ userNode.user.name }}
                    </a>
                </td>
            </tr>
            {%- endfor -%}
        </table>
    </div>
</div>
{% endif %}
