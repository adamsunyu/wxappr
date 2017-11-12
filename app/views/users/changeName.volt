{{ content() }}

<form method="post" autocomplete="off" action="{{ url("account/changeName") }}">
<div class="panel panel-default">
		<div align="left" class="panel-heading">
			<ul class="nav nav-pills">
	            <li>
	                {{ link_to('account/settings', '基本资料') }}
	            </li>
				<li class="active">
	                {{ link_to('account/changeName', '修改姓名') }}
	            </li>
	        </ul>
		</div>
		<div align="left" class="panel-body">
		<table class="user-form user-form-settings">
			<tbody>

			{%- if days == -1 or days >= 365 -%}
			<tr>
				<td align="right"><label>当前姓名:</label></td>
				<td>
					<label>{{ user.name }} </label>
				</td>
				<td>（1年内只允许修改一次）</td>
			</tr>
			<tr>
				<td align="right">{{ form.label('name') }}</td>
				<td>{{ form.render('name') }}</td>
				<td></td>
			</tr>
            <tr>
				<td align="right"></td>
				<td>{{ form.render('保存') }}</td>
				<td></td>
			</tr>
			{%- else -%}
			<tr>
				<td align="right"><label>当前姓名:</label></td>
				<td>
					<label>{{ user.name }} </label>
				</td>
				<td></td>
			</tr>
			<tr>
				<td align="right">系统限制:</td>
				<td>(还需要{{ (365-days) }}天才能再次修改姓名)</td>
				<td></td>
			</tr>
			{%- endif -%}
			</tbody>
		</table>
		</div>
</div>
</form>
