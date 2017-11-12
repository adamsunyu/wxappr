{{ content() }}

<form method="post" autocomplete="off" action="{{ url("account/changePassword") }}">

<div class="panel panel-default">
		<div align="left" class="panel-heading">
			<ul class="nav nav-pills">
	            <li>
	                {{ link_to('account/settings', '基本资料') }}
	            </li>
				<li class="active">
	                {{ link_to('account/changePassword', '修改密码') }}
	            </li>
	        </ul>
		</div>
		<div class="panel-body">
		<table class="user-form user-form-settings">
			<tbody>
			<tr>
				<td align="right" width="20%;">新密码</td>
				<td align="left" width="30%;">
					{{ form.render("password") }}
				</td>
				<td></td>
			</tr>
			<tr>
				<td align="right">确认新密码</td>
				<td width="30%;">
					{{ form.render("confirmPassword") }}
				</td>
				<td></td>
			</tr>
            <tr>
				<td align="right"></td>
				<td>{{ submit_button("修改", "class": "btn btn-success") }}</td>
				<td></td>
			</tr>
			</tbody>
		</table>
		</div>
</div>
</form>
