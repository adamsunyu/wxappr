<div class="modal fade" id="stickModal" tabindex="-1" role="dialog" aria-labelledby="replyModalLabel" aria-hidden="true" data-backdrop="true" data-keyboard="false">
    <div class="modal-dialog">
        <form method="post" autocomplete="off" role="form">
            {{ hidden_field(tokenKey, "value": token) }}
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h5 class="modal-title" id="thankModalLabel">自助置顶</h5>
                </div>
                {% if user.money >= 72 %}
                <div class="modal-body">
                    <br>
                    <div class="text-center"><label id="stickModelTip">花费: 48微币</label></div>
                    <div>
                        {{- hidden_field('stickAmount', 'value': 48) -}}
                        {{- hidden_field('itemId', 'value': post.id) -}}
                    </div>
                    <div style="margin: 20px 0px 20px;text-align:center;">
                        <input id="stick-option-1" data-id="1" class="btn btn-default btn-middle btn-stick-option" type="button" value="12小时">
                        <input id="stick-option-2" data-id="2"  class="btn btn-warning btn-middle btn-stick-option" type="button" value="24小时">
                        <input id="stick-option-3" data-id="3" class="btn btn-default btn-middle btn-stick-option" type="button" value="36小时">
                    </div>

                </div>
                <div class="modal-footer center-block" style="margin-bottom: 20px;">
                    <button type="button" class="btn btn-default btn-middle" data-dismiss="modal">取消</button>
                    &nbsp;
                    <input id="btn-submit-stick" type="button" class="btn btn-success btn-middle" value="确定置顶"/>
                </div>
                {% else %}
                <div class="modal-body">
                    <div id="thankLimitError" class="alert alert-danger">你的资产过少，不能使用自助置顶</div>
                </div>
                {% endif %}
            </div>
        </form>
    </div>
</div>
