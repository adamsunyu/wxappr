{%- if otherUser -%}
    {% set theUser = otherUser %}
    {% set whoName = '他' %}
{%- else -%}
    {% set theUser = user %}
    {% set whoName = '我' %}
{%- endif -%}

<div class="module sidebar-margin-top">
    <div class="module-head">{{ whoName }}的活动</div>
    <div class="module-body">
        <table class="table-stats">
            <tr>
                <td><label>主题:</label>{{ numberPosts }}</td>
                <td><label>送出赞:</label>{{ theUser.votes_send }}</td>
            </tr>
            <tr>
                <td><label>回复:</label>{{ numberReplies }}</td>
                <td><label>获得赞:</label>{{ theUser.votes_receive }}</td>
            </tr>
        </table>
    </div>
</div>
