{{- content() -}}

{% include 'partials/flash-banner.volt' %}

{%-
    set currentUser  = session.get('identity'),
        moderator    = session.get('identity-moderator'),
        tokenKey     = security.getPrefixedTokenKey('post-' ~ post.id),
        token        = security.getPrefixedToken('post-' ~ post.id),
        postAuthorUrl = 'user/' ~ post.user.login
-%}

<div class="discussion row">
    <div id="mainbox" class="col-md-8">
        <div class="panel panel-default">
            <div class="panel-heading post-title">
                <div class="row">
                    <div class="col-md-11">
                        {% if post.nodes_id == 3 %}
                            <h1><a href="{{ post.link }}" title="{{ post.title|e }}">{{ post.title|e }}</a><sup>&nbsp;<span class="glyphicon glyphicon-link"></span></sup>
                                </h1>
                        {% else %}
                            <h1>{{- post.title|e -}}</h1>
                        {% endif %}

                    </div>
                    <div class="col-md-1">
                        <div class="author-avatar">
                            {{ link_to(postAuthorUrl, post.user.avatarNormal('avatar-small')) }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel-body">
                <div id="post-content-box" class="post-content markdown-body">
                    {%- cache "post-body-" ~ post.id -%}
                        <div>
                            {{- markdown.render(content) -}}
                        </div>
                    {%- endcache -%}
                </div>
            </div>
            <div class="panel-footer" id="vote-block">
                {%-
                    include 'partials/post/post-buttons' with [
                        'post': post,
                        'currentUser': currentUser,
                        'moderator': moderator,
                        'tokenKey': tokenKey,
                        'token': token
                    ]
                -%}
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading" id="reply-block">
                <div class="post-status-bar">
                    <span class="replyCount">{{- post.number_replies -}}个回复</span>
                    <span class="separator">-</span>
                    <span class="viewCount">{{- post.number_views -}}人浏览</span>
                </div>
            </div>
            <div class="panel-body">
                {%- if post.replies|length -%}
                    {%- for reply in replies -%}
                    <div class="row replyBlock">
                        <div class="col-md-1">
                            <span class="post-avatar">
                                {{- link_to('user/' ~ reply.user.login, reply.user.avatarNormal()) -}}
                            </span>
                        </div>
                        <div class="col-md-11">
                              {{-
                                  partial('partials/post/reply', [
                                      'post': post,
                                      'reply': reply,
                                      'markdown': this.markdown,
                                      'moderator': moderator,
                                      'currentUser': currentUser
                                  ])
                              -}}
                        </div>
                     </div>
                    {%- endfor -%}
                 {%- else -%}
                    <div style="text-align:center;font-size:1.2rem;color:#777;">暂无回复(第一个回复者可获得额外微币奖励)</div>
                 {%- endif -%}
            </div>
        </div>
        {%- if post.locked != 'Y' -%}
            {{-
                partial('partials/post/comment-form', [
                  'post': post,
                  'currentUser': currentUser,
                  'tokenKey': tokenKey,
                  'token': token
                ])
            -}}
        {%- endif -%}
    </div>
    <div class="col-md-3">
        {% include 'partials/sidebar/sidebar-topic-hot.volt' %}
        {% include 'partials/sidebar/sidebar-stat.volt' %}
    </div>
</div>

{%- if currentUser -%}
    {%- include 'partials/popup/error-modal.volt' -%}
    {%- include 'partials/popup/reply-popup' with ['post': post, 'tokenKey': tokenKey, 'token': token] -%}
    {%- include 'partials/popup/stick-popup' with ['post': post, 'tokenKey': tokenKey, 'token': token] -%}
{%- endif -%}
