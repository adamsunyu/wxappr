{{- content() -}}

{% include 'partials/flash-banner.volt' %}

{%-
    set currentUser  = session.get('identity'),
        moderator    = session.get('identity-moderator')
-%}

<div class="discussions row">
	<div id="mainbox" class="col-md-8">
		<div class="panel panel-default">
			<div class="panel-heading">
				<div class="row">
			        <div class="col-md-6">
			            <ul class="nav nav-pills">

					        {%- set orders = [
					            'new': '最新',
					            'hot': '热门'
					        ] -%}

					        {%- for order, label in orders -%}
					            <li>
					                {{ link_to('node/' ~ node.slug ~ '/' ~ order, label) }}
					            </li>
					        {%- endfor -%}
			            </ul>
			        </div>
			        <div class="col-md-6 text-right">
			            {% if currentUser == node.creator_id or moderator == 'Y' %}
			                {{ link_to('node-wiki/' ~ node.id , '<button type="button" class="btn btn-small btn-success">编辑主页</button>') }}
			            {% endif %}
			        </div>
			    </div>
			</div>
			<div class="panel-body">
                {% if node.wiki != null %}
                    <div class="post-content markdown-body">
                        <div>
                            {{- markdown.render(node.wiki|e) -}}
                        </div>
                    </div>
                {% else %}
                    <div align="center">
                        <br>
                        <div class="alert alert-info">暂无问题</div>
                    </div>
                {% endif %}
            </div>
		</div>
	</div>
	<div class="col-md-3">
        {% include 'partials/sidebar/sidebar-node-info.volt' %}
		{% include 'partials/sidebar/sidebar-node-about.volt' %}
		{% include 'partials/sidebar/sidebar-node-followers.volt' %}
	</div>
</div>
