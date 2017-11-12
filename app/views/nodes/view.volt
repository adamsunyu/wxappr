{{- content() -}}

{% include 'partials/flash-banner.volt' %}

<div class="discussions row">
	<div id="mainbox" class="col-md-8">
		<div class="panel panel-default">
			{% include 'partials/list-posts-node.volt' %}
		</div>
	</div>
	<div class="col-md-3">
        {% include 'partials/sidebar/sidebar-node-info.volt' %}
		{% include 'partials/sidebar/sidebar-node-about.volt' %}
		{% include 'partials/sidebar/sidebar-node-followers.volt' %}
	</div>
</div>
