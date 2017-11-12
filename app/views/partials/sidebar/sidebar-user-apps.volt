{%
    set currentUser = session.get('identity')
%}
{% if currentUser %}
<div class="module sidebar-margin-top">
    <div class="module-head">我创建的小程序</div>
    <div class="module-body">
        <table class="table-stats">
            {% if myApps|length %}
                {%- for eachApp in myApps -%}
                <tr>
                    <td width="40px">
                        <a href="{{ url("app/" ~ eachApp.id) }}" title="{{ eachApp.name }}" class="avatar-link">
                            {{ eachApp.iconNormal('left') }}
                        </a>
                    </td>
                    <td>
                        <a href="{{ url("app/" ~ eachApp.id) }}" title="{{ eachApp.name }}" class="left">
                            {{ eachApp.name }}
                        </a>
                    </td>
                </tr>
                {%- endfor -%}
            {% else %}
                <tr><td></td><tr>
                <tr><td style="text-align:center;">尚未创建</td></tr>
                <tr><td></td><tr>
            {% endif %}
        </table>
    </div>
</div>
{% endif %}
