{% if activeUsers|length %}
<div class="module">
    <div class="module-head">24小时活跃榜</div>
    <div class="module-body">
        <table class="table-stats">
            {%- for activity in activeUsers -%}
            <tr>
                <td width="40px">
                    <a href="{{ url("user/" ~ activity.user.login) }}" title="{{ activity.user.name}}" class="avatar-link">
                    {{ activity.user.avatarNormal('avatar-small') }}
                    </a>
                </td>
                <td>
                    <a href="{{ url("user/" ~ activity.user.login) }}" title="{{ activity.user.name}}" class="left">
                    {{ activity.user.name }}
                    </a>
                </td>
                <td class="text-right"><span style="color: #777;font-size:11px;">{{ activity.getHumanModifiedAt() }}</span></td>
            </tr>
            {%- endfor -%}
        </table>
    </div>
</div>
{% endif %}
