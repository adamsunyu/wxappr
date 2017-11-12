{{ content() }}

<form method="post" autocomplete="off" action="{{ url("account/changeCity") }}">
<div class="panel panel-default">
		<div align="left" class="panel-heading">
			<ul class="nav nav-pills">
	            <li>
	                {{ link_to('account/settings', '基本资料') }}
	            </li>
				<li class="active">
	                {{ link_to('account/changeCity', '修改城市') }}
	            </li>
	        </ul>
		</div>
		<div align="left" class="panel-body">
		<table class="user-form user-form-settings">
			<tbody>
			<tr>
				<td width="20%" align="right"><label>当前城市:</label></td>
				<td style="width:30%">
					<label>{{ user.city_name }} </label>
				</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td align="right">{{ form.label('city') }}</td>
				<td>{{ form.render('city') }}</td>
				<td></td>
			</tr>
            <tr>
				<td align="right"></td>
				<td>{{ form.render('保存') }}</td>
				<td></td>
			</tr>
			</tbody>
		</table>
		</div>
</div>
</form>
