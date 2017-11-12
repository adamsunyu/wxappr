<div class="module">
    <div class="module-body">
        <div class="profile-avatar clearfix">
            {{ user.avatarNormal('avatar-big') }}
        </div>
        <div class="profile-title">
            <h1><span class="user-name" itemprop="name">{{ user.name|e }}</span></h1>
        </div>
        <div class="profile-time">
            <span itemprop="additionalName">来自{{ user.city_name }}</span>
        </div>
        <div class="profile-time">
            <span itemprop="additionalName">于{{ date('Y-m-d', user.created_at) }}加入</span>
        </div>
        <div class="profile-follow">
        {% if myself %}
            {% if followedByMe %}
                <div class="profile-buttons clearfix">
                    <div class="left-box">
                        <a class="btn btn-success btn-user-unfollow" data-id="{{ user.id }}">
                            <span class="glyphicon glyphicon-star"></span>&nbsp;已关注
                        </a>
                    </div>
                    <div class="right-box">
                        <a class="btn btn-default btn-xs btn-send-message" data-id="{{ user.id }}" data-login="{{ user.login }}" data-name="{{ user.name }}">
                            <span class="glyphicon glyphicon-envelope"></span>&nbsp;发消息
                        </a>
                    </div>
                </div>
            {% elseif myself.id != user.id %}
                <div class="profile-buttons clearfix">
                    <div class="left-box">
                        <a class="btn btn-default btn-xs btn-user-follow" data-id="{{ user.id }}">
                            <span class="glyphicon glyphicon-star-empty"></span>&nbsp;关注他
                        </a>
                    </div>
                    <div class="right-box">
                        <a class="btn btn-default btn-xs btn-send-message" data-id="{{ user.id }}" data-login="{{ user.login }}" data-name="{{ user.name }}">
                            <span class="glyphicon glyphicon-envelope"></span>&nbsp;发消息
                        </a>
                    </div>
                </div>
            {% endif %}
        {% else %}
            <div class="profile-buttons clearfix">
                <div class="left-box">
                    <a class="btn btn-default btn-xs need-login" data-id="{{ user.id }}">
                        <span class="glyphicon glyphicon-star-empty"></span>&nbsp;关注他
                    </a>
                </div>
                <div class="right-box">
                    <a class="btn btn-default btn-xs need-login" data-id="{{ user.id }}">
                        <span class="glyphicon glyphicon-envelope"></span>&nbsp;发消息
                    </a>
                </div>
            </div>
        {% endif %}
        </div>
    </div>
</div>
