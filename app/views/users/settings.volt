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
                <tr><td width="10%"><span class="cate">姓名:</span></td><td width="30%">{{ user.name }}<span></span></td><td>{{ link_to('account/changeName', '修改姓名', 'class' : 'setting-description') }}</td></tr>
                <tr><td width="10%"><span class="cate">城市:</span></td><td width="30%">{{ user.city_name }}<span></span></td><td>{{ link_to('account/changeCity', '修改城市', 'class' : 'setting-description') }}</td></tr>
                <tr><td><span class="cate">域名:</span></td><td width="15%">@{{ user.login }}<span></span></td><td>{{ link_to('account/changeDomain', '修改域名', 'class' : 'setting-description') }}</td></tr>
                <tr><td>
                    <span class="cate">头像:</span></td><td>{{ user.avatarNormal() }}</td><td>
                        <div class="setting-avatar">
                            <i>上传头像</i>
                            <div class="upload-box">
                                <input id="input-upload-avatar" accept="image/png,image/jpg,image/jpeg" type="file">
                            </div>
                        </div>
                    </td>
                </tr>
            </table>
            <br>
        </div>
    </div>
</div>

{%- include 'partials/popup/error-modal.volt' -%}
