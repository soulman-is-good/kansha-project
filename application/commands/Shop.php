<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Company
 *
 * @author Soul_man
 */
class ShopCommand extends X3_Command {

    public function init() {
        ini_set('display_errors',false);
        error_reporting(E_ERROR);
    }

    public function runTest() {
        $o = new Shop_Item;
        $model_name = X3::app()->global['name'];
        $ms = $o->search($model_name,true,true);
        echo $ms->count();
    }

    public function runGeneratetop() {
        //TODO: Generate top table
    }
    
    public function runUpdateimages() {
        $items = Shop_Item::get();
        foreach($items as $item){
            if(!empty($item->image)) continue;
            $json = Grabber::grep(array('find'=>1,'q'=>$item->title." ".$item->articule));
            $res = json_decode($json,1);
            if(!empty($res) && $res != null && count($res)==1){
                $json = Grabber::grep(array('modelid'=>$res[0]['modelid'],'hid'=>$res[0]['hid']));
                $res = json_decode($json,1);
                if(!empty($res) && $res != null){
                    $item->image = $res['image'][1];
                    $item->save();
                }
            }else{
                echo "Cant update image for item #".$item->id."\n";
            }
        }
        
    }
    
    public function runGetimages() {
        $items = Shop_Item::get();
        foreach($items as $item){
            $img = $item->image;
            if(strpos($img, 'http://')===0){
                $img = preg_replace("/\&.+$/", "", $img);
                $ext = strtolower(pathinfo($img,PATHINFO_EXTENSION));
                if(!in_array($ext,array('jpg','png','jpeg','gif'))){
                    echo('Неверный формат файла ('.$ext.') для "'.$item->title.'": '.$img);
                    X3::log('Неверный формат файла для "'.$item->title.'": '.$img,'kansha_error');
                    continue;
                }
                if(FALSE === ($image = file_get_contents($img))){
                    echo('Ошибка записи картинки для "'.$item->title.'" по адресу '.$item->image);
                    X3::log('Ошибка записи картинки для "'.$item->title.'" по адресу '.$item->image,'kansha_error');
                    continue;
                }
                $fname = "Shop_Item-".time().rand(0, 9).rand(0, 9).rand(10, 99) . "." . $ext;
                if(FALSE === @file_put_contents(X3::app()->basePath.'/uploads/Shop_Item/'.$fname, $image)){
                    echo('Ошибка записи картинки для "'.$item->title.'" по адресу '.$item->image);
                    X3::log('Ошибка записи картинки для "'.$item->title.'" по адресу '.$item->image,'kansha_error');
                    continue;
                }
                $item->image = $fname;
                if(!$item->save()){
                    echo('Ошибка сохранения "'.$item->title.'" '.print_r($item->table->getErrors(),1));
                    X3::log('Ошибка сохранения "'.$item->title.'" '.print_r($item->table->getErrors(),1),'kansha_error');
                    continue;
                }
            }
        }
    }
    
