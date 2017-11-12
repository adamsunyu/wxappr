{%
    set moderator = session.get('identity-moderator'),
        currentUser = session.get('identity')
%}
<div class="panel-heading">
        <ul class="nav nav-pills">
            {%- for cityTab in cityList -%}
                <li class="{%- if cityTab.slug == currentTab -%}active{%- endif -%}">
                    {{ link_to('cities/' ~ cityTab.slug, cityTab.name) }}
                </li>
            {%- endfor -%}
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

                        {% if city.slug == 'other' %}
                            (来自{{ user.city_name }})
                        {% endif %}
                    </span>
                </td>

                <td class="hidden-xs list-text text-right">
                    <span>
                        {{- user.getLastActivityTime() -}}
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
