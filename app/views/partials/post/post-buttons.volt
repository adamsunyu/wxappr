{%-
    set postAuthorUrl = 'user/' ~ post.user.login,
        postAuthorName= '<span itemprop="name">' ~ post.user.name|e ~ '</span>'
-%}
<div class="posts-bar clearfix">

    <span class="author-box">
        <span class="name">
            {{ link_to(postAuthorUrl, postAuthorName, 'class': 'author-name' ) }}
        </span>
        <span class="separator">-</span>
        <span class="time hidden-xs">
        {%- if post.edited_at > 0 -%}
            <time itemprop="dateEdited" datetime="{{ date('c', post.edited_at) }}">
                {{ post.getHumanEditedAt() ~ '编辑'}}
            </time>
        {%- else -%}
            <time itemprop="dateCreated" datetime="{{ date('c', post.created_at) }}">
                {{ post.getHumanCreatedAt() ~ '发布' }}
            </time>
        {%- endif -%}
        </span>
        {% if post.sticked == 'Y' %}
        <span class="separator">-</span>
        <span class="time hidden-xs">
            已置顶(由{{ link_to('user/' ~ post.stickOwner.login, post.stickOwner.name) }}发起, 剩余{{ post.getRemainStickTime()}})
        </span>
        {% endif %}
    </span>

    <span class="button-box">

        {%- if post.users_id == currentUser or moderator == 'Y' -%}

            {% if post.nodes_id == 3 %}
                {% set editURI = 'edit/link/' %}
            {% else %}
                {% set editURI = 'edit/post/' %}
            {% endif %}
            {{ link_to(editURI ~ post.id, '<span class="glyphicon glyphicon-pencil"></span>', 'class': 'btn btn-default btn-xs btn-edit-post') }}
            <a class="btn btn-default btn-xs btn-delete-post" data-id="{{ post.id }}">
                <span class="glyphicon glyphicon-remove"></span>
            </a>
        {%- endif %}

        {%- if currentUser AND post.sticked == 'N' -%}
            <a href="#" class="btn btn-default btn-xs btn-stick-post"
                onclick="return false" data-id="{{ post.id }}">
              <span class="glyphicon glyphicon-pushpin"></span>&nbsp;自助置顶
            </a>
        {%- endif -%}

        {%- if moderator == 'Y' %}
            {{ link_to('unstick/post/' ~ post.id ~ '?' ~ tokenKey ~ '=' ~ token, '<span class="glyphicon glyphicon-pushpin"></span>&nbsp;取消置顶', 'class': 'btn btn-default btn-xs btn-stick-post') }}
        {%- endif %}

        <a href="#" class="btn btn-default btn-xs btn-vote-post" onclick="return false" data-type="1" data-id="{{ post.id }}">
          <span class="glyphicon glyphicon-thumbs-up" {% if votedType == 1 %}style="color:red;"{% endif %} ></span>
          {%- if post.votes_up -%}
            &nbsp;<span itemprop="upvoteCount">{{ post.votes_up }}赞</span>
          {%- else -%}
            &nbsp;赞
          {%- endif -%}
        </a>
    </span>
</div>