    public function runGenmodels() {
        set_time_limit(0);
        $grp = isset(X3::app()->global['gid'])?X3::app()->global['gid']:false;
        if(!$grp>0) {
            @file_put_contents(X3::app()->basePath."/uploads/status-models$grp.log",json_encode(array('status'=>'error','message'=>'Не определен id группы','percent'=>'0')));
            return;
        }
        @file_put_contents(X3::app()->basePath."/uploads/status-models$grp.log",json_encode(array('status'=>'idle','message'=>'Подготовка...','percent'=>'1')));
        //$rs = X3::db()->fetchAll("SELECT id,type FROM shop_properties WHERE group_id=$grp AND (type='string' OR type='content')");
        $words = array();
        //if(is_array($rs))
        /*foreach ($rs as $r) {
            if($r['type']!='string' && $r['type']!='content') continue;
            $q2 = X3::db()->fetchAll("SELECT value FROM shop_proplist WHERE property_id=$r[id]");
            foreach ($q2 as $r2) {
                $tmp = trim($r2['value']);
                if(!empty($tmp))
                    $words[] = $r2['value'];
            }
        }*/
        $query = array('@condition'=>array('group_id'=>$grp));
        if (isset($_GET['generatorpage'])) {
            $query['@limit'] = 10;
            $query['@offset'] = $_GET['generatorpage'] * 10;
        }
        
        @file_put_contents(X3::app()->basePath."/uploads/status-models$grp.log",json_encode(array('status'=>'idle','message'=>'Подготовка...','percent'=>'1')));
        $q = Shop_Item::get($query);
        //X3::db()->fetchAll("SELECT id FROM shop_item WHERE group_id=$grp $pagin");
        $tbl_name = 'prop_' . $grp;
        @file_put_contents(X3::app()->basePath."/uploads/status-models$grp.log",json_encode(array('status'=>'idle','message'=>'Обработка...','percent'=>'2')));
        $cnt = $q->count();
        X3::db()->startTransaction();
        foreach($q as $kk=>$o) {
            $commit = false;
            $_words = $words;
            $id = $o->id;
            //$so = $o->getProperties();//Shop_Properties::getProp($tbl_name, array('id'=>$r['id']),1);
            //if($so == null) 
            //    $so = new Shop_Properties($tbl_name);
            $model_name = $o->title;
            //$model_name = preg_replace('/'.$o->formSuffix(true).'/', '', $model_name);
            $manuf = $o->getManufacturer();
            $skip = false;
            if($manuf !== null){
                if($manuf->bskip) $skip = true;
                $manuf_t = $manuf->title . " ";
            }else
                $manuf_t = "";
            $_model_name = $model_name = str_replace($manuf_t, '', $model_name);
            if(!empty($manuf->rules)){
                $rules = explode("\n",$manuf->rules);
                $touch = false;
                foreach($rules as $rule){
                    $match = array();
                    $rule = trim($rule,"\n\r");
                    if(strpos($rule,'!!!')===0){
                        if(substr($rule, 3)==$o->group_id){
                            $skip=true;
                            break;
                        }
                    }
                    if(strpos($rule,'=>')>0){
                        $rule = explode('=>',$rule);
                        $group_id = $rule[0];
                        $rule = $rule[1];
                    }else
                        $group_id = $o->group_id;
                    $rep = "";
                    if(strpos($rule,'-->')>0){
                        $rule = explode('-->', $rule);
                        $rep = $rule[1];
                        $rule = $rule[0];
                    }
                    if($group_id == $o->group_id){
                        if(substr($rule,0,1)=='~' && substr($rule,-1,1)=='~'){
                            try{
                                if(preg_match("$rule",$model_name, $match)>0){
                                    $model_name = $match[1];
                                    $touch = true;
                                }
                            }catch(Exception $e){
                                $msg = "Регулярное выражение '$rule' для производителя #$manuf->id-'$manuf->title' не корректно!";
                                echo $msg."\n";
                                X3::log($msg,'kansha_error');
                                //Notify::sendMail('autoupdateError01',array('message'=>$msg));
                            }
                        }elseif(substr($rule,0,1)=='#' && substr($rule,-1,1)=='#'){
                            try{
                                if(preg_match_all("$rule",$model_name, $match)>0){
                                    array_shift($match);
                                    foreach($match as $ma){
                                        $model_name = str_replace($ma[0],$rep,$model_name);
                                    }
                                    $touch = true;
                                }
                            }catch(Exception $e){
                                $msg = "Регулярное выражение '$rule' для производителя '$manuf->title' не корректно!";
                                echo $msg."\n";
                                X3::log($msg,'kansha_error');
                                //Notify::sendMail('autoupdateError01',array('message'=>$msg));
                            }
                        }else{
                            $model_name = str_replace($rule, $rep, $model_name);
                            $touch = true;
                        }
                    }
                }
            }
            
            //$ms = Shop_Item::get($o->searchItem($model_name,true,true));
            //if(strpos($model_name,'635')!==false)
                //echo $_model_name." == ".$ms->count()." $o->group_id=>'$model_name'\n";
            //if(($ms->count()==1 && $ms[0]->id != $o->id) || $ms->count() > 1){
                $ms = Shop_Item::get($o->searchItem($model_name." ",true,true));
                if(($ms->count()==1 && $ms[0]->id != $o->id) || $ms->count() > 1)
                    $model_name = '';
            //}
            if($kk>0)
                echo str_repeat("\x08",strlen("$percent%"));
            $percent = sprintf("%.02f",($kk/$cnt)*100);
            echo "$percent%";
            @file_put_contents(X3::app()->basePath."/uploads/status-models$grp.log",json_encode(array('status'=>'idle','message'=>'Обработка...','percent'=>$percent)));
            if($skip)
                $model_name='';
            if(!$skip || ($skip && !empty($o->model_name))){
                $o->model_name = trim($model_name);
                if(!$o->save()){
                    var_dump($o->getTable()->getErrors());
                    exit;
                }
            }
            if($kk%50 == 0){
                X3::db()->commit();
                $commit = true;
                //sleep(1);
            }
        }
        if(!$commit)
            X3::db()->commit();
        echo "\n\nOK!\n";
        @file_put_contents(X3::app()->basePath."/uploads/status-models$grp.log",json_encode(array('status'=>'idle','message'=>'Обработка...','percent'=>'100')));
        unset($o,$q);
        if($grp>0)
            @unlink(X3::app()->basePath."/uploads/status-models$grp.log");
    }

