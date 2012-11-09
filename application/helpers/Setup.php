<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Setup
 *
 * @author Soul_man
 */
class Setup extends X3_Component{

    public $filename = 'setup.txt';

    public function __construct($config) {
        $this->filename = X3::app()->getPathFromAlias($this->filename);
        $this->addTrigger('onStartApp');
    }

    public function onStartApp() {
        if(is_file($this->filename)){
            $mods = X3::app()->getPathFromAlias('@app:modules');
            //init modules
            $handle = opendir($mods);
            while (false !== ($file = readdir($handle)))
            {
                if ($file != "." and $file != "..")
                {
                    $file = str_replace('.php','',$file);
                    if(is_subclass_of($file, 'X3_Module'))
                        X3_Module::newInstance($file);
                }
            }
            $content = file_get_contents($this->filename);
            $content = explode("\n",$content);
            foreach($content as $c){
                if(!X3::app()->db->query($c))
                        die(X3::app()->db->getErrors());
            }
            @unlink($this->filename);
        }
    }
}
?>
