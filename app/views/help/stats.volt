<div class="panel panel-default">
    <div class="panel-heading">
        <h1>社区运行状况</h1>
    </div>
    <div class="panel-body">
        <br>
		{% cache "stats" 3600 %}
		<table class="table table-striped" align="center" style="width:300px;border:1px solid #ddd;">
            <tr>
                <td>成员</td>
                <td align="right">{{ number_format(users) }}</td>
            </tr>
			<tr>
				<td>话题</td>
				<td align="right">{{ number_format(threads) }}</td>
			</tr>
			<tr>
				<td>回复</td>
				<td align="right">{{ number_format(replies) }}</td>
			</tr>
            <tr>
				<td>总赞</td>
				<td align="right">{{ number_format(votes) }}</td>
			</tr>
            <tr>
				<td>经济总量</td>
				<td align="right">{{ number_format(votes) }}</td>
			</tr>
		</table>
		{% endcache %}
    </div>
</div>