    function uniqueModel2($model, $q = array()) {
        $words = explode(' ', $model);
        if (sizeof($words) === 1)
            return $model;
        $unique = array();
        $cond = $q;
        $cond['model_name'] = array('LIKE'=>"'%$model%'");
        $num = Shop_Item::num_rows($cond);
        if ($num > 0) {
            //Дублирующиеся модели
            return '';
        }
        for ($x = 0; $x < sizeof($words) - 1; $x++) {
            foreach ($words as $i => $word) {
                $cond = $q;
                if ($i == 0)
                    $cond['name']=array('LIKE'=>"'%$word %'");
                elseif ($i == sizeof($words) - 1)
                    $cond['name']=array('LIKE'=>"'% $word%'");
                else
                    $cond['name']=array('LIKE'=>"'% $word %'");
                if (Shop_Item::num_rows($cond) == 0) {
                    $unique[] = $word;
                }
            }
            if (sizeof($unique) > 0)
                break;
            $tm = array_pop($words);
            $words[sizeof($words) - 1] .= " $tm";
        }
        if (empty($unique))
            return $model;
        return implode(' ', $unique);
    }    
    
    public function runRegentitle() {
        $id = isset(X3::app()->global['group_id'])?X3::app()->global['group_id']:false;
        if($id == false) die('No group');
        $group = Shop_Group::getByPk($id);
        $items = X3::db()->query("SELECT * FROM shop_item WHERE group_id=$id");//Shop_Item::get(array('group_id'=>$id));
        if(!$items) die(X3::db()->getErrors());
        $cnt = mysql_num_rows($items);
        @file_put_contents(X3::app()->basePath."/uploads/status-models$id.log",json_encode(array('status'=>'idle','message'=>'Обработка...','percent'=>'1')));
        X3::db()->startTransaction();
        $commit = false;
        $kk = 0;
        echo "Starting generation for $cnt items...\n";
        $percent = "";
        while($it = mysql_fetch_assoc($items)){
            $item = new Shop_Item();
            $item->acquire($it);
            $item->getTable()->setIsNewRecord(false);
            $item->suffix = $item->formSuffix();
            $item->properties_text = '';
            $item->getPropstext($group->short_desc);
            $item->save();
            $commit=false;
            if($kk>0)
                echo str_repeat("\x08",strlen("$percent%"));
            $percent = sprintf("%.02f",($kk/$cnt)*100);
            echo "$percent%";
            @file_put_contents(X3::app()->basePath."/uploads/status-models$id.log",json_encode(array('status'=>'idle','message'=>'Обработка...','percent'=>$percent)));
            if($kk>0 && $kk%500==0){
                if(X3::db()->commit());
                    //echo "Commited 500\n";
                else
                    echo X3::db()->getErrors();
                $commit = true;
                sleep(1);
            }
            $kk++;
        }
        echo str_repeat("\x08",strlen("$percent%"));
        echo "100%    \n\n";
        @unlink(X3::app()->basePath."/uploads/status-models$id.log");
        if($commit || X3::db()->commit())
            echo "OK\n";
        else
            echo X3::db()->getErrors();
        unset($item,$items,$group);
    }
    
