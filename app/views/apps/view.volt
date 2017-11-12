{{- content() -}}

{% include 'partials/flash-banner.volt' %}

<div class="discussion row">
	<div id="mainbox" class="col-md-8">
		<div class="panel panel-default">
			<div align="left" class="panel-heading">
				{{ theApp.name}}
				{% if theApp.status != 'P' %}
				<span class="push-right" style="float:right;">(状态:未发布)</span>
				{% endif %}
			</div>
			<div align="left" class="panel-body">
				<div class="app-create-box" style="padding:0px 10px;">
					<div class="clearfix" style="margin-bottom:40px;">
						<div class="row">
							<div class="col-md-8 col-xs-6">
								<div class="app-icon-box" style="float:left;border: 1px solid #f5f5f5;">
									{% if theApp.icon_version > 0 %}
										<img src="{{ theApp.iconURI() }}" alt="小程序图标" class="app-icon-image">
									{% endif %}
								</div>
								<div style="margin-left:20px;float:left;">
									<div style="margin-top:5px;"><h2>{{ theApp.name }}</h2></div>
									<div>
										{% if theApp.tag1 %}
											{{ link_to('apps/' ~ theApp.tag1.id, theApp.tag1.name, "class":"btn btn-small btn-default btn-apptag")}}
										{% endif %}
										{% if theApp.tag2 %}
											{{ link_to('apps/' ~ theApp.tag2.id, theApp.tag2.name, "class":"btn btn-small btn-default btn-apptag")}}
										{% endif %}
										{% if theApp.tag3 %}
											{{ link_to('apps/' ~ theApp.tag3.id, theApp.tag3.name, "class":"btn btn-small btn-default btn-apptag")}}
										{% endif %}
									</div>
								</div>
							</div>
							<div class="col-md-4 col-xs-6">
								<div class="app-qrcode-box" style="float:right;">
									{% if theApp.qrcode_version > 0 %}
										<img src="{{ theApp.qrcodeURI() }}" alt="小程序二维码" class="app-qrcode-image">
									{% endif %}
								</div>
							</div>
						</div>
					</div>

					<div><label>简介</label></div>
					<div style="margin-bottom:20px;">
						<p>
							{{ theApp.desc|nl2br }}
						</p>
					</div>

					<div style="margin-bottom:20px;"><label>截图</label></div>
					<div class="app-screenshots-box">
						<ul id="app-screenshots-list-id" class="app-screenshots-list">
							{% if theApp.screen1_version > 0 %}
								<li class="app-screenshots-button">
									<img src="{{ theApp.screenshotURI(1) }}" alt="截图1" class="app-screenshots-image">
								</li>
							{% endif %}
							{% if theApp.screen2_version > 0 %}
								<li class="app-screenshots-button">
									<img src="{{ theApp.screenshotURI(2) }}" alt="截图2" class="app-screenshots-image">
								</li>
							{% endif %}
							{% if theApp.screen3_version > 0 %}
								<li class="app-screenshots-button">
									<img src="{{ theApp.screenshotURI(3) }}" alt="截图3" class="app-screenshots-image">
								</li>
							{% endif %}
							{% if theApp.screen4_version > 0 %}
								<li class="app-screenshots-button">
									<img src="{{ theApp.screenshotURI(4) }}" alt="截图3" class="app-screenshots-image">
								</li>
							{% endif %}
						</ul>
					</div>
				</div>
			</div>
			<div class="panel-footer" id="vote-block">
                {%-
                    include 'partials/app/app-buttons' with [
                        'post': post,
                        'currentUser': currentUser,
                        'moderator': moderator,
                        'tokenKey': tokenKey,
                        'token': token
                    ]
                -%}
            </div>
		</div>
		<div class="panel panel-default" id="review-block">
            <div class="panel-heading">
                <div class="post-status-bar">
                    <span class="replyCount">{{- theApp.number_reviews -}}个评论</span>
                    <span class="separator">-</span>
                    <span class="viewCount">{{- theApp.number_views -}}人浏览</span>
                </div>
            </div>
            <div class="panel-body">

                {%- if reviews|length -%}
                    {%- for review in reviews -%}
                    <div class="row replyBlock">
                        <div class="col-md-1">
                            <span class="post-avatar">
                                {{- link_to('user/' ~ review.user.login, review.user.avatarNormal()) -}}
                            </span>
                        </div>
                        <div class="col-md-11">
                              {{-
                                  partial('partials/app/app-review', [
                                      'app': app,
                                      'review': review,
                                      'markdown': this.markdown,
                                      'moderator': moderator,
                                      'currentUser': currentUser
                                  ])
                              -}}
                        </div>
                     </div>
                    {%- endfor -%}
                 {%- else -%}
                    <div style="text-align:center;font-size:1.2rem;color:#777;">暂无评论</div>
                 {%- endif -%}
            </div>
        </div>
        {{-
            partial('partials/app/app-comment-form', [
              'post': post,
              'currentUser': currentUser,
              'tokenKey': tokenKey,
              'token': token
            ])
        -}}
	</div>
	<div class="col-md-3">
		{% include 'partials/sidebar/sidebar-search.volt' %}
        {% include 'partials/sidebar/sidebar-apps-related.volt' %}
		{% include 'partials/sidebar/sidebar-apps-tip.volt' %}
	</div>
</div>

{%- if currentUser -%}
    {%- include 'partials/popup/error-modal.volt' -%}
    {%- include 'partials/popup/reply-popup' with ['post': post, 'tokenKey': tokenKey, 'token': token] -%}
{%- endif -%}
