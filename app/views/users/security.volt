{{ content() }}

<div class="panel panel-default">
    <div class="panel-heading">
        <ul class="nav nav-pills">
            {%- set orders = [
                'settings': '基本资料',
                'security': '账号密码',
                'social': '社交资料'
            ] -%}
            {%- for order, label in orders -%}
                <li class="{%- if order == currentTab -%}active{%- endif -%}">
                    {{ link_to('account/' ~ order, label) }}
                </li>
            {%- endfor -%}
        </ul>
    </div>
    <div class="panel-body">
        <div class="setting-section">
            <br>
            <table class="setting" width="100%">
                <tr><td width="10%"><span class="cate">邮箱:</span></td><td width="20%">{{ user.email }}</td><td></td></tr>

                {% if user.signup_source == 'G' %}
                <tr><td><span class="cate">密码:</span></td><td>******</td><td>{{ link_to('account/changePassword', '设置密码', 'class' : 'setting-description') }} &nbsp; <span class="setting-description">(你当前使用Github登录，设置密码之后可用邮箱登录)</span></td></tr>
                {% else %}
                <tr><td><span class="cate">密码:</span></td><td>******</td><td>{{ link_to('account/changePassword', '修改密码', 'class' : 'setting-description') }}</td></tr>
                {% endif %}

                {% if user.signup_source == 'G' %}
                <tr><td><span class="cate">Github:</span></td><td>已绑定{{user.github_login}}</td><td><span class="setting-description">(提醒: 修改Github用户名会导致无法登陆本站，建议使用邮箱登录)</span></td></tr>
                {% endif %}
            </table>
            <br>
        </div>
    </div>
</div>

{%- include 'partials/popup/error-modal.volt' -%}
