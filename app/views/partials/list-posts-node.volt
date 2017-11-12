<div class="panel-heading">
    <ul class="nav nav-pills" style="float:left;margin-top:2px;">
        {%- set orders = [
            'topic': '话题'
        ] -%}

        {%- for order, label in orders -%}
            <li class="{%- if order == currentTab -%}active{%- endif -%}">
                {{ link_to('node/' ~ node.slug ~ '/' ~ order, label) }}
            </li>
        {%- endfor -%}
    </ul>
    <span style="float:right;">
        {{ link_to('node/' ~ node.slug, node.iconNormal('avatar-normal')) }}
    </span>
    <div class="clearfix"></div>
</div>

{%- if posts|length -%}
<div class="panel-body">
    <table class="table list-items">
    {%- for post in posts -%}
        <tr>
            <td align="left" class="list-author">
                <a href="{{ url("user/" ~ post.user.login) }}" title="{{ post.user.name}}" class="avatar-link">
                    {{ post.user.avatarNormal() }}
                </a>
            </td>
            <td align="left" class="list-title">

                <span class="title">
                    {% if post.sticked == 'Y' %}
                        <span class="octicon octicon-arrow-up post-sticked"></span>
                    {% endif %}
                    {{- link_to('topic/' ~ post.id, post.title|e) -}}
                </span>
            </td>
            <td class="hidden-xs list-repliers text-right">
                <span class="round-number">
                    {{- post.number_replies -}}
                </span>

                {%- cache "post-users-" ~ post.id -%}
                    {%- for id, replier in post.getRecentUsers() -%}
                        {% set replyAuthor = replier[1] %}
                        <a href="{{ url("user/" ~ replyAuthor.login) }}" title="{{ replier[0] }}" class="avatar-link">
                            {{ replyAuthor.avatarNormal('avatar-small left') }}
                        </a>
                    {%- endfor -%}
                {%- endcache -%}

            </td>

            <td class="hidden-xs list-date text-right">
                <span class="post-date">
                    {{- post.getLastActiveTime() -}}
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
    <div class="alert alert-info">空空如也 -_-</div>
</div>
{%- endif -%}
