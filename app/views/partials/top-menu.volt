<nav class="navbar navbar-fixed-top navbar-light bg-faded nav-top-menu" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#forum-navbar-collapse">
                <span class="sr-only">Toggle</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            {{ link_to('',  '<span class="header-logo">' ~ image('site/logo-60x60.png', 'width': '30', 'height':'30') ~ '</span>', 'class': 'navbar-brand', 'title': '首页') }}
        </div>

        <div class="collapse navbar-collapse" id="forum-navbar-collapse">
            <ul class="nav navbar-nav navbar-left">
                <li class="navbar-item">
                    {{- link_to(
                        '/apps',
                        '小程序'
                    ) -}}
                </li>
                <li class="navbar-item">
                    {{- link_to(
                        '/bbs',
                        '论坛'
                    ) -}}
                </li>
                <li class="navbar-item">
                    {{- link_to(
                        '/ranks',
                        '会员'
                    ) -}}
                </li>
                <li class="navbar-item">
                    {{- link_to(
                        '/cities',
                        '城市'
                    ) -}}
                </li>

            </li>
            </ul>

            <ul class="nav navbar-nav navbar-right">
                {%- if session.get('identity') -%}

                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" style="height:44px;" data-toggle="dropdown" id="dropdownPost" role="button" aria-haspopup="true" aria-expanded="false">
                        <span class="glyphicon glyphicon-pencil" style="font-size:75%"></span> 发布
                    </a>

                    <ul class="dropdown-menu" aria-labelledby="dropdownPost">
                        <li>{{ link_to('new/link', '<span>链接</span>') }}</li>
                        <li>{{ link_to('new/idea', '<span>话题</span>') }}</li>
                        <li>{{ link_to('new/question', '<span>问题</span>') }}</li>
                        <li>{{ link_to('create/app', '<span>小程序</span>') }}</li>
                    </ul>
                </li>

                <li class="navbar-item">
                    {{- link_to('notifications', '<span class="glyphicon glyphicon-bell small"></span> 消息', 'title': '消息') -}}
                    {%- if notifications.has() -%}
                        <span class="notification-counter">{{ notifications.getNumber() }}</span>
                    {%- endif -%}
                </li>

                <li class="dropdown navbar-avatar">
                    <a href="#" class="dropdown-toggle" style="height:44px;" data-toggle="dropdown" id="dropdownCategories" role="button" aria-haspopup="true" aria-expanded="false">
                        {{ myself.avatarNormal('avatar-small') }}
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="dropdownCategories">
                        <li>{{ link_to('user/' ~ session.get('identity-login'), '<span class="glyphicon glyphicon-home"></span>&nbsp;&nbsp;<span>我的主页</span>') }}</li>
                        <li>{{ link_to('inbox', '<span class="glyphicon glyphicon-envelope"></span>&nbsp;&nbsp;<span>私信</span>') }}</li>
                        <li>{{ link_to('wallet', '<span class="glyphicon glyphicon-bitcoin"></span>&nbsp;&nbsp;<span>钱包</span>') }}</li>
                        <li>{{ link_to('account/settings', '<span class="glyphicon glyphicon-cog"></span>&nbsp;&nbsp;<span>设置</span>') }}</li>
                        <li>{{ link_to('help/about', '<span class="glyphicon glyphicon-info-sign"></span>&nbsp;&nbsp;<span>帮助</span>') }}</li>
                        <li>{{ link_to('account/logout', '<span class="glyphicon glyphicon-off"></span>&nbsp;&nbsp;<span>退出</span>') }}</li>
                    </ul>
                </li>

                {%- else -%}
                <li>
                    {{ link_to('account/welcome', '注册') }}
                </li>
                <li>
                    {{ link_to('account/login', '登录') }}
                </li>
                {%- endif -%}
            </ul>
        </div>
    </div>
</nav>
