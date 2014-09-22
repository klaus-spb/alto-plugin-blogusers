<?php
/* ---------------------------------------------------------------------------
 * @Project: Alto CMS
 * @Plugin Name: Blogusers
 * @Author: Klaus
 * @License: GNU GPL v2 & MIT
 *----------------------------------------------------------------------------
 */
 
/**
 * Запрещаем напрямую через браузер обращение к этому файлу.
 */
if (!class_exists('Plugin')) {
    die('Hacking attemp!');
}

class PluginBlogusers extends Plugin {

    // Объявление делегирований (нужны для того, чтобы назначить свои экшны и шаблоны)
    public $aDelegates = array(
            'action' => array('ActionBlog'=>'_ActionBlog'),
			'template'=>array('widgets/widget.add_to_blog.tpl'=>'_widgets/widget.add_to_blog.tpl'),

    );

    // Объявление переопределений (модули, мапперы и сущности)
    protected $aInherits=array(
     
    );

    // Активация плагина
    public function Activate() {
       
        return true;
    }

    // Деактивация плагина
    public function Deactivate(){
        
        return true;
    }


    // Инициализация плагина
    public function Init() {
        
    }
}
?>
