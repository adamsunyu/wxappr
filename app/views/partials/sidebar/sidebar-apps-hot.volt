<div class="module sidebar-margin-top">
    <div class="module-head">热门小程序</div>
    <div class="module-body">
        <table class="table-stats">
            {%- for eachApp in hotApps -%}
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