    public function runCacheprop() {
        if(isset(X3::app()->global['id'])){
            $id = (int)X3::app()->global['id'];
            $a = Shop_Item::getByPk($id);
            $a->properties_text = '';
            echo $a->getPropstext($a->getGroup()->short_desc);
            $a->save();
        }else{
            echo "\tCaching properties list for item.\n";
            echo "\tUSAGE: run.php shop cacheprop id=<shop item id>\n\n";
        }
    }
    
    public function runCacheallprop() {
        $models = X3::db()->query("SELECT * FROM shop_item WHERE properties_text=''");
        if(!is_resource($models))
            die(X3::db()->getErrors());
        $props = array();
        $plist = array();
        while($model = mysql_fetch_assoc($models)){
            $pvals = X3::db()->fetch("SELECT * FROM prop_{$model['group_id']} WHERE id={$model['id']}");
            $result = array();
            //if(!isset($plist[$model['group_id']])){
                //$plist[$model['group_id']] = X3::db()->fetchAll("SELECT * FROM shop_proplist WHERE group_id={$model['group_id']} AND status");
            //}
            if(!isset($props[$model['group_id']])){
                $props[$model['group_id']] = X3::db()->fetchAll("SELECT * FROM shop_properties WHERE group_id={$model['group_id']} AND type='string'");
            }
            foreach($props[$model['group_id']] as $prop){
                if(!isset($pvals[$prop['name']]))
                    continue;
                $val = X3::db()->fetch("SELECT title FROM shop_proplist WHERE group_id={$model['group_id']} AND status AND id={$pvals[$prop['name']]}");
                if(!empty($val) && $val !== false && $val['title'] != '')
                    $result[] = $val['title'];
            }
            $text = implode(', ', $result);
            if(X3::db()->query("UPDATE shop_item SET properties_text='$text' WHERE id={$model['id']}"))
                echo $text . "\r\n\r\n-------------\r\n";
            else
                echo X3::db()->getErrors() . "\r\n\r\n-------------\r\n";
        }
    }
    
    /**
     * Generates all model_name's in all groups and suffix and prop cache
     */
    public function runGenall() {
        set_time_limit(0);
        $base = X3::app()->basePath;
        $grs = X3::db()->fetchAll('SELECT id,title FROM shop_group');
        $titles = isset(X3::app()->global['titles']);
        foreach ($grs as $g){
            echo "-----------------------\n";
            echo "{$g['title']}\n";
            echo "-----------------------\n";
            echo "-Models generation-".date('d.m.Y H:i:s')."\n";
            @file_put_contents(X3::app()->basePath."/uploads/gen_all.stat",json_encode(array('id'=>$g['id'],'title'=>$g['title'])));
            //X3::app()->global['gid'] = $g['id'];
            //$this->runGenmodels();
            system("php $base/run.php shop genmodels gid=".$g['id']);
            if($titles){
                echo "-Suffix generation-".date('d.m.Y H:i:s')."\n";
                //X3::app()->global['group_id'] = $g['id'];
                //$this->runRegentitle();
                system("php $base/run.php shop regentitle group_id=".$g['id']);
            }
        }
        @unlink(X3::app()->basePath."/uploads/gen_all.stat");
        exit;
    }
        
