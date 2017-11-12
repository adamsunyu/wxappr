{%-
    set currentUser  = session.get('identity')
-%}

{%- if posts|length -%}
<div class="panel panel-default">
        <div class="panel-heading">
            <ul class="nav nav-pills">
                <li class="{%- if 'new' == currentTab -%}active{%- endif -%}">
                    {{ link_to('bbs/new', '最新') }}
                </li>
                <li class="{%- if 'hot' == currentTab -%}active{%- endif -%}">
                    {{ link_to('bbs/hot', '热门') }}
                </li>
                <li class="{%- if 'ideas' == currentTab -%}active{%- endif -%}">
                    {{ link_to('bbs/ideas', '话题') }}
                </li>
                <li class="{%- if 'questions' == currentTab -%}active{%- endif -%}">
                    {{ link_to('bbs/questions', '问答') }}
                </li>
            </ul>
        </div>
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
                        <span class="title" style="width:100%;">
                            {% if post.sticked == 'Y' %}
                                <span style="background:#777;color:#fff;border-radius:2px;padding:4px 6px;;font-size:85%;">置顶</span>
                            {% else %}
                                <span style="background:#f0f0f0;color:#777;border-radius:2px;padding:4px 6px;;font-size:85%;">
                                    {{- link_to('bbs/' ~ post.node.slug, post.node.name) -}}
                                </span>
                            {% endif %}

                            {% if post.nodes_id == 3 %}
                                <a href="{{ post.link }}" title="{{ post.title|e }}">{{ post.title|e }}</a><sup>&nbsp;<span class="glyphicon glyphicon-link"></span></sup>
                            {% else %}
                                {{- link_to('topic/' ~ post.id, post.title|e) -}}
                            {% endif %}
                        </span>
                    </td>
                    <td class="list-node hidden-xs text-right">
                        {% if post.votes_up %}
                            {{- link_to('topic/' ~ post.id ~ '#vote-block', post.votes_up ~ '赞', 'class':'square-number') -}}
                        {% endif %}
                        {% if post.number_replies %}
                            {{- link_to('topic/' ~ post.id ~ '#reply-block',  post.number_replies ~ '评', 'class':'square-number') -}}
                        {% endif %}
                        {% if post.number_replies == 0 and post.nodes_id == 3%}
                            {{- link_to('topic/' ~ post.id ~ '#reply-block',  '评论', 'class':'square-number') -}}
                        {% endif %}
                    </td>
                    <!-- <td class="list-date hidden-xs text-right">
                        <span class="post-date">
                            {{- post.getLastActiveTime() -}}
                        </span>
                    </td> -->
                </tr>
                {%- endfor -%}
        </table>
    </div>
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
    <div class="alert alert-info">没有话题</div>
</div>
{%- endif -%}
