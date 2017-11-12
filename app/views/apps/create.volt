{{ content() }}

{% include 'partials/flash-banner.volt' %}

<div>
    <div class="row">
        <div id="mainbox" class="col-md-8">
            <div class="panel panel-default">
            	<div align="left" class="panel-heading">
                    {{ title }}
            	</div>
            	<div align="left" class="panel-body">
                    <form id="create-node" method="post" autocomplete="off" role="form">
                        <div class="app-create-box" style="margin: 10px auto;width:90%;">
                            <div>{{ form.label('name') }}</div>
                            <div style="margin-bottom:20px;">{{ form.render('name') }}</div>

                            <div><label>小程序标签</label><span class="app-create-tip">(至少添加1个标签, 最多不能超过3个)</span>
                            </div>

                            <div class="app-tag-list">
                                <div style="margin-bottom:10px;">{{ form.render('appTags') }}</div>
                                <span style="font-size:1.2rem;">常用标签</span><br>
                                {%- for theTag in tagList -%}
                                    <input data-id="{{ theTag.id }}" class="btn btn-default btn-middle btn-apptag-option" type="button" value="{{ theTag.name }}">
                                {%- endfor -%}
                            </div>

                            <div><label>小程序简介</label><span class="app-create-tip"></span></div>
                            <div style="margin-bottom:20px;">
                                {{ text_area("descArea", "rows": 10, "placeholder": "请输入小程序的简介", "class": "form-control") }}
                            </div>

                            <div class="post-button text-center" style="margin-top:30px;">
                                <button type="submit" class="btn btn-sm btn-success">创建小程序</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            {% include 'partials/sidebar/sidebar-help-createapp.volt' %}
            {% include 'partials/sidebar/sidebar-user-apps.volt' %}
        </div>
    </div>
</div>



{%- include 'partials/popup/error-modal.volt' -%}
