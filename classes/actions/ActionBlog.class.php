<?php
/* ---------------------------------------------------------------------------
 * @Project: Alto CMS
 * @Plugin Name: Blogusers
 * @Author: Klaus
 * @License: GNU GPL v2 & MIT
 *----------------------------------------------------------------------------
 */
  
class PluginBlogusers_ActionBlog extends PluginBlogusers_Inherit_ActionBlog {	

	public function Init() {
		parent::Init();
	}
	
	
	protected function EventAdminBlog() {
		parent::EventAdminBlog();
		
		/**
	         * Проверяем передан ли в УРЛе номер блога
	         */
	        $sBlogId = $this->GetParam(0);
	        if (!$oBlog = $this->Blog_GetBlogById($sBlogId)) {
	            return parent::EventNotFound();
	        }
	        /**
	         * Проверям авторизован ли пользователь
	         */
	        if (!$this->User_IsAuthorization()) {
	            $this->Message_AddErrorSingle($this->Lang_Get('not_access'), $this->Lang_Get('error'));
	            return Router::Action('error');
	        }
	        /**
	         * Проверка на право управлением пользователями блога
	         */
	        if (!$this->ACL_IsAllowAdminBlog($oBlog, $this->oUserCurrent)) {
	            $this->Message_AddErrorSingle($this->Lang_Get('not_access'), $this->Lang_Get('not_access'));
	            return Router::Action('error');
	        }
	        
		$this->Viewer_AddWidget('right', '/widgets/widget.add_to_blog.tpl');
		
		
		if (F::isPost('submit_user_add')) {
			
			$sUsers = F::GetRequest('users', null, 'post');
			$sStatus = F::GetRequest('status', null, 'post');
			
			$aUsers = explode(',', $sUsers);
			
			foreach ($aUsers as $sUser) {
			
				$sUser = trim($sUser);
				if ($sUser == '') {
					continue;
				}
				$oUser = $this->User_GetUserByLogin($sUser);
				
				if (!$oUser || $oUser->getActivate() != 1) {					
					continue;
				}
				$oBlogUser = $this->Blog_GetBlogUserByBlogIdAndUserId($oBlog->getId(), $oUser->getId());
				if (!$oBlogUser) {
					$oBlogUserNew = Engine::GetEntity('Blog_BlogUser');
					$oBlogUserNew->setBlogId($oBlog->getId());
					$oBlogUserNew->setUserId($oUser->getId());
					$oBlogUserNew->setUserRole(ModuleBlog::BLOG_USER_ROLE_USER);
					switch ($sStatus) {
						case 'administrator':
							$oBlogUserNew->setUserRole(ModuleBlog::BLOG_USER_ROLE_ADMINISTRATOR);
							break;
						case 'moderator':
							$oBlogUserNew->setUserRole(ModuleBlog::BLOG_USER_ROLE_MODERATOR);
							break;
						case 'reader':
							$oBlogUserNew->setUserRole(ModuleBlog::BLOG_USER_ROLE_USER);
							break;
						case 'ban':
							if ($oBlogUserNew->getUserRole() != ModuleBlog::BLOG_USER_ROLE_BAN) {
								$oBlog->setCountUser($oBlog->getCountUser() - 1);
							}
							$oBlogUserNew->setUserRole(ModuleBlog::BLOG_USER_ROLE_BAN);
							break;
						default:
							$oBlogUserNew->setUserRole(ModuleBlog::BLOG_USER_ROLE_GUEST);
					}
				
					if ( $bResult = $this->Blog_AddRelationBlogUser($oBlogUserNew) ) {
						$oBlog->setCountUser($oBlog->getCountUser() + 1);
						$this->Blog_UpdateBlog($oBlog);
						
						$this->Stream_Write($oUser->getId(), 'join_blog', $oBlog->getId());
						
						$this->Userfeed_SubscribeUser(
							$oUser->getId(), ModuleUserfeed::SUBSCRIBE_TYPE_BLOG, $oBlog->getId()
						);	
					}
					
				}
				
			}
			
			Router::Location( Router::GetPath('blog') . "admin/{$oBlog->getId()}" );
			
		}	
		
	}
	
	
}

?>
