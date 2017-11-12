<div class="modal fade" id="sendMessageModal" tabindex="-1" role="dialog" aria-labelledby="replyModalLabel" aria-hidden="true" data-backdrop="true" data-keyboard="false">
    <div class="modal-dialog">
        <form method="post" autocomplete="off" role="form">

            {{ hidden_field(tokenKey, "value": token, "id": "csrf-token") }}

            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h5 class="modal-title" id="userModalLabel">发私信给</h5>
                </div>

                <div class="modal-body">
                    <div>{{- hidden_field('toUserId') -}}</div>
                    <div>
                        {{- text_area("messageArea", "rows": 5, "class": "form-control") -}}
                    </div>
                </div>
                <div class="modal-footer center-block" style="margin-bottom: 20px;">
                    <button type="button" class="btn btn-default btn-middle" data-dismiss="modal">取消</button>
                    &nbsp;
                    <input id="btn-submit-message" type="button" class="btn btn-success btn-middle" disabled value="发送"/>
                </div>
            </div>
        </form>
    </div>
</div>
