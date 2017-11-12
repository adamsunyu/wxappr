{%-
    set currentUser  = session.get('identity')
-%}

<div class="module sidebar-margin-top">
    <div class="module-body">
        <table class="table-stats sidebar-table-buttons">
            <tr>
                <td class="33%">
                    {% if currentUser %}
                        <a href="/share/{{ node.id }}" class="btn btn-default btn-xs btn-node-ask" data-id="{{ node.id }}">
                            <span style="font-size:85%;" class="glyphicon glyphicon-pencil"></span>&nbsp;分享
                        </a>
                    {% else %}
                        <a class="btn btn-default btn-xs need-login" data-id="{{ node.id }}">
                            <span class="glyphicon glyphicon-pencil"></span>&nbsp;分享
                        </a>
                    {% endif %}
                </td>
                <td>
                    {% if currentUser %}
                        {% if followedByMe %}
                            <a class="btn btn-success btn-xs btn-node-unfollow" data-id="{{ node.id }}">
                                <span class="glyphicon glyphicon-star"></span>&nbsp;已关注
                            </a>
                        {% else %}
                            <a class="btn btn-default btn-xs btn-node-follow" data-id="{{ node.id }}">
                                <span class="glyphicon glyphicon-star-empty"></span>&nbsp;关注
                            </a>
                        {% endif %}
                    {% else %}
                    <a class="btn btn-default btn-xs need-login" data-id="{{ node.id }}">
                        <span class="glyphicon glyphicon-star-empty"></span>&nbsp;关注
                    </a>
                    {% endif %}
                </td>
            </tr>
        </table>
    </div>
</div>
