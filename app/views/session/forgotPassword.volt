{{ content() }}

<div class="col-md-5 col-md-offset-3 col-xs-12 center-block">
	<div align="center" class="module well well-zb">

		{{ form('class': 'form-table') }}

			<table class="user-form">
				<tbody>
					<tr>
						<td>
							<h5>你的注册邮箱</h5>
						</td>
					</tr>
				<tr>
					<td>
						{{ form.render('email') }}
					</td>
				</tr>
				<tr>
					<td>
						{{ form.render('重设密码') }}
					</td>
				</tr>
				</tbody>
			</table>
		</form>
	</div>
</div>
