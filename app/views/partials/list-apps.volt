{%
    set moderator = session.get('identity-moderator'),
        currentUser = session.get('identity'),
        inTagList = false
%}
<div class="panel-heading">
        <ul class="nav nav-pills">

            {%- for theTag in tagList -%}
                {% if theTag.id == currentTag.id %}
                   {% set inTagList = true %}
                {% endif %}
                <li class="{%- if theTag.id == currentTag.id -%}active{%- endif -%}">
                    {{ link_to('apps/' ~ theTag.id, theTag.name) }}
                </li>
            {%- endfor -%}

            <li class="{%- if 'rank' == appTagId -%}active{%- endif -%}">
                {{ link_to('apps/rank', '排行榜') }}
            </li>

            {% if keywords %}
                <li class="active">
                    {{ link_to('search/app/' ~ keywords, keywords) }}
                </li>
            {% endif %}

            {% if inTagList == false and currentTag %}
                <li class="active">
                    {{ link_to('apps/' ~ currentTag.id, currentTag.name) }}
                </li>
            {% endif %}

        </ul>
</div>

{%- if appList|length -%}
<div class="panel-body" id="app-list-page">
    <table class="table list-items">
        <tbody>
            {%- for eachApp in appList -%}
            <tr>
                <td align="left" class="list-author">
                    <a href="{{ url("app/" ~ eachApp.id) }}" title="{{ eachApp.name}}" class="avatar-link">
                        {{ eachApp.iconSmall() }}
                    </a>
                </td>
                <td align="left" class="list-title">
                    <span class="title">
                        <a id="{{ 'app' ~ eachApp.id}}" href="{{ url("app/" ~ eachApp.id) }}" title="{{ eachApp.name }}">
                            {{ eachApp.name }}
                        </a>

                    </span>
                </td>
                <td class="list-node hidden-xs text-right">

                    {% if eachApp.votes_up %}
                        {{- link_to('app/' ~ eachApp.id ~ '#vote-block', eachApp.votes_up ~ '赞', 'class':'square-number') -}}
                    {% endif %}
                    {% if eachApp.number_reviews %}
                        {{- link_to('app/' ~ eachApp.id ~ '#review-block',  eachApp.number_reviews ~ '评', 'class':'square-number') -}}
                    {% endif %}
                </td>
                <td class="list-node hidden-md hidden-lg hidden-sm text-right">
                    <button class="btn btn-default btn-small app-copy-link" data-toggle="tooltip" data-clipboard-target="#app{{ eachApp.id }}" title="复制成功">复制名字</button>
                </td>
                </tr>
            {%- endfor -%}
            </tbody>
        </table>
</div>

<div class="col-md-12">
    <ul class="pager">
        {%- if offset > 0 -%}
            <li class="previous">{{ link_to(paginatorUri ~ '/' ~ (offset - limitPost), '上一页', 'rel': 'prev') }}</li>
        {%- endif -%}

        {%- if totalApps > (offset + limitPost) -%}
            <li class="next">{{ link_to(paginatorUri ~ '/' ~ (offset + limitPost), '下一页', 'rel': 'next') }}</li>
        {%- endif -%}
    </ul>
</div>

{%- else -%}
<div class="panel-body" align="center">
    <br>
    <div class="alert alert-info">没有找到相关的小程序</div>
</div>
{%- endif -%}

{%- include 'partials/popup/error-modal.volt' -%}
