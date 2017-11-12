{{ content() }}

<form method="post" autocomplete="off" action="{{ url("account/changeDomain") }}">
<div class="panel panel-default">
	<div align="left" class="panel-heading">
		<ul class="nav nav-pills">
            <li>
                {{ link_to('account/settings', '基本资料') }}
            </li>
			<li class="active">
                {{ link_to('account/changeDomain', '修改域名') }}
            </li>
        </ul>
	</div>
	<div align="left" class="panel-body">
		<table class="user-form user-form-settings">
			<tbody>
			{%- if days == -1 or days >= 365 -%}
			<tr>
				<td align="right"><label>当前域名:</label></td>
				<td>
					<label>{{ user.login }} </label>
				</td>
				<td>(1年内只允许修改一次)</td>
			</tr>
			<tr>
				<td align="right">{{ form.label('login') }}</td>
				<td>{{ form.render('login') }}</td>
				<td>(4 ~ 12个字符, 只能使用英文字母、-、数字)</td>
			</tr>
	        <tr>
				<td align="right"></td>
				<td>{{ form.render('保存') }}</td>
				<td></td>
			</tr>
			{%- else -%}
			<tr>
				<td align="right"><label>当前域名:</label></td>
				<td>
					<label>{{ user.login }} </label>
				</td>
				<td></td>
			</tr>
			<tr>
				<td align="right">系统限制:</td>
				<td>(还需要{{ (365-days) }}天才能再次修改域名)</td>
				<td></td>
			</tr>
			{%- endif -%}
			</tbody>
		</table>
	</div>
</div>
</form>
