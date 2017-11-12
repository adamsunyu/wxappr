{%- if otherUser -%}
    {% set theUser = otherUser %}
    {% set whoName = '他' %}
{%- else -%}
    {% set theUser = user %}
    {% set whoName = '我' %}
{%- endif -%}

<div class="module sidebar-margin-top">
    <div class="module-head">{{ whoName }}的成就</div>
    <div class="module-body">
        <table class="table-stats">
            <tr>
                <td><label>获得赞:</label>{{ theUser.votes_receive }}赞</td>
            </tr>
            <tr>
                <td><label>等级星:</label>{{ theUser.getUserLevelStar() }}</td>
            </tr>
            <tr>
                <td><label>总财富:</label>{{ theUser.money }}微币</td>
            </tr>
        </table>
    </div>
</div>
