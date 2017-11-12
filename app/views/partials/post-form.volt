{%- if currentUser -%}
<div class="panel panel-default">
    <div class="panel-body">
        <div>
            <a href="/talk">
                <span> 话题</span>
            </a>
            <a href="/ask">
                <span> 提问</span>
            </a>
        </div>
        <div class="comment-section">
            <form method="post" autocomplete="off" role="form">
                {{ hidden_field(tokenKey, "value": token, "id": "csrf-token") }}
                <div id="comment-box">
                    {{- hidden_field('id', 'value': post.id) -}}
                    {{- text_area("content", "rows": 5, "class": "form-control") -}}
                </div>
                <div class="btn-comment-box clearfix">
                    <span class="push-left label-color small">
                    </span>
                    <button type="submit" class="btn btn-success pull-right">回复</button>
                </div>
            </form>
        </div>
    </div>
</div>
{%- endif -%}
