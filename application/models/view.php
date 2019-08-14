<?php
class View{
    public function render($file,  $title = null)
    {
        $body =  self::body($file);
        $tpl =  self::body('../views/tpl/bootstrap.php');
        return  str_replace(['{{body}}',  '{{title}}'],  [$body,$title ],  $tpl);

    }
	public function body($file) {
		ob_start();
		include($p =  dirname(__FILE__) . '/' . $file);
		return ob_get_clean();
	}
}