{%- if otherUser -%}
    {% set whoName = '他' %}
{%- else -%}
    {% set whoName = '我' %}
{%- endif -%}

<div class="module sidebar-margin-top">
    <div class="module-head">{{ whoName }}的资料</div>
    <div class="module-body">
        <table class="table-stats">
            {% if socialData['gender'] != '未知' and socialData['gender']  != '不公开'  %}
            <tr>
                <td><label>性别:</label><span>{{ socialData['gender'] }}</span></td>
            </tr>
            {% endif %}

            {% if socialData['skills'] != '未知' %}
            <tr>
                <td><label>专长:</label><span>{{ socialData['skills'] }}</span></td>
            </tr>
            {% endif %}

            {% if socialData['website'] != '未知' %}
            <tr>
                <td><label>网站:</label><span>{{ socialData['website'] }}</span></td>
            </tr>
            {% endif %}

            {% if socialData['zhihu'] != '未知' %}
            <tr>
                <td><label>知乎:</label><span>{{ socialData['zhihu'] }}</span></td>
            </tr>
            {% endif %}

            {% if socialData['weibo'] != '未知' %}
            <tr>
                <td><label>微博:</label><span>{{ socialData['weibo'] }}</span></td>
            </tr>
            {% endif %}

            {% if socialData['gzhao'] != '未知' %}
            <tr>
                <td><label>公众号:</label><span>{{ socialData['gzhao']  }}</span></td>
            </tr>
            {% endif %}

            {% if socialData['github'] != '未知' %}
            <tr>
                <td><label>Github:</label><span>{{ socialData['github'] }}</span></td>
            </tr>
            {% endif %}
        </table>
    </div>
</div>
