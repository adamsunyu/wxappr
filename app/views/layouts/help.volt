{% include 'partials/top-menu.volt' %}

<div class="page-wrapper">
    <div class="container help-page">
        <div class="row">
            <div id="mainbox" class="col-md-8">
                {{ content() }}
            </div>
            <div class="col-md-3">
                {% include 'partials/sidebar/sidebar-help-list.volt' %}
            </div>
        </div>
    </div>
    {% include 'partials/footer.volt' %}
</div>
