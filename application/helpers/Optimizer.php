<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * class Optimizer optimizes scripts, css and makes replaces with SysSettings
 * 
 * Doesn't make inline < script /> optimization
 *
 * @author Soul_man
 */
class Optimizer extends X3_Component {
    
    //TODO: @import logic and url images logic in CSS!
    const ONLY_CSS = 1;
    const ONLY_JS = 2;
    const COMPRESS_HEAD = 3;
    const COMPRESS_INLINE_CSS = 4;
    const COMPRESS_ALL = 7;
    
    public $assets = false;
   
    
    public function init() {
        if($this->assets == false){
            $this->assets = X3::app()->basePath . DIRECTORY_SEPARATOR . "assets";
            if(!file_exists($this->assets))
                if(!mkdir ($this->assets,0777))
                throw new X3_Exception("Can't find assets directory in \"$this->assets\"");
        }
        $this->addTrigger('onRender');
    }

    public function onRender($output) {
        $output = $this->compressCss($output);
        $output = $this->manageSettings($output);
        return $output;
    }
    
    private function compressCss($output) {
        function compress($url) {
            $text = file_get_contents($url);
            $dir = pathinfo($url,PATHINFO_DIRNAME);
            $m = array();
            if (preg_match_all("/@import (.+)?;/", $text, $m)){
                foreach($m[1] as $i => $include){
                    $include = trim($include);
                    if(preg_match("/url\((.+)?\)|\"(.+)?\"/", $include,$tmp)){
                        $nurl = $dir."/";
                        if(isset($tmp[2]))
                            $nurl .=$tmp[2];
                        else
                            $nurl .= trim($tmp[1],'"');
                        if(FALSE!==($nurl = realpath($nurl))){
                            $temp = compress($nurl);
                            $text = str_replace($m[0][$i], $temp, $text);
                        }
                    }
                }
            }
            // remove comments
            $text = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $text);
            // remove tabs, spaces, newlines, etc.
            $text = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $text);
            return $text;
        }
        $buffer = "";
        $m = array();
        if (preg_match_all("/<link.+?rel=\"stylesheet\".+?>/", $output, $m)) {
            foreach($m[0] as $link){
                $tmp = array();
                if(preg_match("/href=\"(.+)?\"/",$link,$tmp)>0){
                    $url = trim($tmp[1],'/');
                    if(FALSE!==($url = realpath($url))){
                        $buffer .= compress($url);
                        $output = str_replace($link, "", $output);
                    }
                }
            }
        }
        if($buffer!=""){
            $filename = $this->assets . DIRECTORY_SEPARATOR . "style.css";
            $relative = str_replace(X3::app()->basePath, "", $filename);
            $relative = str_replace("\\", "/", $relative);
            $output = preg_replace("/<\/head>/", "<link rel=\"stylesheet\" href=\"$relative\" />" . PHP_EOL, $output);
            @file_put_contents($filename, $buffer);
        }
        return $output;
    }
    private function manageSettings($output) {
        $m = array();
        if (preg_match_all("/\[@Syssettings\((.*)?\)@\]/", $output, $m)) {
            $replaces = array_shift($m);
            $matches = array_shift($m);
            $names = array();
            $data = array();
            if (!empty($matches)) {
                foreach ($matches as $i => $match) {
                    list($_name, $type, $title, $group, $default) = explode(',', $match);
                    $name = trim($_name, "'");
                    $data[$name]['type'] = trim($type, "'");
                    $data[$name]['title'] = trim($title, "'");
                    $data[$name]['group'] = trim($group, "'");
                    $data[$name]['default'] = trim($default, "'");
                    $data[$name]['replace'] = $replaces[$i];
                    $names[$name] = $_name;
                }
                $_names = implode(",", $names);
                $models = X3::app()->db->fetchAll("SELECT `name`,`value` FROM sys_settings WHERE `name` IN ($_names)");
                foreach ($models as $model) {
                    $data[$model['name']]['value'] = $model['value'];
                    unset($names[$model['name']]);
                }
                foreach ($names as $name) {
                    $name = trim($name, "'");
                    $data[$name]['value'] = (Syssettings::getValue($name, $data[$name]['type'], $data[$name]['title'], $data[$name]['group'], $data[$name]['default']));
                    switch ($data[$name]['type']) {
                        case 'content':
                            $data[$name]['value'] = nl2br($data[$name]['value']);
                            break;
                    }
                }
                foreach ($data as $dd) {
                    $output = str_replace($dd['replace'], stripslashes($dd['value']), $output);
                }
            }
        }
        return $output;
    }

}

?>
