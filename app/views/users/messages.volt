{{ content() }}

{%-
    set tokenKey     = security.getPrefixedTokenKey('user-' ~ user.id),
        token        = security.getPrefixedToken('user-' ~ user.id)
-%}

<div class="panel panel-default">
    <div class="panel-heading">
        <ul class="nav nav-pills">
            {%- set orders = [
                'inbox': '收件箱',
                'outbox': '发件箱'
            ] -%}
            {%- for order, label in orders -%}
                <li class="{%- if order == currentTab -%}active{%- endif -%}">
                    {{ link_to('/' ~ order, label) }}
                </li>
            {%- endfor -%}
        </ul>
    </div>

    <div class="panel-body">
        <div class="profile-content">

            {% set has_message = false %}

            {%- for message in messages -%}

                {% if message.deleted == 1 %}
                    {% continue %}
                {% endif %}

                {% set has_message = true %}

                <div class="activity-list">
                    <div class="message-list-body row">
                        <div class="col-md-1">
                            <span class="post-avatar">
                                {% if 'inbox' == currentTab %}
                                    {{ link_to('user/' ~ message.userOrigin.login, message.userOrigin.avatarNormal()) }}
                                {% else %}
                                    {{ link_to('user/' ~ message.user.login, message.user.avatarNormal()) }}
                                {% endif %}
                            </span>
                        </div>
                        <div class="col-md-11">
                            <div class="message-author">
                            {% if 'inbox' == currentTab %}
                                {{ link_to('user/' ~ message.userOrigin.login, message.userOrigin.name) }}
                            {% else %}
                                我发给 {{ link_to('user/' ~ message.userReceive.login, message.userReceive.name) }}
                            {% endif %}
                            </div>

                            {{ message.content|nl2br }}

                            <div class="posts-bar">
                                <span class="author-box">
                                    <span class="time hidden-xs">
                                        <time>{{ message.getHumanCreatedAt() }}</time>
                                    </span>
                                </span>
                                <span class="button-box">
                                    <a class="btn btn-default btn-xs btn-small btn-message-delete" data-id="{{ message.id }}" data-type="{% if 'inbox' == currentTab %}in{% else %}out{%endif%}">
                                        <span>删除</span>
                                    </a>
                                    {% if 'inbox' == currentTab %}
                                    &nbsp;<a href="#" class="btn btn-default btn-xs btn-small btn-send-message" data-id="{{ message.userOrigin.id }}" data-login="{{ message.userOrigin.login }}" data-name="{{ message.userOrigin.name }}"
                                        onclick="return false">
                                        <span>回复</span>
                                    </a>
                                    {% endif %}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div>

                    </div>
                </div>

                {% if 'inbox' == currentTab %}
    			    {% do message.markAsRead() %}
                {% endif %}

            {%- endfor -%}

            {% if !has_message %}
                <br>
                <div class="alert alert-info" align="center">无私信</div>
            {% endif %}
        </div>
    </div>
</div>

{%- include 'partials/popup/error-modal.volt' -%}
{%- include 'partials/popup/send-message' with ['post': post, 'tokenKey': tokenKey, 'token': token] -%}
