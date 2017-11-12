{{ content() }}

{% include 'partials/image-upload-button.volt' %}

<div>
    <div class="row">
        <div id="mainbox" class="col-md-8">
            <form method="post" autocomplete="off" role="form">
                <div class="panel panel-default">
                    <div class="panel-heading post-title">
                        {{ actionName }}
                    </div>
                    <div class="panel-body">
                        {{
                            hidden_field(
                                security.getPrefixedTokenKey('create-note'),
                                "value": security.getPrefixedToken('create-note')
                            )
                        }}

                        {{ hidden_field('nodeId', "value": nodeId) }}

                        <div class="form-group clearfix">
                            {% if nodeId == 1 %}
                                {% set placeholder_title  = '话题标题', placeholder_content = '话题内容'  %}
                            {% elseif nodeId == 2 %}
                                {% set placeholder_title  = '问题标题', placeholder_content = '描述问题'  %}
                            {% endif %}
                            {{ text_field("title", "placeholder": placeholder_title, "class": "form-control", "required": "required") }}
                        </div>
                        <div class="form-group">
                            {{ text_area("contentArea", "rows": 15, "placeholder": placeholder_content, "class": "form-control") }}
                        </div>
                        <div class="create-image-list"><ul id="upload-list" class="qq-upload-list"></ul></div>
                        <div class="btn-box-edit clearfix">
                            <div class="post-tip">
                            </div>
                            <div class="post-button">
                                <button type="submit" class="btn btn-sm btn-success pull-right">发布</button>
                            </div>
                            <div class="post-button">
                                <div id="uploader"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-md-3">
            {% include 'partials/sidebar/sidebar-help-markdown.volt' %}
        </div>
    </div>
</div>

{%- include 'partials/popup/error-modal.volt' -%}
