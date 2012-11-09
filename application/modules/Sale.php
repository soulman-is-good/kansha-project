<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Sale
 *
 * @author Soul_man
 */
class Sale extends X3_Module {
    
    public function actionIndex() {
        $this->template->render('index',array('bread'=>array('#'=>'Распродажа')));
    }
}

?>
