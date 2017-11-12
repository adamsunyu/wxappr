{%- set replyAuthorUrl = 'user/' ~ reply.user.login -%}
<div class="reply-item" id="C{{reply.id}}">
    <div class="post-content">

        {% if reply.in_reply_to_user != null %}
         <div class="reply-to-user">
             回复{{ link_to('user/' ~ reply.replyToUser.login, '<span itemprop="name">' ~ reply.replyToUser.name|e ~ '</span>') }}
         </div>
        {% endif %}

        <div itemprop="text">
            {{- markdown.render(reply.content|e) -}}
        </div>
    </div>
    <div class="posts-bar">

        <span class="button-box">

            {%- if reply.users_id == currentUser or moderator == 'Y' -%}
                <a class="btn btn-default btn-xs btn-reply-edit" data-id="{{ reply.id }}">
                    <span class="glyphicon glyphicon-pencil"></span>
                </a>
            {%- endif -%}

            {%- if currentUser and reply.users_id != currentUser -%}
                <a class="btn btn-default btn-xs btn-reply-reply" data-id="{{ reply.id }}">
                    <span class="glyphicon glyphicon-share-alt"></span>
                </a>
            {%- endif -%}

            {%- if moderator == 'Y' -%}
                <a class="btn btn-default btn-xs btn-reply-remove" data-id="{{ reply.id }}">
                    <span class="glyphicon glyphicon-remove"></span>
                </a>
            {% endif %}

            <a href="#" onclick="return false" class="btn btn-default {% if currentUser %}vote-reply-up{% else %}need-login{% endif %}" data-id="{{ reply.id }}">
                <span class="glyphicon glyphicon-thumbs-up" title="赞"></span>
                {% if reply.votes_up %}
                    {{ reply.votes_up }}
                {% endif %}
            </a>
        </span>

        <span class="author-box">
            <span class="name">
                {{ link_to(replyAuthorUrl, '<span itemprop="name">' ~ reply.user.name|e ~ '</span>', 'class': 'user-moderator-' ~ reply.user.moderator) }}
            </span>

            <span class="time hidden-xs">
            {%- if reply.edited_at > 0 -%}
                <time itemprop="dateCreated" datetime="{{ date('c', reply.edited_at) }}" class="action-date">
                    {{ reply.getHumanEditedAt() }}编辑
                </time>
            {%- else -%}
                <time itemprop="dateCreated" datetime="{{ date('c', reply.created_at) }}" class="action-date">
                   {{ reply.getHumanCreatedAt() }}回复
                </time>
            {%- endif -%}
            </span>
        </span>
    </div>
</div>
