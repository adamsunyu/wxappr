{%-
    set currentUser  = session.get('identity')
-%}

{% if node is defined %}
<div class="module">
    <div class="module-body">
        <div class="profile-avatar clearfix">
            {{ link_to('topics/' ~ node.slug, node.iconNormal('avatar-big')) }}
        </div>
        <div class="profile-title">
            <h1>
                <span class="user-name" itemprop="name">
                    {{ link_to('topics/' ~ node.slug, node.name|e) }}
                </span>
            </h1>
        </div>
    </div>
</div>
{% endif %}
