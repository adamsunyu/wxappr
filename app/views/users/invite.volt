{{ content() }}
{%-
    set currentUser  = session.get('identity'),
        orderCode = 1
-%}

<div class="panel panel-default">
    <div class="panel-heading">
        <div class="row">
            <div class="col-md-9">
                <ul class="nav nav-pills">
                    {%- set orders = [
                        'home': '我的邀请码'
                    ] -%}
                    {%- for order, label in orders -%}
                        <li class="{%- if order == currentTab -%}active{%- endif -%}">
                            {{ link_to('invite/' ~ order, label) }}
                        </li>
                    {%- endfor -%}
                </ul>
            </div>
            <div class="col-md-3 text-right">
            </div>
        </div>
    </div>
    <div class="panel-body">
        <div class="setting-section">
            <table class="table table-bordered table-stripped" width="100%">
                <tr>
                    <th width="6%">序号</th><th width="50%">邀请链接</th><th width="19%">状态</th><th width="15%">创建时间</th>
                </tr>
                {% if myCodes|length %}
                    {% for eachCode in myCodes %}
                    <tr>
                        <td class="text-center">{{ orderCode }}</td>
                        {% set orderCode = orderCode + 1 %}
                        <td>https://www.wxappr.com/join/{{ eachCode.invite_code }}</td>
                        <td>{% if eachCode.used == 'N' %}
                            未使用
                            {% else %}
                            已邀请{{ link_to('user/' ~ eachCode.invitee.login, eachCode.invitee.name) }}
                            {% endif %}
                        </td>
                        <td style="font-size:1.1rem">{{ eachCode.getFormalCreatedAt() }}</td>
                    </tr>
                    {% endfor %}
                {% else %}
                <tr>
                    <td colspan="4" align="center">无记录</td>
                </tr>
                {% endif %}
            </table>
        </div>
    </div>
</div>

{%- include 'partials/popup/error-modal.volt' -%}
