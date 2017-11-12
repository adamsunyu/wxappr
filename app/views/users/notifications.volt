{{ content() }}
<div class="panel panel-default">
    <div class="panel-heading">
        <ul class="nav nav-pills">
            {%- set orders = [
                'activity': '我的消息'
            ] -%}
            {%- for order, label in orders -%}
                <li class="{%- if order == currentTab -%}active{%- endif -%}">
                    {{ link_to('/notifications', label) }}
                </li>
            {%- endfor -%}
        </ul>
    </div>

    <div class="panel-body">
        <div class="profile-content">

            {% set has_activity = false %}

            {%- for activity in notifications -%}

                {# if activity.post and activity.post.deleted == 1 #}
                    {# continue #}
                {# endif #}

                {% set has_activity = true %}

                <div class="activity-list">
                    <div class="activity-list-body">

                        <span class="activity-icon">
                            {% if activity.userOrigin.id == 0 %}
                                {{ image('site/system-60x60.png', 'width': 30, 'height': 30, 'class': 'img-circle') }}
                            {% else %}
                                {{ link_to('user/' ~ activity.userOrigin.login, activity.userOrigin.avatarNormal('avatar-small')) }}
                            {% endif %}
                        </span>

                        <span class="activity-content">

                        {%- if activity.type == 'II' -%}
                            欢迎加入社区 <span class="label label-success">+{{ activity.extra }}微币</span>
                        {%- endif -%}

                        {%- if activity.type == 'DI' -%}
                            24小时活跃榜上榜 <span class="label label-success">+{{ activity.extra }}微币</span>
                        {%- endif -%}

                        {%- if activity.type == 'GM' -%}
                            {{ link_to('user/' ~ activity.userOrigin.login, activity.userOrigin.name) }} 发给你一封私信 {{ link_to('inbox', '“' ~ activity.extra ~ '”') }}
                        {%- endif -%}

                        {%- if activity.type == 'PS' -%}
                            恭喜，你的话题被置顶 {{ link_to('topic/' ~ activity.post.id, '《' ~ activity.post.title|e ~ '》') }}
                            <span class="label label-success">+{{ activity.extra }}微币</span>
                        {%- endif -%}

                        {%- if activity.type == 'MP' -%}
                            你的话题被管理员删除 <del>{{ link_to('mytopic/' ~ activity.post.id, '《' ~ activity.post.title|e ~ '》') }}</del>
                            <span class="label label-danger">-{{ activity.extra }}微币</span>
                        {%- endif -%}

                        {%- if activity.type == 'MR' -%}
                            你的回复被管理员删除 <del>{{ link_to('topic/' ~ activity.post.id ~ '#C' ~ activity.posts_replies_id, '《' ~ activity.post.title|e ~ '》') }}</del>
                            <span class="label label-danger">-{{ activity.extra }}微币</span>
                        {%- endif -%}

                        {%- if activity.type == 'UF' -%}
                            {{ link_to('user/' ~ activity.userOrigin.login, activity.userOrigin.name) }}&nbsp;关注了你
                        {%- endif -%}

                        {%- if activity.type == 'VP' -%}
                            赞了你的话题 {{ link_to('topic/' ~ activity.post.id, '《' ~ activity.post.title|e ~ '》') }}
                        {%- endif -%}

                        {%- if activity.type == 'NR' -%}
                            {% if activity.post.users_id == user.id %}
                                回复了你的话题 {{ link_to('topic/' ~ activity.post.id ~ '#C' ~ activity.posts_replies_id, '《' ~ activity.post.title|e ~ '》') }}
                            {%- else -%}
                                回复了话题 {{ link_to('topic/' ~ activity.post.id ~ '#C' ~ activity.posts_replies_id, '《' ~ activity.post.title|e ~ '》') }}
                            {%- endif -%}
                        {%- endif -%}

                        {%- if activity.type == 'VR' -%}
                            赞了你的回复 {{ link_to('topic/' ~ activity.post.id ~ '#C' ~ activity.posts_replies_id, '《' ~ activity.post.title|e ~ '》') }}
                        {%- endif -%}

                        {%- if activity.type == 'UT' -%}

                            {%- if activity.posts_id != null and activity.posts_replies_id == null -%}
                                感谢了你的话题 {{ link_to('topic/' ~ activity.posts_id, '《' ~ activity.post.title|e ~ '》') }}
                            {%- endif -%}

                            {%- if activity.posts_id != null and activity.posts_replies_id != null -%}
                                感谢了你的回复 {{ link_to('topic/' ~ activity.posts_id ~ '#C' ~ activity.posts_replies_id, '《' ~ activity.post.title|e ~ '》') }}
                            {%- endif -%}

                            <span class="label label-success">+{{ activity.extra }}微币</span>

                        {%- endif -%}

                        </span>

                        <span class="small right"><time>{{ activity.getHumanCreatedAt() }}</time></span>
                    </div>
                </div>

    			{% do activity.markAsRead() %}

            {%- endfor -%}

            {% if !has_activity %}
                <br>
                <div class="alert alert-info" align="center">暂无消息</div>
            {% endif %}
        </div>
    </div>
</div>
