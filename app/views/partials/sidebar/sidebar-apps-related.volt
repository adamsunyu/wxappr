{% if tag1Apps | length %}
<div class="module sidebar-margin-top">
    <div class="module-head">{{ appTag1.name }}类小程序排行</div>
    <div class="module-body">
        <table class="table-stats">
            {%- for eachApp in tag1Apps -%}
            <tr>
                <td align="left" style="width:40px;">
                    <a href="{{ url("app/" ~ eachApp.id) }}" title="{{ eachApp.name}}" class="avatar-link">
                        {{ eachApp.iconSmall() }}
                    </a>
                </td>
                <td align="left">
                    <span class="title">
                        <a href="{{ url("app/" ~ eachApp.id) }}" title="{{ eachApp.name }}">
                            {{ eachApp.name }}
                        </a>
                    </span>
                </td>
            </tr>
            {%- endfor -%}
        </table>
    </div>
</div>
{% endif %}

{% if tag2Apps | length %}
<div class="module sidebar-margin-top">
    <div class="module-head">{{ appTag2.name }}类小程序排行</div>
    <div class="module-body">
        <table class="table-stats">
            {%- for eachApp in tag2Apps -%}
            <tr>
                <td align="left" style="width:40px;">
                    <a href="{{ url("app/" ~ eachApp.id) }}" title="{{ eachApp.name}}" class="avatar-link">
                        {{ eachApp.iconNormal() }}
                    </a>
                </td>
                <td align="left">
                    <span class="title">
                        <a href="{{ url("app/" ~ eachApp.id) }}" title="{{ eachApp.name }}">
                            {{ eachApp.name }}
                        </a>
                    </span>
                </td>
            </tr>
            {%- endfor -%}
        </table>
    </div>
</div>
{% endif %}

{% if tag3Apps | length %}
<div class="module sidebar-margin-top">
    <div class="module-head">{{ appTag3.name }}类小程序排行</div>
    <div class="module-body">
        <table class="table-stats">
            {%- for eachApp in tag3Apps -%}
            <tr>
                <td align="left" style="width:40px;">
                    <a href="{{ url("app/" ~ eachApp.id) }}" title="{{ eachApp.name}}" class="avatar-link">
                        {{ eachApp.iconNormal() }}
                    </a>
                </td>
                <td align="left">
                    <span class="title">
                        <a href="{{ url("app/" ~ eachApp.id) }}" title="{{ eachApp.name }}">
                            {{ eachApp.name }}
                        </a>
                    </span>
                </td>
            </tr>
            {%- endfor -%}
        </table>
    </div>
</div>
{% endif %}
