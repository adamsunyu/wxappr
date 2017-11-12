<div class="modal fade" id="thankModal" tabindex="-1" role="dialog" aria-labelledby="replyModalLabel" aria-hidden="true" data-backdrop="true" data-keyboard="false">
    <div class="modal-dialog">
        <form method="post" autocomplete="off" role="form">
            {{ hidden_field(tokenKey, "value": token) }}
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h5 class="modal-title" id="thankModalLabel">感谢</h5>
                </div>
                {% if user.money >= 20 %}
                <div class="modal-body">
                    <div>
                        {# hidden_field('thankLimit', 'value': user.money) #}

                        {{- hidden_field('thankUserId') -}}
                        {{- hidden_field('thankType') -}}
                        {{- hidden_field('thankAmount', 'value': 10) -}}
                        {{- hidden_field('itemMainId', 'value': post.id) -}}
                        {{- hidden_field('itemSubId') -}}
                    </div>

                    <!-- <div id="thankLimitError" class="alert alert-danger" style="display:none;"></div> -->
                    <!-- <div class="input-group center-block thank-amount-control">

                        <div class="input-group-addon">
                            <span class="glyphicon glyphicon-bitcoin"></span>
                        </div>
                        <input type="number" class="form-control" id="thankAmount" placeholder="" value="10">
                        <div id="btn-amount-plus" class="input-group-addon">
                            <span class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span>
                        </div>
                    </div> -->

                    <div style="margin: 20px 0px 20px;text-align:center;">
                        <input id="thank-option-1" data-id="1" class="btn btn-default btn-thank-option" type="button" value="5微币">
                        <input id="thank-option-2" data-id="2"  class="btn btn-warning btn-thank-option" type="button" value="10微币">
                        <input id="thank-option-3" data-id="3" class="btn btn-default btn-thank-option" type="button" value="20微币">
                    </div>

                </div>
                <div class="modal-footer center-block" style="margin-bottom: 20px;">
                    <button type="button" class="btn btn-default btn-middle" data-dismiss="modal">取消</button>
                    &nbsp;
                    <input id="btn-submit-thank" type="button" class="btn btn-success btn-middle" value="送出"/>
                </div>
                {% else %}
                <div class="modal-body">
                    <div id="thankLimitError" class="alert alert-danger">你的资产过少</div>
                </div>
                {% endif %}
            </div>
        </form>
    </div>
</div>
