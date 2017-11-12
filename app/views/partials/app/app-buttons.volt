
<div class="posts-bar clearfix">

    <span class="author-box">
        <span style="color:#777">本小程序由</span>
            {{ link_to('user/' ~ theApp.creator.login, theApp.creator.name ) }}
        <span style="color:#777">发布</span>
    </span>

    <span class="button-box">

        {%- if theApp.creator_id == currentUser.id or moderator == 'Y' -%}
            {{ link_to('edit/app/' ~ theApp.id, '<span class="glyphicon glyphicon-pencil" style="font-size:75%;"></span>&nbsp;修改', 'class': 'btn btn-default btn-xs') }}
        {%- endif %}

        <a href="#" class="btn btn-default btn-xs {% if !appVotedUp %}btn-vote-app{% endif %}"
            onclick="return false" data-type="1" data-id="{{ theApp.id }}">
          <span class="glyphicon glyphicon-thumbs-up" {% if votedType == 1 %}style="color:red;"{% endif %}></span>
          {%- if theApp.votes_up -%}
            <span itemprop="upvoteCount">&nbsp;{{ theApp.votes_up }}赞</span>
          {%- else -%}
            &nbsp;赞
          {%- endif -%}
        </a>
    </span>
</div>
