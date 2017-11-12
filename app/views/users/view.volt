{{ content() }}

{%-
    set tokenKey     = security.getPrefixedTokenKey('user-' ~ user.id),
        token        = security.getPrefixedToken('user-' ~ user.id)
-%}

<div class="panel panel-default">
    <div class="panel-heading">
        <ul class="nav nav-pills">
            {%- set orders = [
                'activity': '个人动态',
                'index': '关注的人',
                'followers': '被谁关注'
            ] -%}
            {%- for order, label in orders -%}
                <li class="{%- if order == currentTab -%}active{%- endif -%}">
                    {{ link_to('user/' ~ user.login ~ '/' ~ order, label) }}
                </li>
            {%- endfor -%}
        </ul>
    </div>
    <div class="panel-body">
        <div class="profile-content">
            {% if 'index' == currentTab %}
                {% if myFollowings|length %}
                    <table class="table list-items">
                    {%- for userFollow in myFollowings -%}
                        <tr>
                            <td align="left" class="list-author">
                                <a href="{{ url("user/" ~ userFollow.user.login) }}" title="{{ userFollow.user.name}}" class="avatar-link">
                                    {{ userFollow.user.avatarNormal('avatar-small') }}
                                </a>
                            </td>
                            <td align="left" class="list-title">
                                <span class="title">
                                    {{- link_to('user/' ~ userFollow.user.login, userFollow.user.name) -}}
                                </span>
                            </td>
                            {% if myself.id == user.id %}
                            <td class="hidden-xs list-date text-right">
                                <a class="btn btn-success btn-xs btn-user-unfollow" data-id="{{ userFollow.user.id }}">
                                    <span class="glyphicon glyphicon-star"></span>&nbsp;已关注
                                </a>
                            </td>
                            {% endif %}
                        </tr>
                    {%- endfor -%}
                    </table>
                {% else %}
                    <br>
                    <div class="alert alert-info" align="center">未关注他人</div>
                {% endif %}
            {% elseif 'followers' == currentTab %}
                {% if myFollowers|length %}
                    <table class="table list-items">
                    {%- for userFollow in myFollowers -%}
                        <tr>
                            <td align="left" class="list-author">
                                <a href="{{ url("user/" ~ userFollow.follower.login) }}" title="{{ userFollow.follower.name}}" class="avatar-link">
                                    {{ userFollow.follower.avatarNormal('avatar-small') }}
                                </a>
                            </td>
                            <td align="left" class="list-title">
                                <span class="title">
                                    {{- link_to('user/' ~ userFollow.follower.login, userFollow.follower.name) -}}
                                </span>
                            </td>
                        </tr>
                    {%- endfor -%}
                    </table>
                {% else %}
                    <br>
                    <div class="alert alert-info" align="center">未被人关注</div>
                {% endif %}
            {% elseif 'node' == currentTab %}
                {% if myNodes|length %}
                    <table class="table list-items">
                    {%- for userNode in myNodes -%}
                        <tr>
                            <td align="left" class="list-author">
                                <a href="{{ url("node/" ~ userNode.node.slug) }}" title="{{ userNode.node.name}}" class="avatar-link">
                                    {{ userNode.node.iconNormal('avatar-small') }}
                                </a>
                            </td>
                            <td align="left" class="list-title">
                                <span class="title">
                                    {{- link_to('node/' ~ userNode.node.slug, userNode.node.name) -}}
                                </span>
                            </td>
                            {% if myself.id == user.id %}
                            <td class="hidden-xs list-date text-right">
                                <a class="btn btn-success btn-xs btn-node-unfollow" data-id="{{ userNode.node.id }}">
                                    <span class="glyphicon glyphicon-star"></span>&nbsp;已关注
                                </a>
                            </td>
                            {% endif %}
                        </tr>
                    {%- endfor -%}
                    </table>
                {% else %}
                    <br>
                    <div class="alert alert-info" align="center">未关注节点</div>
                {% endif %}
            {% elseif 'activity' == currentTab %}
                {% set has_activity = false %}
                {%- for activity in activities -%}
                    {%- if activity.post and activity.post.deleted != 1 -%}
                        {% set has_activity = true %}
                        <div class="activity-list">
                            <div class="activity-list-body">
                            <span class="activity-icon">{{ user.avatarNormal('avatar-small') }}</span>
                            {%- if activity.type == 'VP' -%}
                                赞了话题《{{ link_to('topic/' ~ activity.post.id, activity.post.title|e) }}》
                            {%- elseif activity.type == 'VR' -%}
                                赞了回复《{{ link_to('topic/' ~ activity.post.id ~ '#C' ~ activity.posts_replies_id, activity.post.title|e) }}》
                            {%- elseif activity.type == 'NP' -%}
                                发布了话题《{{ link_to('topic/' ~ activity.post.id, activity.post.title|e) }}》
                            {%- elseif activity.type == 'NR' -%}
                                回复了《{{ link_to('topic/' ~ activity.post.id, activity.post.title|e) }}》
                            {%- endif -%}
                            <span class="small right"><time>{{ activity.getHumanCreatedAt() }}</time></span>
                            </div>
                        </div>
                    {%- endif -%}

                    {%- if activity.type == 'FU' -%}
                    <div class="activity-list">
                        <div class="activity-list-body">
                            <span class="activity-icon">{{ user.avatarNormal() }}</span>
                            关注了 {{ link_to('user/' ~ activity.followUser.login, activity.followUser.name) }}
                            <span class="small right"><time>{{ activity.getHumanCreatedAt() }}</time></span>
                        </div>
                    </div>
                    {%- endif -%}

                {%- endfor -%}

                {% if !has_activity %}
                    <br>
                    <div class="alert alert-info" align="center">尚无动态</div>
                {% endif %}
            {% endif %}
        </div>
    </div>
</div>

{%- include 'partials/popup/error-modal.volt' -%}
{%- include 'partials/popup/send-message' with ['post': post, 'tokenKey': tokenKey, 'token': token] -%}
