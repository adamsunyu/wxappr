{{ content() }}

{% include 'partials/flash-banner.volt' %}

<div>
    <div class="row">
        <div class="col-md-7 col-md-offset-1">
            <div class="panel panel-default">
            	<div align="left" class="panel-heading">
            		<ul class="nav nav-pills">
                        <li>
                            {{ link_to('node-edit/' ~ node.id, '编辑节点') }}
                        </li>
                        <li class="active">
                            {{ link_to('node-icon/' ~ node.id, '节点图标') }}
                        </li>
                    </ul>
            	</div>
            	<div align="left" class="panel-body">
                    <br>
                    <table class="setting" width="100%">
                        <tr>
                            <td width="5%"></td>
                            <td width="12%"><span class="cate">节点名称:</span></td>
                            <td width="30%">{{ node.name }}<span></span></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>
                            <span class="cate">节点图标:</span></td><td>{{ node.iconNormal() }}</td><td>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4"></td>
                        </tr>
                        <tr>
                            <td colspan="2"></td>
                            <td>
                                <div class="upload-node-icon">
                                    <a class="btn btn-small btn-success">上传图标</a>
                                    {{ hidden_field("node-id", "value":node.id) }}
                                    <div class="upload-box">
                                        <input id="input-upload-icon" accept="image/png,image/jpg,image/jpeg" type="file">
                                    </div>
                                </div>
                            </td>
                            <td></td>
                        </tr>
                    </table>
                    <br>
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
