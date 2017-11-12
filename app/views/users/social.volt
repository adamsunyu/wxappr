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
                <tr>
                    <td width="15%"><span class="cate">性别:</span></td>
                    <td width="30%">
                        <span id="setting-social-a" class="setting-social">{{ socialData['gender'] }}</span>
                        <div id="setting-form-a" class="setting-social-form" style="width:35%">{{ form.render('gender') }}</div>
                    </td>
                    <td>
                        <a href="javascript:;" data-id="a" class="setting-social-edit">修改</a>
                        <span id="setting-btn-a" data-id="a" class="setting-social-form">{{ form.render("确定") }}</span>
                    </td>
                </tr>
                <tr>
                    <td><span class="cate">专长:</span></td>
                    <td>
                        <span id="setting-social-c" class="setting-social">{{ socialData['skills'] }}</span>
                        <div id="setting-form-c" class="setting-social-form" style="width:80%">{{ form.render('skills') }}</div>
                    </td>
                    <td>
                        <a href="javascript:;" data-id="c" class="setting-social-edit">修改</a>
                        <span id="setting-btn-c" data-id="c" class="setting-social-form">{{ form.render("确定") }}</span>
                    </td>
                </tr>
                <tr>
                    <td><span class="cate">网站:</span></td>
                    <td>
                        <span id="setting-social-f" class="setting-social">{{ socialData['website'] }}</span>
                        <div id="setting-form-f" class="setting-social-form" style="width:80%">{{ form.render('website') }}</div>
                    </td>
                    <td>
                        <a href="javascript:;" data-id="f" class="setting-social-edit">修改</a>
                        <span id="setting-btn-f" data-id="f" class="setting-social-form">{{ form.render("确定") }}</span>
                    </td>
                </tr>
                <tr>
                    <td><span class="cate">知乎:</span></td>
                    <td>
                        <span id="setting-social-h" class="setting-social">{{ socialData['zhihu'] }}</span>
                        <div id="setting-form-h" class="setting-social-form" style="width:80%">{{ form.render('zhihu') }}</div>
                    </td>
                    <td>
                        <a href="javascript:;" data-id="h" class="setting-social-edit">修改</a>
                        <span id="setting-btn-h" data-id="h" class="setting-social-form">{{ form.render("确定") }}</span>
                    </td>
                </tr>
                <tr>
                    <td><span class="cate">微博:</span></td>
                    <td>
                        <span id="setting-social-e" class="setting-social">{{ socialData['weibo'] }}</span>
                        <div id="setting-form-e" class="setting-social-form" style="width:80%">{{ form.render('weibo') }}</div>
                    </td>
                    <td>
                        <a href="javascript:;" data-id="e" class="setting-social-edit">修改</a>
                        <span id="setting-btn-e" data-id="e" class="setting-social-form">{{ form.render("确定") }}</span>
                    </td>
                </tr>
                <tr>
                    <td><span class="cate">公众号:</span></td>
                    <td>
                        <span id="setting-social-g" class="setting-social">{{ socialData['gzhao'] }}</span>
                        <div id="setting-form-g" class="setting-social-form" style="width:80%">{{ form.render('gzhao') }}</div>
                    </td>
                    <td>
                        <a href="javascript:;" data-id="g" class="setting-social-edit">修改</a>
                        <span id="setting-btn-g" data-id="g" class="setting-social-form">{{ form.render("确定") }}</span>
                    </td>
                </tr>

                <tr>
                    <td><span class="cate">Github:</span></td>
                    <td>
                        <span id="setting-social-d" class="setting-social">{{ socialData['github'] }}</span>
                        <div id="setting-form-d" class="setting-social-form" style="width:80%">{{ form.render('github') }}</div>
                    </td>
                    <td>
                        <a href="javascript:;" data-id="d" class="setting-social-edit">修改</a>
                        <span id="setting-btn-d" data-id="d" class="setting-social-form">{{ form.render("确定") }}</span>
                    </td>
                </tr>
            </table>
            <br>
        </div>
    </div>
</div>

{%- include 'partials/popup/error-modal.volt' -%}
