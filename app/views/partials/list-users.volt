{%
    set moderator = session.get('identity-moderator'),
        currentUser = session.get('identity')
%}
<div class="panel-heading">
        <ul class="nav nav-pills">
            <li class="{%- if 'getvotes' == currentTab -%}active{%- endif -%}">
                {{ link_to('ranks/getvotes', '获赞榜') }}
            </li>
            <li class="{%- if 'sendvotes' == currentTab -%}active{%- endif -%}">
                {{ link_to('ranks/sendvotes', '送赞榜') }}
            </li>
            <li class="{%- if 'wealth' == currentTab -%}active{%- endif -%}">
                {{ link_to('ranks/wealth', '土豪榜') }}
            </li>
        </ul>
</div>

{%- if users|length -%}
<div class="panel-body">
        <table class="table list-items">
        {%- for user in users -%}
            <tr>
                <td align="left" class="list-author">
                    <a href="{{ url("user/" ~ user.login) }}" title="{{ user.name}}" class="avatar-link">
                        {{ user.avatarNormal() }}
                    </a>
                </td>
                <td align="left" class="list-title">
                    <span class="title">
                        {{- link_to('user/' ~ user.login, user.name|e) -}}
                    </span>
                </td>

                <td class="hidden-xs list-text text-right">
                    <span>
                        {% if currentTab == 'getvotes' %}
                            获得{{- user.votes_receive -}}赞
                        {% elseif currentTab == 'sendvotes' %}
                            送出{{- user.votes_send -}}赞
                        {% elseif currentTab == 'wealth' %}
                            拥有{{- user.money -}}微币
                        {% endif %}
                    </span>
                </td>
            </tr>
        {%- endfor -%}
        </table>
</div>

<div class="col-md-12">
    <ul class="pager">
        {%- if offset > 0 -%}
            <li class="previous">{{ link_to(paginatorUri ~ '/' ~ (offset - limitPost), '上一页', 'rel': 'prev') }}</li>
        {%- endif -%}

        {%- if totalPosts > (offset + limitPost) -%}
            <li class="next">{{ link_to(paginatorUri ~ '/' ~ (offset + limitPost), '下一页', 'rel': 'next') }}</li>
        {%- endif -%}
    </ul>
</div>

{%- else -%}
<div class="panel-body" align="center">
    <br>
    <div class="alert alert-info">没有用户</div>
</div>
{%- endif -%}

{%- include 'partials/popup/error-modal.volt' -%}
