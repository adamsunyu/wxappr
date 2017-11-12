{%-
    set currentUser  = session.get('identity')
-%}
<div class="module {% if currentUser %} sidebar-margin-top {% endif %}">
    <div class="module-head">招募志愿开发者</div>
    <div class="module-body">
        <table class="table-stats">
            <tr>
                <td>本站现寻求一名志愿开发者，协助做一些开发工作。</td>
            </tr>
            <tr>
                <td class="text-right">{{link_to('help/volunteers', '>>查看详情', 'target':'_blank')}}</td>
            </tr>
        </table>
    </div>
</div>
