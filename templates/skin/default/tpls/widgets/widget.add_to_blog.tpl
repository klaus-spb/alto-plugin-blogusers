<section class="panel panel-default widget">
    <div class="panel-body">

        <header class="widget-header">
            <h3 class="widget-title">{$aLang.plugin.blogusers.add_users}</h3>
        </header>

        <div class="widget-content">
            <form action="" method="post">
                <p class="text-muted">
                    <small>{$aLang.plugin.blogusers.do_not_forgot}</small>
                </p>
				<div class="input-group">
                   <select name="status" id="status" class="form-control">
							<option value="reader">{$aLang.blog_admin_users_reader}</option>
							<option value="ban">{$aLang.blog_admin_users_bun}</option>
							<option value="moderator">{$aLang.blog_admin_users_moderator}</option>
							<option value="administrator">{$aLang.blog_admin_users_administrator}</option>                            
                    </select>
                </div>
				<div class="input-group">
                    <input type="text" id="blogusers_user_add" name="users" class="form-control autocomplete-users-sep"/>
                        <span class="input-group-btn">
                            <button class="btn btn-default" name="submit_user_add" >{$aLang.plugin.blogusers.add}</button>
                        </span>
                </div>
                <input type="hidden" name="security_key" value="{$ALTO_SECURITY_KEY}" />
            </form>
        </div>
    </div>
</section>
 
