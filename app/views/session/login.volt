{{ content() }}

{% include 'partials/flash-banner.volt' %}

<div class="col-md-5 col-md-offset-3 col-xs-12 center-block">

	<div class="clearfix" align="center" style="margin-bottom: 10px;">
		<span class="label-color">欢迎登录</span>
	</div>

	<div align="center" class="module well well-zb">

		{{ form('class': 'form-table') }}

		<br>
		<table class="user-form">
			<tr>
				<td>
					{{ form.render('email') }}
				</td>
			</tr>
			<tr>
				<td>
					{{ form.render('password') }}
				</td>
			</tr>
			<tr>
				<td>
					{{ form.render('登录') }}
				</td>
			</tr>
		</table>

			{{ form.render('csrf', ['value': security.getToken()]) }}

			<hr>

			<div class="forgot">
				{{ link_to("account/forgotPassword", "忘记密码了") }}
			</div>
		</form>
	</div>
</div>
