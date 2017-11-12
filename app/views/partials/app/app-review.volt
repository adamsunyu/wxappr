
{%- set reviewAuthorUrl = 'user/' ~ review.user.login -%}

<div class="reply-item" id="C{{review.id}}">
    <div class="post-content">

        {% if review.in_reply_to_user != null %}
         <div class="reply-to-user">
             回复{{ link_to('user/' ~ review.replyToUser.login, '<span itemprop="name">' ~ review.replyToUser.name|e ~ '</span>') }}
         </div>
        {% endif %}

        <div itemprop="text">
            {{- markdown.render(review.content|e) -}}
        </div>
    </div>
    <div class="posts-bar">

        <!--span class="button-box">

            {%- if review.users_id == currentUser or moderator == 'Y' -%}
                <a class="btn btn-default btn-xs btn-review-edit" data-id="{{ review.id }}">
                    <span class="glyphicon glyphicon-pencil"></span>
                </a>
                <a class="btn btn-default btn-xs btn-review-remove" data-id="{{ review.id }}">
                    <span class="glyphicon glyphicon-remove"></span>
                </a>
            {%- endif -%}

            {%- if currentUser and review.users_id != currentUser -%}
                <a class="btn btn-default btn-xs btn-review-review" data-id="{{ review.id }}">
                    <span class="glyphicon glyphicon-share-alt"></span>
                </a>
            {%- endif -%}

            <a href="#" onclick="return false" class="btn btn-default {% if currentUser %}vote-review-up{% else %}need-login{% endif %}" data-id="{{ review.id }}">
                <span class="glyphicon glyphicon-thumbs-up" title="赞"></span>
                {% if review.votes_up %}
                    {{ review.votes_up }}
                {% endif %}
            </a>
        </span-->

        <span class="author-box">
            <span class="name">
                {{ link_to(reviewAuthorUrl, '<span itemprop="name">' ~ review.user.name|e ~ '</span>', 'class': 'user-moderator-' ~ review.user.moderator) }}
            </span>

            <span class="time hidden-xs">
            {%- if review.edited_at > 0 -%}
                <time itemprop="dateCreated" datetime="{{ date('c', review.edited_at) }}" class="action-date">
                    {{ review.getHumanEditedAt() }}编辑
                </time>
            {%- else -%}
                <time itemprop="dateCreated" datetime="{{ date('c', review.created_at) }}" class="action-date">
                   {{ review.getHumanCreatedAt() }}回复
                </time>
            {%- endif -%}
            </span>
        </span>
    </div>
</div>
