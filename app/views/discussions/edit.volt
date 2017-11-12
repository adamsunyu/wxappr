{{ content() }}

{% include 'partials/image-upload-button.volt' %}

<div>
    <div class="row">
        <div class="col-md-7 col-md-offset-1">
            <div class="panel panel-default">
                <form method="post" autocomplete="off" role="form">

                <div class="panel-body">
                    {{
                        hidden_field(
                            security.getPrefixedTokenKey('edit-post-' ~ post.id),
                            "value": security.getPrefixedToken('edit-post-' ~ post.id)
                        )
                    }}
                    {{ hidden_field("id") }}

                    <div class="clearfix">
                        <div class="row">
                            <div class="form-group col-md-10 col-xs-12">
                                {{ text_field("title", "placeholder": "标题", "class": "form-control", "required": "required") }}
                            </div>
                            <div class="form-group col-md-2 col-xs-12" >
                                {{ select("nodeSelector", nodeList, 'useEmpty': false, 'emptyText': '', 'class': 'form-control', "required": "required") }}
                            </div>
                        </div>
                    </div>

                    <div class="textarea-box">
                        {{ text_area("contentArea", "rows": 15, "placeholder": "", "class": "form-control") }}
                    </div>

                    <div class="create-image-list"><ul id="upload-list" class="qq-upload-list"></ul></div>

                    <div class="btn-box-edit clearfix">
                        <div class="post-button">
                            <button type="submit" class="btn btn-sm btn-success pull-right">保存</button>
                        </div>
                        <div class="post-button">
                            <div id="uploader"></div>
                        </div>
                        <div class="post-button">
                            {{ link_to('topic/' ~ post.id , '<button type="button" class="btn btn-sm btn-default">取消</button>') }}
                        </div>
                    </div>
                </div>
                </form>
            </div>
        </div>
        <div class="col-md-3">
            {% include 'partials/sidebar/sidebar-help-markdown.volt' %}
        </div>
    </div>
</div>

{%- include 'partials/popup/error-modal.volt' -%}
