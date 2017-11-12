{{ content() }}

{%-
    set currentUser  = session.get('identity')
-%}

{% include 'partials/flash-banner.volt' %}

<div class="discussions row">
	<div id="mainbox" class="col-md-8">
		<div class="panel panel-default">
			{% include 'partials/list-apps.volt' %}
		</div>
	</div>
	<div class="col-md-3">
        {% include 'partials/sidebar/sidebar-search.volt' %}
        {% include 'partials/sidebar/sidebar-apps-hot.volt' %}
        {% include 'partials/sidebar/sidebar-apps-new.volt' %}
	</div>
</div>
