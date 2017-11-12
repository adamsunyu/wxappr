{{ content() }}
{%-
    set currentUser  = session.get('identity')
-%}

<div class="panel panel-default">
    <div class="panel-heading">
        <ul class="nav nav-pills">
            {%- set orders = [
                'home': '我的钱包',
                'log': '收支记录'
            ] -%}
            {%- for order, label in orders -%}
                <li class="{%- if order == currentTab -%}active{%- endif -%}">
                    {{ link_to('wallet/' ~ order, label) }}
                </li>
            {%- endfor -%}
        </ul>
    </div>
    <div class="panel-body">
        <div class="setting-section">
            <br>
            {% if currentTab == 'home' %}

                <table class="table table-bordered table-stripped" width="100%">
                    <tr><th class="text-center">总资产: {{ myself.money }}微币</th></tr>
                    
                </table>
            {% elseif currentTab == 'log' %}
                <table class="table table-bordered table-stripped" width="100%">
                    <tr>
						<th width="8%">类目</th><th width="15%">金额</th><th width="15%">余额</th><th width="40%">备注</th><th width="22%">时间</th>
					</tr>
                    {% if moneyLog|length %}
                        {% for eachLog in moneyLog %}
                        <tr>
                            <td>{{ eachLog.getBasicInfo() }}</td>
                            <td>{{ eachLog.getMoneyInfo() }}</td>
                            <td>{% if eachLog.balance %} {{ eachLog.balance }}微币 {% else %} N/A {% endif%}</td>
                            <td>{{ eachLog.getDetailInfo() }}</td>
                            <td style="font-size:1.1rem">{{ eachLog.getFormalCreatedAt() }}</td>
                        </tr>
                        {% endfor %}
                    {% else %}
                    <tr>
                        <td colspan="4" align="center">无记录</td>
                    </tr>
                    {% endif %}
                </table>
            {% elseif currentTab == 'withdraw' %}
                <div class="alert alert-warning center-block">需要达到100元才能提现，你当前的资产为{{ myself.moneyRMB() }}元RMB</div>
            {% else %}
                <div class="alert alert-warning center-block">本站采用邀请制，初次充值将会扣除200微币做为邀请人的奖励。</div>
                <div style="width:50%;" class="center-block"><img  class="center-block" style="width:95%;" src="/site/wechat-pay-2.png"></div>
            {% endif %}
            <br>
        </div>
    </div>
</div>

{%- include 'partials/popup/error-modal.volt' -%}
