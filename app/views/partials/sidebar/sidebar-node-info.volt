{%-
    set currentUser  = session.get('identity')
-%}

{% if node is defined %}
<div class="module">
    <div class="module-body">
        <div class="profile-avatar clearfix">
            {% if node.id == '1024' %}
                {{ link_to('/topics', node.iconNormal('avatar-big')) }}
            {% else %}
                {{ link_to('node/' ~ node.slug, node.iconNormal('avatar-big')) }}
            {% endif %}
        </div>
        <div class="profile-title">
            <h1>
                <span class="user-name" itemprop="name">
                    {% if node.id == '1024' %}
                        {{ link_to('/topics' ~ node.slug, node.name|e) }}
                    {% else %}
                        {{ link_to('node/' ~ node.slug, node.name|e) }}
                    {% endif %}
                </span>
            </h1>
        </div>
    </div>
</div>
{% endif %}
