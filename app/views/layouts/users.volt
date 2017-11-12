{% include 'partials/top-menu.volt' %}

<div class="page-wrapper">
    <div class="container profile">
        {% include 'partials/flash-banner.volt' %}
        <div class="row">

            <div id="mainbox" class="col-md-8">
                {{ content() }}
            </div>
            <div class="col-md-3">

                {% include 'partials/sidebar/sidebar-user.volt' %}

                {% if showSocial %}
                    {% include 'partials/sidebar/sidebar-user-social.volt' %}
                {% endif %}

                {% include 'partials/sidebar/sidebar-user-achievements.volt' %}

                {% include 'partials/sidebar/sidebar-user-activity.volt' %}
            </div>
        </div>
    </div>
    {% include 'partials/footer.volt' %}
</div>
