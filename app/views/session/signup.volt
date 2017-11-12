{{ content() }}

<div class="col-md-5 col-md-offset-3 col-xs-12 center-block">

	<div class="clearfix" align="center" style="margin-bottom: 10px;">
		{% if inviter %}
			<span>{{ link_to('user/'~ inviter.login, inviter.name) }}</span><span class="label-color">邀请你加入微信小程序之家</span>
		{% else %}
			<span class="label-color">请填写资料完成注册</span>
		{% endif %}
	</div>

	<div align="center" class="module well well-zb">
		{{ form('class': 'form-table') }}

			{{ hidden_field("email") }}

			<table class="user-form">
				<tbody>
				<tr>
					<td>
						{{ form.render('name') }}
					</td>
				</tr>
				<tr>
					<td>
						{{ form.render('city') }}
					</td>
				</tr>
				<tr>
					<td>
						{{ form.render('password') }}
					</td>
				</tr>
				<tr>
					<td>
						{{ form.render('confirmPassword') }}
					</td>
				</tr>
				<tr>
					<td>{{ form.render('完成注册') }}</td>
				</tr>
				</tbody>
			</table>

			{{ form.render('csrf', ['value': security.getToken()]) }}
		</form>
	</div>
</div>
