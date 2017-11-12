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
                    <br>
                    <form id="create-node" method="post" autocomplete="off" role="form">

                        {{ hidden_field("id") }}

                        <div class="app-create-box" style="margin: 10px auto;width:90%;">

                            <div class="app-publish-section clearfix">
                                <div class="row">
                                    <div class="col-md-2 col-xs-12">
                                        <div class="app-icon-upload-box" style="float:left;" id="app-icon-upload-box">
                                            {% if theApp.icon_version > 0 %}
                                                <div class="iconfont hide-element"></div>
                                                <img src="{{ theApp.iconURI() }}" alt="小程序图标" id="app-icon-upload-icon-image" class="app-icon-upload-icon">
                                            {% endif %}
                                        </div>
                                    </div>
                                    <div class="col-md-10 col-xs-12">
                                        <div style="margin-top:5px;margin-bottom:10px;"><h2>{{ theApp.name }}</h2></div>
                                        <div>
                                            <input data-id="1" class="btn btn-small btn-default btn-apptag-option" type="button" value="{{ theApp.tag.name }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div><label>二维码</label></div>
                            <div class="app-publish-section">
                                <div class="app-qrcode-upload-box" id="app-qrcode-upload-box">
                                    {% if theApp.qrcode_version > 0 %}
                                        <div class="iconfont hide-element"></div>
                                        <img src="{{ theApp.qrcodeURI() }}" alt="小程序二维码" id="app-qrcode-upload-icon-image" class="app-qrcode-upload-icon">
                                    {% endif %}
                                </div>
                            </div>
                            <div><label>简介</label></div>
                            <div style="margin-bottom:20px;">
                                <p>
                                    {{ theApp.desc }}
                                </p>
                            </div>

                            <div><label>截图</label></div>
                            <div class="app-screenshots-box">
                                <ul class="app-screenshots-list">
                                    {% if theApp.screen1_version > 0 %}
                                        <li class="app-screenshots-button" id="app-screenshots-button-1">
                                            <div class="iconfont hide-element"></div>
                                            <img src="{{ theApp.screenshotURI(1) }}" alt="截图1" id="app-screenshots-image-1" class="app-screenshots-image">
                                        </li>
                                    {% endif %}
                                    {% if theApp.screen2_version > 0 %}
                                        <li class="app-screenshots-button" id="app-screenshots-button-2">
                                            <div class="iconfont hide-element"></div>
                                            <img src="{{ theApp.screenshotURI(2) }}" alt="截图2" id="app-screenshots-image-2" class="app-screenshots-image">
                                        </li>
                                    {% endif %}
                                    {% if theApp.screen3_version > 0 %}
                                        <li class="app-screenshots-button" id="app-screenshots-button-3">
                                            <div class="iconfont hide-element"></div>
                                            <img src="{{ theApp.screenshotURI(3) }}" alt="截图3" id="app-screenshots-image-3" class="app-screenshots-image">
                                        </li>
                                    {% endif %}
                                    {% if theApp.screen4_version > 0 %}
                                        <li class="app-screenshots-button" id="app-screenshots-button-4">
                                            <div class="iconfont hide-element"></div>
                                            <img src="{{ theApp.screenshotURI(4) }}" alt="截图3" id="app-screenshots-image-4" class="app-screenshots-image">
                                        </li>
                                    {% endif %}
                                </ul>
                            </div>

                            <div class="post-button text-center" style="margin-top:30px;">
                                <button type="submit" class="btn btn-sm btn-success">发布小程序</button>
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
