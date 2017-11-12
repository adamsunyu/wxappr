{% include 'partials/top-menu.volt' %}
<div class="page-wrapper" >
    <div class="container" >
        <div class="col-md-10 col-md-offset-1">
    		<div class="row" >
                {{ content() }}
            </div>
        </div>
    </div>
    {% include 'partials/footer.volt' %}
</div>
<script type="text/javascript" src="//cdn.bootcss.com/particles.js/2.0.0/particles.min.js"></script>
<script src="/css/app.js"></script>