    public function runFixmanufacturer() {
        $items = X3::db()->query("SELECT * FROM shop_item WHERE manufacturer_id IS NULL OR manufacturer_id=0");
        if(!is_resource($items) || mysql_numrows($items)==0) die('Nothing to fix. '.X3::db()->getErrors());
        $manus = X3::db()->fetchAll("SELECT * FROM manufacturer");
        X3::db()->startTransaction();
        $z = 0;
        $o = 0;
        while($item = mysql_fetch_assoc($items)){
            $len = 0;
            $id = 0;
            $msg = "$z fixed";
            foreach($manus as $m)
                if(stripos($item['title'],$m['name'])===0 && strlen($m['name'])>$len){
                    $len = strlen($m['name']);
                    $id = $m['id'];
                }
            if($id>0){
                if($z>0)
                    echo str_repeat("\x08",$o);
                echo $msg;
                X3::db()->addTransaction("UPDATE `shop_item` SET `manufacturer_id`='{$id}' WHERE `id`='{$item['id']}'");
                $z++;
            }
            $o = strlen($msg);
        }
        if(!X3::db()->commit()){
            X3::db()->rollback();
            echo X3::db()->getErrors();
        }
        echo "\n\n\n";
        exit;
    }
    
    public function runSearchprepare() {
        if(isset(X3::app()->global['gid']) && X3::app()->global['gid']>0){
            $q = X3::db()->query("SELECT id, title FROM shop_group WHERE id>=".X3::app()->global['gid']." ORDER BY id");
            if(!$q) die(X3::db()->getErrors());
        }else{
            $q = X3::db()->query("SELECT id, title FROM shop_group ORDER BY id");
            if(!$q) die(X3::db()->getErrors());
        }
        $z = "";
        $group = mysql_fetch_object($q);
        if(!isset(X3::app()->global['regenerate']))
            $z = "si.id NOT IN (SELECT item_id FROM search_item ss WHERE group_id=$group->id) AND ";
        else
            X3::db()->query("TRUNCATE search_item");
        $qi = X3::db()->query("SELECT * FROM shop_item si INNER JOIN prop_$group->id p ON p.id=si.id WHERE $z group_id=$group->id");
        if(!$qi) die(X3::db()->getErrors());
        $props = X3::db()->fetchAll("SELECT * FROM shop_properties WHERE group_id=$group->id");
        //prepare index array
        $pq = X3::db()->query("SELECT * FROM shop_proplist WHERE group_id=$group->id");
        if(!$pq) die(X3::db()->getErrors());
        $plist = array();
        while($pl = mysql_fetch_assoc($pq)){
            if($pl['title']=='')$pl['title'] = $pl['value'];
            $plist[(int)$pl['id']] = $pl;
        }
        //Generation process
        X3::db()->startTransaction();
        $i = 0;
        while($item = mysql_fetch_assoc($qi)){
            $title = $group->title." ".$item['title']." ".$item['articule'];
            foreach($props as $prop){
                if(($prop['type'] == 'string' || $prop['type'] == 'content') && isset($item[$prop['name']]) && $item[$prop['name']]>0 && isset($plist[(int)$item[$prop['name']]])){
                    $title .= " ".$plist[(int)$item[$prop['name']]]['title'];
                }
                if($prop['type'] == 'boolean' && isset($item[$prop['name']]) && $item[$prop['name']]>0){
                    $title .= " ".$prop['label'];
                }
                if(($prop['type'] == 'decimal' || $prop['type'] == 'integer') && isset($item[$prop['name']])){
                    $mm = mb_substr($prop['label'],$j=mb_strrpos($prop['label'], '(')+1,mb_strrpos($prop['label'], ')')-$j);
                    $title .= " ".$item[$prop['name']]." ".$mm;
                }
            }
            X3::db()->addTransaction("INSERT INTO search_item VALUES (NULL,'{$item['id']}','{$group->id}','$title')");
            $i++;
            if($i%5000 == 0){
                X3::db()->commit();
            }
        }
        if($i%5000 != 0){
            X3::db()->commit();
        }
        $group = mysql_fetch_object($q);
        exec("php ".X3::app()->basePath."/run.php shop searchprepare gid=".$group->id." > /dev/null 2>&1 &");
    }
}

?>
