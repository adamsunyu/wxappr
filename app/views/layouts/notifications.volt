{% include 'partials/top-menu.volt' %}

<div class="page-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-md-8 center-block">
                {{ content() }}
            </div>
        </div>
    </div>

    {% include 'partials/footer.volt' %}
</div>
