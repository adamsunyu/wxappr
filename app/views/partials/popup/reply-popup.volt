<div class="modal fade" id="replyModal" tabindex="-1" role="dialog" aria-labelledby="replyModalLabel" aria-hidden="true" data-backdrop="true" data-keyboard="false">
    <div class="modal-dialog">
        <form method="post" autocomplete="off" role="form">
            {{ hidden_field(tokenKey, "value": token) }}
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h5 class="modal-title" id="replyModalLabel">回复</h5>
                </div>

                <div class="modal-body" id="errorBody">
                    <div>
                        <div id="reply-comment-box">
                            {{- hidden_field('id', 'value': post.id) -}}
                            {{- hidden_field('reply-id') -}}
                            {{- text_area("commentArea", "rows": 10, "class": "form-control", "id": "comment-textarea") -}}
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-middle" data-dismiss="modal">取消</button>
                    <input type="submit" class="btn btn-success btn-middle" value="回复"/>
                </div>
            </div>
        </form>
    </div>
</div>
