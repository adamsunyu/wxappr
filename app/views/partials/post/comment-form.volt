{%- if currentUser -%}
<div class="panel panel-default">
    <div class="panel-body">
        <div class="comment-section">
            <form method="post" autocomplete="off" role="form">
                {{ hidden_field(tokenKey, "value": token, "id": "csrf-token") }}
                <div id="comment-box">
                    {{- hidden_field('id', 'value': post.id) -}}
                    {{- text_area("commentArea", "rows": 5, "class": "form-control") -}}
                </div>
                <div class="btn-comment-box clearfix">
                    <span class="push-left label-color small">
                    </span>
                    <button type="submit" class="btn btn-success btn-middle pull-right"><span class="glyphicon glyphicon-send" style="font-size:80%"></span>&nbsp;回复</button>
                </div>
            </form>
        </div>
    </div>
</div>
{%- endif -%}
