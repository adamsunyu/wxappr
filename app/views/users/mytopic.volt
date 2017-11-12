{{- content() -}}

{% include 'partials/flash-banner.volt' %}

{%-
    set currentUser  = session.get('identity'),
        moderator    = session.get('identity-moderator'),
        postAuthorUrl = 'user/' ~ post.user.login
-%}

<div class="panel panel-default">
    <div class="panel-heading post-title">
        <div class="row">
            <div class="col-md-11">
                <h1>[已删除]{{- post.title|e -}}</h1>
            </div>
            <div class="col-md-1">
                <div class="author-avatar">
                    {{ link_to(postAuthorUrl, post.user.avatarNormal()) }}
                </div>
            </div>
        </div>
    </div>
    <div class="panel-body">
        <div id="post-content-box" class="post-content markdown-body">
            <div>
                {{- markdown.render(post.content) -}}
            </div>
        </div>
    </div>
</div>
