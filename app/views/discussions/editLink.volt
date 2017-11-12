{{ content() }}

<div>
    <div class="row">
        <div id="mainbox" class="col-md-8">
            <form method="post" autocomplete="off" role="form">
                <div class="panel panel-default">
                    <div class="panel-heading post-title">
                        {{ title }}
                    </div>
                    <div class="panel-body">

                        {{ hidden_field("id") }}

                        <div class="form-group clearfix">
                            <div class="input-group">
                              {{ text_field("link", "placeholder": "链接地址", "class": "form-control", "required": "required") }}
                              <span class="input-group-btn">
                                <button id="btn-fetch-link-loading" style="display:none;" class="btn btn-default" type="button"><img style="height:18px;" src="/css/loading.svg"></button>
                                <button id="btn-fetch-title" class="btn btn-default" type="button" style="color:#777"><span style="font-size:1.2rem;">抓取标题</span></button>
                              </span>
                            </div>
                        </div>
                        <div class="form-group clearfix">
                            {{ text_field("title", "placeholder": "链接标题", "class": "form-control", "required": "required") }}
                        </div>
                        <div class="form-group">
                            {{ text_area("suggestArea", "rows": 15, "placeholder": "推荐评语或链接描述", "class": "form-control") }}
                        </div>
                        <div class="create-image-list"><ul id="upload-list" class="qq-upload-list"></ul></div>
                        <div class="btn-box-edit clearfix">
                            <div class="post-tip">
                            </div>
                            <div class="post-button">
                                <button type="submit" class="btn btn-sm btn-success pull-right">发布</button>
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
