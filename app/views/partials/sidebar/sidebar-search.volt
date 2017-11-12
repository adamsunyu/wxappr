<div class="module" style="margin-bottom: 10px;">
    <div class="module-head">搜索小程序</div>
    <div class="module-body" id="sidebar-search-bar">
        <form action="/search/app" method="post" autocomplete="off" role="form">
            <div class="input-group">
              <input id="keywords" name="keywords" value="{{ keywords }}"type="text" class="form-control" placeholder="共有{{ appCount }}个小程序">
              <span class="input-group-btn">
                <button class="btn btn-default" type="submit"><span class="glyphicon glyphicon-search"></span></button>
              </span>
            </div>
        </form>
    </div>
</div>
