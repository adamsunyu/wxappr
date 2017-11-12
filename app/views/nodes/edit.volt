{{ content() }}

{% include 'partials/flash-banner.volt' %}

<div>
    <div class="row">
        <div class="col-md-7 col-md-offset-1">
            <div class="panel panel-default">
            	<div align="left" class="panel-heading">
            		<ul class="nav nav-pills">
                        <li class="active">
                            {{ link_to('node-edit/' ~ node.id, '编辑节点') }}
                        </li>
                        <li>
                            {{ link_to('node-icon/' ~ node.id, '节点图标') }}
                        </li>
                    </ul>
            	</div>
            	<div align="left" class="panel-body">
                    <br>
                    <form id="create-node" method="post" autocomplete="off" role="form">
                        {{
                            hidden_field(
                                security.getPrefixedTokenKey('edit-node-' ~ node.id),
                                "value": security.getPrefixedToken('edit-node-' ~ node.id)
                            )
                        }}

                        {{ hidden_field("id") }}

                        <table class="setting" width="100%">
                            <tr>
                                <td width="5%"></td>
                                <td width="8%"><span class="cate">{{ form.label('name') }}</span></td>
                                <td width="80%">
                                    {{ form.render('name') }}
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td><span class="cate">{{ form.label('slug') }}</span></td>
                                <td>
                                    {{ form.render('slug') }}
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td><span class="cate">简介:</span></td>
                                <td>
                                    {{ text_area("aboutArea", "rows": 10, "placeholder": "请简要的说明此节点代表的技术或工具", "class": "form-control") }}
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan="2"></td>
                                <td>
                                    <div class="post-button">
                                        <button type="submit" class="btn btn-sm btn-success pull-right">保存</button>
                                    </div>
                                    <div class="post-button">
                                        <button type="button" class="btn btn-default">{{ link_to('node/' ~ node.slug , '取消') }}</button>
                                    </div>
                                </td>
                                <td></td>
                            </tr>
                        </table>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            {% include 'partials/sidebar/sidebar-node-info.volt' %}
            {% include 'partials/sidebar/sidebar-node-about.volt' %}
        </div>
    </div>
</div>

{%- include 'partials/popup/error-modal.volt' -%}
