{{ content() }}

<div class="row">
    <div class="col-md-12">
        <div class="error-v4">
            <div class="col-md-12 text-center error-banner">
                <h1>{{ code }}</h1>
                <span class="sorry">{{ message }}</span>
            </div>
            {% if debug %}
                <div class="col-md-12 error-debug">
                    <p>
                        Error [{{ error.type() }}]: {{ error.message() }} <br>
                        File: <code>{{ error.file() }}</code><br>
                        Line: <code>{{ error.line() }}</code>
                    </p>
                    {% if error.isException() %}
                        <pre>{{ error.exception().getTraceAsString() }}</pre>
                    {% endif %}
                </div>
            {% endif %}
            <div class="col-md-12 text-center">
                <p class="lead">
                    页面不存在，可能已经被删除...
                    <a href="{{ 'mailto:' ~ config.mail.fromEmail }}">{{ config.mail.fromEmail }}</a>
                </p>
                <p>
                    <a class="btn btn-primary" href="/" style="color: #fff;">回到首页</a>
                </p>
            </div>
        </div>
    </div>
</div>
