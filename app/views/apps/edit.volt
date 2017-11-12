{{ content() }}

{% include 'partials/flash-banner.volt' %}

<div>
    <div class="row">
        <div id="mainbox" class="col-md-8">
            <div class="panel panel-default">
            	<div align="left" class="panel-heading">
            		<span>修改小程序</span>
                    {% if theApp.status != 'P' %}
                    <span class="push-right" style="float:right;">(状态:未发布)</span>
                    {% else %}
                    <span class="push-right" style="float:right;">(状态:已发布)</span>
                    {% endif %}
            	</div>
            	<div align="left" class="panel-body">
                    <form method="post" autocomplete="off" role="form">

                        <div class="app-create-box" style="margin: 10px auto;width:90%;">
                            <div>{{ form.label('name') }}</div>
                            <div style="margin-bottom:20px;">{{ form.render('name') }}</div>

                            <div><label>小程序标签</label><span class="app-create-tip">(至少添加1个标签, 最多不能超过3个)</span></div>

                            {{ hidden_field('appId', "value": theApp.id) }}

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

                            <div><label>小程序图标</label><span class="app-create-tip">建议尺寸: 512×512px（必填）</span></div>
                            <div class="app-publish-section">
                                <div class="app-icon-upload-box" id="app-icon-upload-box">
                                    {% if theApp.icon_version > 0 %}
                                        <div class="iconfont hide-element"></div>
                                        <img src="{{ theApp.iconURI() }}" alt="上传小程序图标" id="app-icon-upload-icon-image" class="app-icon-upload-icon">
                                    {% else %}
                                        <div class="iconfont"></div>
                                        <img src="#" alt="上传小程序图标" id="app-icon-upload-icon-image" class="app-icon-upload-icon hide-element">
                                    {% endif %}
                                </div>
                            </div>

                            <div><label>小程序二维码</label><span class="app-create-tip">建议尺寸: 512×512px（必填）</span></div>
                            <div class="app-publish-section">
                                <div class="app-qrcode-upload-box" id="app-qrcode-upload-box">
                                    {% if theApp.qrcode_version > 0 %}
                                        <div class="iconfont hide-element"></div>
                                        <img src="{{ theApp.qrcodeURI() }}" alt="上传二维码" id="app-qrcode-upload-icon-image" class="app-qrcode-upload-icon">
                                    {% else %}
                                        <div class="iconfont"></div>
                                        <img src="#" alt="上传二维码" id="app-qrcode-upload-icon-image" class="app-qrcode-upload-icon hide-element">
                                    {% endif %}
                                </div>
                            </div>

                            <div><label>小程序截图</label><span class="app-create-tip">建议尺寸: 720x1280px（最少1张，最多3张）</span></div>
                            <div class="app-screenshots-box">
                                <ul class="app-screenshots-list">
                                    <li class="app-screenshots-button" id="app-screenshots-button-1">
                                        {% if theApp.screen1_version > 0 %}
                                            <div class="iconfont hide-element"></div>
                                            <img src="{{ theApp.screenshotURI(1) }}" alt="上传截图" id="app-screenshots-image-1" class="app-screenshots-image">
                                        {% else %}
                                            <div class="iconfont"></div>
                                            <img src="#" alt="上传截图" id="app-screenshots-image-1" class="app-screenshots-image hide-element">
                                        {% endif %}
                                    </li>
                                    <li class="app-screenshots-button" id="app-screenshots-button-2">
                                        {% if theApp.screen2_version > 0 %}
                                            <div class="iconfont hide-element"></div>
                                            <img src="{{ theApp.screenshotURI(2) }}" alt="上传截图" id="app-screenshots-image-2" class="app-screenshots-image">
                                        {% else %}
                                            <div class="iconfont"></div>
                                            <img src="#" alt="上传截图" id="app-screenshots-image-2" class="app-screenshots-image hide-element">
                                        {% endif %}
                                    </li>
                                    <li class="app-screenshots-button" id="app-screenshots-button-3">
                                        {% if theApp.screen3_version > 0 %}
                                            <div class="iconfont hide-element"></div>
                                            <img src="{{ theApp.screenshotURI(3) }}" alt="上传截图" id="app-screenshots-image-3" class="app-screenshots-image">
                                        {% else %}
                                            <div class="iconfont"></div>
                                            <img src="#" alt="上传截图" id="app-screenshots-image-3" class="app-screenshots-image hide-element">
                                        {% endif %}
                                    </li>
                                </ul>
                            </div>

                            <div class="post-button text-center" style="margin-top:30px;">
                                <!-- <button type="submit" class="btn btn-sm btn-default">撤销发布</button> -->
                                <button type="submit" class="btn btn-sm btn-success">保存并发布</button>
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
