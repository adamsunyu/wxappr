{{ content() }}

{%-
    set currentUser  = session.get('identity')
-%}

{% include 'partials/flash-banner.volt' %}

<div class="discussions row">

	<div id="mainbox" class="col-md-8">
		{% include 'partials/list-posts.volt' %}
	</div>

    <div class="col-md-3">
        {% include 'partials/sidebar/sidebar-user-active.volt' %}
        {% include 'partials/sidebar/sidebar-stat.volt' %}
    </div>

</div>
