
{%- if posts|length -%}
	<br/>
	<div align="center">
		<table class="table table-striped list-items" width="90%">
			<tr>
				<th width="50%">Topic</th>
				<th class="hidden-xs">Users</th>
				<th class="hidden-xs">Category</th>
				<th class="hidden-xs">Replies</th>
				<th class="hidden-xs">Views</th>
				<th class="hidden-xs">Created</th>
				<th class="hidden-xs">Last Reply</th>
			</tr>
		{%- for post in posts -%}
			<tr>
				<td align="left">

					{%- if post.sticked == "Y" -%}
						<span class="glyphicon glyphicon-pushpin"></span>&nbsp;
					{%- endif -%}
					{{- link_to('post/' ~ post.id, post.title|e) -}}

					{%- if post.canHaveBounty() -%}
						&nbsp;<span class="label label-info">BOUNTY</span>
					{%- endif -%}

				</td>
				<td class="hidden-xs">
					{%- cache "post-users-" ~ post.id -%}
						{%- for id, user in post.getRecentUsers() -%}
							{% set postAuthor = user[1] %}
						 	<a href="{{ url("user/" ~ postAuthor.login ) }}" title="{{ user[0] }}">
								{{ postAuthor.avatarNormal() }}
							</a>
						{%- endfor -%}
					{%- endcache -%}
				</td>
				<td class="hidden-xs">
					<span class="category">{{ link_to('posts/' ~ post.category.slug, post.category.name) }}</span>
				</td>
				<td class="hidden-xs" align="center">
					<span class="big-number">{% if post.number_replies > 0 %}{{ post.number_replies }}{%endif %}</span>
				</td>
				<td class="hidden-xs" align="center">
					<span class="big-number">{{ post.getHumanNumberViews() }}</span>
				</td>
				<td class="hidden-xs">
					<span class="date">{{ post.getHumanCreatedAt() }}</span>
				</td>
				<td class="hidden-xs">
					<span class="date">{{ post.getHumanModifiedAt() }}</span>
				</td>
			</tr>
		{%- endfor -%}
		</table>
	</div>
{%- endif -%}
