{%-
    set currentUser  = session.get('identity'),
        moderator    = session.get('identity-moderator')
-%}
<div class="module sidebar-margin-top">
    <div class="module-head">关于{{ node.name }}
        {% if currentUser == node.creator_id or moderator == 'Y' %}
        <span class="btn-node-edit">
            {{ link_to('node-edit/' ~ node.id, '<span title="编辑节点" class="glyphicon glyphicon-edit"></span>') }}
        </span>
        {% endif %}
    </div>
    <div class="module-body">
        {{ node.about }}
    </div>
</div>
