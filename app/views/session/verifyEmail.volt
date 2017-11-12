{{ content() }}

<div class="col-md-5 col-md-offset-3 col-xs-12 center-block">

	<div class="clearfix" align="center" style="margin-bottom: 10px;">
		{% if inviter %}
			<span>{{ link_to('user/'~ inviter.login, inviter.name) }}</span><span class="label-color">邀请你加入微信小程序之家</span>
		{% else %}
			<span class="label-color">欢迎注册</span>
		{% endif %}
	</div>

	<div align="center" class="module well well-zb">
		{{ form('class': 'form-table') }}
			<table class="user-form">
				<tbody>
				<tr>
					<td>
						{{ form.render('email') }}
					</td>
				</tr>
				<tr>
					<td>{{ form.render('发送邀请信') }}</td>
				</tr>
				</tbody>
			</table>
		</form>
	</div>
</div>
