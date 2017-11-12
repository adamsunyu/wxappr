{%
    set moderator = session.get('identity-moderator'),
        currentUser = session.get('identity')
%}
<div class="panel-heading">
    <div class="row">
        <div class="col-md-9">
            <ul class="nav nav-pills">
                {%- for nodeCate in nodeCategories -%}
                    <li class="{%- if nodeCate.slug == current -%}active{%- endif -%}">
                        {{ link_to('nodes/' ~ nodeCate.slug, nodeCate.name) }}
                    </li>
                {%- endfor -%}
            </ul>
        </div>
        <div class="col-md-3 text-right">
            {% if moderator == 'Y' %}
                {{ link_to('node-create' , '<button type="button" class="btn btn-small btn-success">创建节点</button>') }}
            {% endif %}
        </div>
    </div>
</div>

{%- if nodes|length -%}
<div class="panel-body">
        <table class="table list-items">
        {%- for node in nodes -%}
            <tr>
                <td align="left" class="list-author">
                    <a href="{{ url("node/" ~ node.slug) }}" title="{{ node.name}}" class="avatar-link">
                        {{ node.iconNormal() }}
                    </a>
                </td>
                <td align="left" class="list-title">
                    <span class="title">
                        {{- link_to('node/' ~ node.slug, node.name|e) -}}
                    </span>
                </td>

                <td class="hidden-xs list-text text-right">
                    <span>
                        {{- node.number_followers -}}关注
                    </span>
                </td>

                <td class="hidden-xs list-date text-right">

                    {% if node.followed %}
                        <a class="btn btn-success btn-xs btn-node-unfollow" data-id="{{ node.id }}">
                            <span class="glyphicon glyphicon-star"></span>&nbsp;已关注
                        </a>
                    {% else %}
                        {% if currentUser %}
                            <a class="btn btn-default btn-xs btn-node-follow" data-id="{{ node.id }}">
                                <span class="glyphicon glyphicon-star-empty"></span>&nbsp;关注
                            </a>
                        {% else %}
                            <a class="btn btn-default btn-xs need-login" data-id="{{ node.id }}">
                                <span class="glyphicon glyphicon-star-empty"></span>&nbsp;关注
                            </a>
                        {% endif %}
                    {% endif %}
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
    <div class="alert alert-info">没有节点</div>
</div>
{%- endif -%}

{%- include 'partials/popup/error-modal.volt' -%}
