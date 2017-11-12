{{ content() }}

{%-
    set currentUser  = session.get('identity')
-%}

{% include 'partials/flash-banner.volt' %}

<div class="discussions row">
	<div id="mainbox" class="col-md-8">
		<div class="panel panel-default">
			{% include 'partials/list-nodes.volt' %}
		</div>
	</div>
	<div class="col-md-3">
		{% include 'partials/sidebar/sidebar-user-nodes.volt' %}

		{% if !currentUser %}
			{% include 'partials/sidebar/sidebar-node-hot.volt' %}
            {% include 'partials/sidebar/sidebar-stat.volt' %}
		{% endif %}
	</div>
</div>
