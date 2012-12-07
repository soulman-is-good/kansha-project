<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Admin
 *
 * @author Soul_man
 */
class AdminSide extends X3_Component {
    
    public static $theme = '.kansha';
    private $online = 0;
    
    public function init() {
        $this->addTrigger('onStartApp');
        $this->addTrigger('onRender');
    }
    
    public function onStartApp($controller,$action) {
        //For profileing
        if(X3::user()->isAdmin()){
            X3::profile()->enable = true;
        }
        if($controller == 'admin'){
            X3::app()->VIEWS_DIR = 'modules' . DIRECTORY_SEPARATOR . 'Admin';
            X3::app()->LAYOUTS_DIR = 'admin' . DIRECTORY_SEPARATOR . 'layouts';
            //Assets::publish();
            //X3::app()->MODULES_DIR = 'modules' . DIRECTORY_SEPARATOR . 'Admin';
            
            //$this->stopBubbling(__FUNCTION__);
        }
        return array($controller,$action);
    }
    
    public function onRender($output) {
        if(X3::user()->isAdmin()){
            //$online = json_encode($this->online);
            //$output = str_replace('</body>',
                //"<script type=\"text/javascript\">
                    //var users_online = $online;
                //</script>"
                //.'<script type="text/javascript" id="x3.admin" src="/js/jquery.x3admin.js"></script></body>',$output);
        }
        return $output;
    }
    
}

?>
