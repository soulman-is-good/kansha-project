<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Blacklist
 *
 * @author Soul_man
 */
class Blacklist extends X3_Module_Table {
    
    public $encoding = 'UTF-8';
    public $tableName = 'blacklist';
    public $_fields = array(
        'id'=>array('integer[10]','unsigned','primary','auto_increment'),
        'ip'=>array('string[128]','unique'),
        'status'=>array('boolean','default'=>'1'),
    );
        
    public function init() {
        if(!is_file('.htblacklist')){
            @file_put_contents(".htblacklist", "<Location />\n<Limit GET POST PUT>\norder allow,deny\nallow from all\n</Limit>\n</Location>");
            chmod("black.list", 0644);
        }
    }
    
    public function afterSave() {
        $data = X3::db()->fetchAll("SELECT ip FROM `$this->tableName`");
        $tmp = "\n";
        foreach ($data as $d)
            $tmp .= "deny from {$d['ip']}\n";
        @file_put_contents(".htblacklist", "<Location />\n<Limit GET POST PUT>\norder allow,deny\nallow from all$tmp</Limit>\n</Location>");
    }
    
}

?>
