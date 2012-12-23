<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Shop
 *
 * @author Soul_man
 */
class Shop extends X3_Module_View{
    
    public $encoding = 'UTF-8';    
    
    public $viewName = 'shop';
    public $fieldset = array(
        'select' => array(
            'data_section' => array(
                'sid' => 'id',
                'stitle' => 'title'
            ),
            'data_catalog' => array(
                'cid' => 'id',
                'section' => 'section_id',
                'ctitle' => 'title',
            )
        ),
        'join' => array(
            'inner' => array('table' => 'data_section', 'on' => array('data_section.id' => 'data_catalog.section_id'))
        )        
    );
    
    public function moduleName() {
        return 'Товары';
    }
    
    public function actionTest() {
        if(!X3::user()->isAdmin()) exit;
        $from = $_GET['from'];
        $to = $_GET['to'];
        for($i = $from; $i<=$to;$i++)
            X3::db()->query("ALTER TABLE shop_properties DROP INDEX group_id_$i");
        exit;
    }    
    
    public function actionGenerateall() {
        if(!X3::user()->isAdmin()) exit;
        exec("php run.php shop genall > /dev/null 2>&1 &");
        exit;
    }
    
    
    public function actionFiximage() {
        $ms = X3::db()->fetchAll("SELECT id, image FROM shop_item");
        X3::db()->startTransaction();
        foreach ($ms as $m){
            $f = $m['image'];
            if(strlen($f)>1 && substr($f,-1,1)=='.' && is_file("uploads/Shop_Item/$f")){
                X3::db()->addTransaction("UPDATE shop_item SET image='{$f}jpg' WHERE id={$m['id']}");
                @rename("uploads/Shop_Item/$f", "uploads/Shop_Item/{$f}jpg");
                echo $f." ... OK!<br/>";
            }
        }
        if(!X3::db()->commit())
            echo X3::db()->getErrors();
        exit;
    }    
    
    public function actionGogo() {
        $grs = X3::db()->fetchAll("SELECT * FROM shop_group");
        foreach($grs as $gr){
            //if(in_array($gr['id'],array(7,15,20,45,59,63))) continue;
            $prs = X3::db()->fetchAll("SELECT * FROM shop_properties WHERE group_id={$gr['id']}");
            $total = array();
            foreach($prs as $pr){
                if($pr['type']=='string'){
                    $pz = X3::db()->fetchAll("SELECT id FROM shop_proplist WHERE group_id={$gr['id']} AND property_id={$pr['id']} AND value REGEXP '^[0-9]+$'");
                    if(count($pz)>0){
                        $tmp = array();
                        foreach($pz as $ppz)
                            $tmp[] = "`{$pr['name']}`={$pr['id']}";
                        $total[] = implode(' OR ', $tmp);
                    }
                }
            }            
            if(count($total)>0){
                $total = '(' . implode(') OR (', $total) . ')';
                var_dump("SELECT si.id as id, si.title title FROM prop_{$gr['id']} prop INNER JOIN shop_item si ON si.id=prop.id WHERE $total");
                $items = X3::db()->fetchAll("SELECT si.id as id, si.title title FROM prop_{$gr['id']} prop INNER JOIN shop_item si ON si.id=prop.id WHERE $total");
                foreach ($items as $item){
                    echo '#'.$item['id']. ' - '.$item['title'] . '  <a href="/admin/shop/edit/'.$item['id'].'" target="_blank">GO</a>';
                }
            }
        }
        exit;
    }
    
    public function actionShow() {
        if(!X3::user()->isAdmin()) throw new X3_404();
        $data = X3::db()->fetchAll("SELECT s1.id AS id1,s2.id AS id2, s1.title AS title1, s2.title as title2, s1.articule a1, s2.articule a2 FROM shop_item s1 INNER JOIN shop_item s2 ON s1.title = s2.title WHERE s1.id <> s2.id AND s1.group_id=s2.group_id");
        $shown = array();
        if(!empty($data)){
        echo "<table><tr><th style=\"background:#f5f5f5;\">ID</th><th style=\"background:#f5f5f5;\">name</th><th style=\"background:#f5f5f5;\">actions</th></tr>";
        foreach($data as $a){
            if(in_array($a['id1'],$shown) || in_array($a['id2'],$shown)) continue;
            if($a['a1']!='' && $a['a2']!='' && $a['a1']!=$a['a2']) continue;
            echo "<tr>";
            echo "<td>";
            echo $a['id1'];
            echo "</td>";
            echo "<td>";
            echo $a['title1'] . "({$a['a1']})";
            echo "</td>";
            echo "<td>";
            echo "<a href=\"/admin/shop/edit/{$a['id1']}\" target=\"_blank\">EDIT1</a>&nbsp;&nbsp;";
            echo "<a href=\"/admin/shop/delete/{$a['id1']}\" target=\"_blank\">DEL1</a>";
            echo "</td>";
            echo "</tr>";
            echo "<tr>";
            echo "<td>";
            echo $a['id2'];
            echo "</td>";
            echo "<td>";
            echo $a['title2'] . "({$a['a2']})";
            echo "</td>";
            echo "<td>";
            echo "<a href=\"/admin/shop/edit/{$a['id2']}\" target=\"_blank\">EDIT2</a>&nbsp;&nbsp;";
            echo "<a href=\"/admin/shop/delete/{$a['id2']}\" target=\"_blank\">DEL2</a>";
            echo "</td>";
            echo "<tr><td colspan='3'>---------------------</td></tr>";
            $shown[] = $a['id1'];
            $shown[] = $a['id2'];
        }
        echo "</table>";
        }else{
            echo "Дублирующихся товаров не обнаружено.";
        }
        exit;
    }
    
    public function actionUnite() {
        if(!X3::user()->isAdmin()) exit;
        $id1 = (int)$_GET['id'];
        $id2 = (int)$_GET['with'];
        $prop_out = Shop_Properties::getByPk($id1);
        $with = Shop_Properties::getByPk($id2);
        if($prop_out->group_id != $with->group_id) die('ERROR#0');
        //String to string
        if($prop_out->type == $with->type && $with->type == 'string'){
            if(X3::db()->query("UPDATE shop_proplist SET property_id=$with->id WHERE property_id=$prop_out->id")){
                if(!X3::db()->query("UPDATE prop_$with->group_id SET `$with->name`=`$prop_out->name` WHERE (`$with->name`='' OR `$with->name` IS NULL) AND (`$prop_out->name`<>'' OR `$prop_out->name` IS NOT NULL)"))
                    echo X3::db()->getErrors();
                if(count(X3::db()->fetchAll("SHOW COLUMNS FROM `prop_$with->group_id` WHERE `Field`='$prop_out->name'"))!=1){
                    X3::db()->query("ALTER TABLE `prop_$with->group_id` DROP COLUMN `$prop_out->name`");
                }
            }else
                echo 'ERROR#1';
        //other types
        }elseif(($prop_out->type == $with->type) || ($prop_out->type=='integer' && $with->type=='boolean') || ($prop_out->type=='integer' && $with->type=='decimal') || ($prop_out->type=='boolean' && $with->type=='integer')
                 || ($prop_out->type=='boolean' && $with->type == 'decimal') || ($prop_out->type=='decimal' && $with->type == 'boolean') || ($prop_out->type=='decimal' && $with->type == 'integer')){
            if(!X3::db()->query("UPDATE prop_$with->group_id SET `$with->name`=`$prop_out->name` WHERE (`$with->name`='' OR `$with->name` IS NULL) AND (`$prop_out->name`<>'' OR `$prop_out->name` IS NOT NULL)"))
                echo X3::db()->getErrors();
            if(count(X3::db()->fetchAll("SHOW COLUMNS FROM `prop_$with->group_id` WHERE `Field`='$prop_out->name'"))!=1){
                X3::db()->query("ALTER TABLE `prop_$with->group_id` DROP COLUMN `$prop_out->name`");
            }
        //from string
        }elseif($prop_out->type == 'string' && ($with->type == 'integer' || $with->type == 'decimal' || $with->type == 'boolean')){
            $vals = X3::db()->fetchAll("SELECT `id`,`value` FROM shop_proplist WHERE group_id='$with->group_id' AND property_id='$prop_out->id'");
            X3::db()->startTransaction();
            foreach($vals as $val)
                X3::db()->addTransaction("UPDATE prop_$with->group_id SET `$prop_out->name`='{$val['value']}' WHERE `$prop_out->name`='{$val['id']}'");
            if(!X3::db()->commit())
                X3::log(X3::db()->getErrors(),'kansha_error');
            X3::db()->query("DELETE FROM `shop_proplist` WHERE `property_id`='$prop_out->id'");            
            if(count(X3::db()->fetchAll("SHOW COLUMNS FROM `prop_$with->group_id` WHERE `Field`='$prop_out->name'"))!=1){
                X3::db()->query("ALTER TABLE `prop_$with->group_id` DROP COLUMN `$prop_out->name`");
            }
        //to string
        }elseif($with->type == 'string' && ($prop_out->type == 'integer' || $prop_out->type == 'decimal' || $prop_out->type == 'boolean')){
            $vals = X3::db()->fetchAll("SELECT `$prop_out->name` FROM prop_{$with->group_id}");
            X3::db()->startTransaction();
            foreach($vals as $val){
                $soex = X3_String::create($val[$prop_out->name])->dmstring();
                X3::db()->addTransaction("INSERT INTO shop_proplist (`group_id`,`property_id`,`soundex`,`title`,`value`) VALUES 
                    ('{$prop_out->group_id}','$prop_out->id','$soex','{$val[$prop_out->name]}','{$val[$prop_out->name]}')");
                X3::db()->addTransaction("UPDATE prop_$with->group_id SET `$prop_out->name`=LAST_INSERT_ID() WHERE `$prop_out->name`='{$val[$prop_out->name]}'");
            }
            if(!X3::db()->commit())
                X3::log(X3::db()->getErrors(),'kansha_error');            
            elseif(count(X3::db()->fetchAll("SHOW COLUMNS FROM `prop_$with->group_id` WHERE `Field`='$prop_out->name'"))!=1){
                X3::db()->query("ALTER TABLE `prop_$with->group_id` DROP COLUMN `$prop_out->name`");
            }
        }
        if(strpos($with->synonymous,$prop_out->name.';')===false){
            $syn = trim($with->synonymous,';');
            $syn = explode(';',$syn);
            array_push($syn, $prop_out->name);
            $with->synonymous = ';' . implode(';', $syn) . ';';
            if(!$with->save())
                print_r($with->table->getErrors());
        }
        Shop_Properties::deleteByPk($prop_out->id);
        echo 'OK';            
        exit;
    }
    
    public function actionDeleteprop() {
        if(!X3::user()->isAdmin()) exit;
        $id = (int)$_GET['id'];
        $prop = Shop_Properties::getByPk($id);
        if($prop === null) exit;
        if(count(X3::db()->fetchAll("SHOW COLUMNS FROM `prop_$prop->group_id` WHERE `Field`='$prop->name'"))!=1){
            X3::db()->query("ALTER TABLE `prop_$prop->group_id` DROP COLUMN `$prop->name`");
        }else
            echo X3::db()->getErrors();
        X3::db()->query("DELETE FROM `shop_proplist` WHERE property_id=`$prop->id`");
        Shop_Properties::deleteByPk($id);
        echo 'OK';
        exit;
    }
    
    public function actionGenprop() {
        if(!X3::user()->isAdmin()) exit;
        $val = $_GET['value'];
        if($val!=''){
            echo Shop_Properties::parseKey($val);
        }
        exit;
    }
    
    public function actionLoadprop() {
        $pid = (int)$_POST['pid'];
        $gid = (int)$_POST['gid'];
        $res = X3::db()->fetchAll("SELECT id,value,title,description,status,weight FROM shop_proplist WHERE property_id='$pid' AND group_id='$gid' ORDER BY weight, title ");
        echo json_encode($res);
        exit;
    }
    
    public function actionUpdatepropname() {
        if(!X3::user()->isAdmin()) exit;
        $name = $_POST['name'];
        $id = (int)$_POST['id'];
        if(!$id>0) exit;
        $prop = Shop_Properties::getByPk($id);
        if($prop == null || $prop->name == $name) exit;
        $this->update_name($name,$prop);
        exit;
    }
    
    public function actionFuckprops2() {
        $ids = X3::db()->fetchAll("SELECT DISTINCT s1.id id, s1.group_id group_id, s1.property_id pid FROM shop_proplist s1, shop_proplist s2 WHERE s1.value=s2.value AND s1.group_id=s2.group_id AND s1.id<>s2.id");
        $del = array();
        $props = array();
        foreach ($ids as $id){
            if(!isset($props[$id['pid']]))
                $name = $props[$id['pid']] = X3::db()->fetch("SELECT * FROM shop_properties WHERE id={$id['pid']}");
            else
                $name = $props[$id['pid']];
            $p = X3::db()->fetch("SELECT COUNT(0) cnt FROM prop_{$id['group_id']} WHERE `{$name['name']}` LIKE '{$id['id']}'");
            if($p['cnt']>0){
                echo "Property #{$name['name']}-{$id['id']} > 0 at {$id['group_id']}<br/>\n";
            }elseif(!in_array ($id['id'], $del))
                $del[] = $id['id'];
        }
        if(count($del)>0){
            $ids = implode(',', $del);
            X3::db()->query("DELETE FROM shop_proplist WHERE id IN ($ids)");
            echo "YEAH!!!";
        }
        exit;
    }
    
    public function update_name($name,$prop) {
        $grp = $prop->group_id;
        $dataType = '';
        switch ($prop->type){
            case 'string':
                $dataType = "varchar(255)";
            case 'boolean':
                $dataType = "tinyint(1)";
            case 'decimal':
                $dataType = "decimal(10,2)";
            case 'integer':
                $dataType = "integer(11)";
            case 'content':
                $dataType = "text";
        }
        $dataType.=" DEFAULT NULL";
        if(X3::db()->query("ALTER TABLE prop_$grp CHANGE COLUMN `$prop->name` `$name` $dataType")){
            $prop->name = $name;
            if(!$prop->save())
                print_r ($prop->table->getErrors ());
            else
                echo 'OK';
                
        }else
            echo 'ERROR: Changing column name failed!';        
    }
    
    
    public function actionSavepg() {
        if(!X3::user()->isAdmin()) exit;
        $models = $_POST['models'];
        $id = (int)$_POST['cid'];
        $model = Shop_Group::getByPk($id);
        if($model == null) exit;
        $model->properties = json_encode($models);
        $model->save();
        //print_r($model->properties,$models);
        exit;
    }    
    
    
    public function actionSavefilters() {
        if(!X3::user()->isAdmin()) exit;
        $models = $_POST['models'];
        $id = (int)$_POST['cid'];
        $model = Shop_Group::getByPk($id);
        if($model == null) die('ERROR');
        $model->filters = json_encode($models);
        if(!$model->save()){
            print_r($model->getTable()->getErrors());
        }else{
            $file = X3::app()->basePath . '/application/cache/'.  X3_String::create($model->title)->translit().".cache";
            if(is_file($file)){
                @unlink($file);
            }            
        }
        exit;
    }    
    
    public function actionDelpropval() {
        if(!X3::user()->isAdmin() || !isset($_POST['pid']) || !$_POST['pid']>0 || !IS_AJAX) die('forbiden');
        $id = (int)$_POST['pid'];
        $prop = Shop_Proplist::getByPk($id);
        $pname = Shop_Properties::getByPk($prop->property_id);
        if($prop == null) die('no prop');
        
        if(X3::db()->query("UPDATE prop_$prop->group_id SET `$pname->name`=NULL WHERE `$pname->name`='$prop->id'"))
            Shop_Proplist::deleteByPk($id);
        else
            die(X3::db()->getErrors());
        echo 'OK';
    }
    
    public function actionCheckitems() {
        if(!X3::user()->isAdmin()) exit;
        $id = (int)$_GET['pid'];
        $proplist = Shop_Proplist::getByPk($id);
        if($proplist == null) exit;
        $props = Shop_Properties::getProp("prop_$proplist->group_id");
        $found = array();
        foreach ($props as $p){
            foreach ($p->_fields as $name=>$f){
                if($name == 'id') 
                    continue;
                if((strpos($f[0],'string')!==false || strpos($f[0],'content')!==false) && $p->$name == $id)
                    $found[] = $p->id;
            }
        }
        if(!empty($found)){
            $ids = implode(',',$found);
            $models = X3::db()->fetchAll("SELECT id,title FROM shop_item WHERE id IN ($ids)");
            if(IS_AJAX)
                echo json_encode($models);
            else{
                $the_prop = X3::db()->fetch("SELECT * FROM shop_properties WHERE id=$proplist->property_id");
                echo "<h1>Товары использующие значение свойства <i>{$the_prop['label']}</i> '$proplist->value':</h1>";
                echo '<ul>';
                foreach ($models as $model){
                    echo "<li><a href=\"/admin/shop/edit/{$model['id']}\" target=\"_blank\">{$model['title']}</a></li>";
                }
                echo '</ul>';
            }
        }else
            if(IS_AJAX)
                echo '[]';                
        exit;
    }
    
    /**
     * Rewrite empty string values '' to a NULL
     * There where empty string values sometimes. So there where a need of 
     * such function.
     * @throws X3_404
     */
    public function actionFuckprops() {
        if(!X3::user()->isAdmin()) throw new X3_404;
        $props = X3::db()->fetchAll("SELECT * FROM shop_proplist WHERE value='' GROUP BY property_id ORDER BY group_id");
        if(empty($props)) exit;
        X3::db()->startTransaction();
        foreach($props as $prop){
            $gid = $prop['group_id'];
            $pname = X3::db()->fetch("SELECT * FROM shop_properties WHERE id={$prop['property_id']} AND group_id=$gid");
            X3::db()->addTransaction("UPDATE prop_{$gid} SET `{$pname['name']}`=NULL WHERE `{$pname['name']}`={$prop['id']}");
        }
        if(X3::db()->commit()){
            $props = X3::db()->query("DELETE FROM shop_proplist WHERE value=''");
            echo 'YEAH!';
        }
        exit;
    }
    
    public function actionCheckprops() {
        $prs = array();
        $props = X3::db()->fetchAll("SELECT id,label FROM shop_properties");
        foreach ($props as $prop)
            $prs[$prop['id']] = $prop['label'];
        $ps = X3::db()->fetchAll("SELECT * FROM shop_proplist WHERE `value` REGEXP '^[0-9]+$'");
        $gs = array();
        $grs = X3::db()->fetchAll("SELECT id,title FROM shop_group");
        foreach ($grs as $g)
            $gs[$g['id']] = array('title'=>$g['title'],'values'=>array());
        
        foreach($ps as $p){
            $a = X3::db()->fetch("SELECT * FROM shop_proplist WHERE id='{$p['value']}'");
            if(!empty($a))
                $val = $prs[$p['property_id']].' <i>('.$a['value'].')</i>';
            else
                $val = $prs[$p['property_id']];
            if(!in_array($val, $gs[$p['group_id']]['values']))
                $gs[$p['group_id']]['values'][] = $val;
        }
        $this->template->layout = null;
        $this->template->render("checkprops",array('models'=>$gs));
    }
    
    public function actionFixprops() {
        $groups = X3::db()->fetchAll('SELECT id,title FROM shop_group');
        foreach($groups as $g){
            $shop = X3::db()->fetchAll("SELECT * FROM prop_{$g['id']}");
            $props = X3::db()->fetchAll("SELECT * FROM shop_properties WHERE group_id={$g['id']}");
            foreach($props as $prop){
                if($prop['type']=='string'){
                    foreach($shop as $sh){
                        $p = X3::db()->fetch("SELECT id, value FROM shop_proplist WHERE group_id={$g['id']} AND property_id={$prop['id']} AND id={$sh[$prop['name']]}");
                        if(empty($p)){
                            $p = X3::db()->fetch("SELECT * FROM shop_proplist WHERE id={$sh[$prop['name']]}");
                            if(!empty($p)){
                                echo $g['title'] . ' - ' . $sh['id'].' '.$prop['name'].' '.$p['id'].'->'.$p['value'].'<br/>';
                                continue;
                                unset($p['id']);
                                $p['property_id'] = $prop['id'];
                                $p['group_id'] = $g['id'];
                                $sp = new Shop_Proplist;
                                $sp->table->acquire($p);
                                if($sp->save()){
                                    if(!X3::db()->query("UPDATE prop_{$g['id']} SET `{$prop['name']}`=$sp->id WHERE `{$prop['name']}`={$sh[$prop['name']]}"))
                                    ;
                                }else
                                    echo "<div>{$g['title']} - {$prop['label']}({$p['value']}) FAIL!</div>";
                            }
                        }
                    }
                }
            }
        }
        exit;
    }
    
    public function actionGetpropsfor() {
        $subaction = $_GET['subaction'];
        $value = $_GET['value'];
        if($subaction=='edit' || $subaction=='clone' || $value=='togroup'):
        if($subaction=='edit' || $subaction=='clone'):
            $modules = Shop_Item::getByPk($value);
            if($modules == null) return 0;
            $G = Shop_Group::getByPk($modules->group_id);
            $_props = X3::db()->query("SELECT * FROM shop_properties WHERE group_id=$G->id ORDER BY weight");//Shop_Properties::get(array('@order'=>'weight','@condition'=>array('group_id'=>$G->id)),0,null,1);
            $props = array('image'=>array('1'=>$modules->image),'group_id'=>$modules->group_id,
                'group_title'=>$G->title,'articule'=>$modules->articule,'item_id'=>$modules->id,
                'title'=>  stripslashes($modules->title),'modelid'=>'','hid'=>'','manname'=>$modules->manufacturer->name,'isnew'=>$modules->isnew,'istop'=>$modules->istop,
                'model_name'=>stripslashes($modules->model_name));
            $shop = $modules->getProperties();
            if(empty($shop)){
                return json_encode($props);
                exit;
            }
            $values = $shop->fetchValues($modules->group_id);
            $fields = $shop->_fields;
        elseif($value=='togroup'):
            $id = (int)$_GET['id'];
            $G = Shop_Group::getByPk($id);
            $_props = $_props = X3::db()->query("SELECT * FROM shop_properties WHERE group_id=$G->id ORDER BY weight");//Shop_Properties::get(array('@order'=>'weight','@condition'=>array('group_id'=>$G->id)),0,null,1);
            $shop = new Shop_Properties('prop_'.$id);
            $fields = $shop->_fields;
            $values = array_combine(array_keys($fields), array_fill(0, count($fields), ''));
            //var_dump($values);
            $props = array('image'=>array('1'=>''),'group_id'=>$G->id,
                'group_title'=>$G->title,'articule'=>'','item_id'=>0,'title'=>'','modelid'=>'','hid'=>'','isnew'=>0,'istop'=>0);
        endif;
            while($p = mysql_fetch_assoc($_props)){
                $k = $p['name'];
                if(!isset($fields[$k])) continue;
                $props[$k]['label']=$p['label'];
                $props[$k]['original']=$k;
                try{
                    $props[$k]['value']=(isset($values[$k])?$values[$k]:$shop->$k);
                }catch(Exception $e){
                    echo $k . "\n";
                    echo (int)isset($shop->_fields[$k])."\n";
                    //var_dump($e);
                    exit;
                }
                $props[$k]['type']=$p['type'];//array_shift($fields[$k]);
                $props[$k]['name']=$k;
                $props[$k]['status']=$p['status'];
                $props[$k]['weight']=$p['weight'];
                $props[$k]['isnew']=0;
            }        
        echo json_encode($props);
        endif;
        if(IS_AJAX)
            exit;
    }
    
    public function actionGetprices() {
        $id = (int)$_GET['for'];
        $models = Company_Item::get(array('item_id'=>$id));
        $res = array();
        foreach($models as $model){
            $res[] = array(
                'id'=>$model->id,
                'idc'=>$model->company_id,
                'title'=>  Company::getByPk($model->company_id)->title,
                'price'=>$model->price,
                'url'=>$model->url,
                'cid'=>$model->client_id,
            );
        }
        echo json_encode($res);
        exit;
    }
    
    public function actionDiff() {
        $diff = 0.3;
        $qgr = "";
        $ig = 0;
        if(isset($_GET['koef'])){
            $diff = (int)$_GET['koef']/100;
        }
        if(isset($_GET['group'])){
            $qgr = " AND `group_id`=".$_GET['group'];
            $ig = $_GET['group'];
        }
        $items = X3::db()->query("SELECT DISTINCT si.id, si.title, (SELECT (MAX(price)-MIN(price))/MAX(price) FROM company_item ci WHERE ci.item_id=si.id) AS diff, 
           (SELECT MAX(price) FROM company_item ci WHERE ci.item_id=si.id) AS `max`, (SELECT MIN(price) FROM company_item ci WHERE ci.item_id=si.id) AS `min` FROM shop_item si WHERE (SELECT (MAX(price)-MIN(price))/MAX(price) FROM company_item ci WHERE ci.item_id=si.id)>$diff $qgr");
        echo(X3::db()->getErrors());
        echo '<form method="GET"><input name="koef" value="'.(isset($_GET['koef'])?$_GET['koef']:'30').'" />%';
        echo '<select name="group"><option value="0">Все</option>';
        $grps = Shop_Group::get();
        foreach($grps as $g){
            if($ig == $g->id)
            echo '<option selected="selected" value="'.$g->id.'">'.$g->title.'</option>';
            else
            echo '<option value="'.$g->id.'">'.$g->title.'</option>';
        }
        echo '</select><input type="submit" value="Обновить" /></form><hr/>';
        echo '<table width="100%"><tr><td>Наименование</td><td>Min</td><td>Max</td><td>Diff</td></tr>';
        while($item = mysql_fetch_assoc($items)){
            echo '<tr><td><a href="/admin/shop/edit/'.$item['id'].'" target="_blank">'.$item['title'].'</a></td><td>'.$item['min'].'</td><td>'
                    .$item['max'].'</td><td>'.($item['diff']*100).'%</td></tr>';
        }
        echo '</table>';
        exit;
    }
    
    public function actionFaileditems() {
        $qg = X3::db()->query("SELECT id,title FROM shop_group ORDER BY title");
        echo '<table>';
        while($gr = mysql_fetch_assoc($qg)){
            $qi = X3::db()->query("SELECT id, title FROM shop_item WHERE id NOT IN (SELECT si.id FROM shop_item si INNER JOIN prop_{$gr['id']} p ON p.id=si.id WHERE si.group_id={$gr['id']}) AND group_id={$gr['id']}");
            if(mysql_num_rows($qi)==0) continue;
            echo '<tr><td colspan="2"><b>'.$gr['title'].'</b></td></tr>';
            while($is = mysql_fetch_assoc($qi)){
                echo '<tr><td><a href="/admin/shop/edit/'.$is['id'].'">'.$is['id'].'</a></td><td>'.$id['title'].'</td></tr>';
            }
        }
        echo '</table>';
        exit;
    }
    /**
     * Made to find broken properties
     * Like double to string made strange substitution to Core i5
     */
    public function actionBroken() {
        $type = 'numeric';
        if(isset($_GET['type']))
            $type = $_GET['type'];
        $groups = X3::db()->query("SELECT id, title FROM shop_group");
        $similar = array();
        while($g = mysql_fetch_assoc($groups)){
            $prs = X3::db()->query("SELECT id,name,label FROM shop_properties WHERE group_id={$g['id']}");
            if(mysql_num_rows($prs)>0)
            while($pr = mysql_fetch_assoc($prs)){
                $prv = X3::db()->query("SELECT id,title FROM shop_proplist WHERE group_id={$g['id']} AND property_id={$pr['id']}");
                $count = mysql_num_rows($prv);
                if($count>0){
                    $nonsimilar = 0;
                    while($pv = mysql_fetch_assoc($prv)){
                        if(($type == 'numeric' && !is_numeric($pv['title'])) || ($type == 'string' && is_numeric($pv['title'])))
                            $nonsimilar++;
                    }
                    if($nonsimilar>0 && $nonsimilar/$count<0.2){
                        echo "Group {$g['title']} and property {$pr['id']}#{$pr['label']}\n\n<br/>";
                    }
                }
            }
            
        }
        exit;
    }
    
    /**
     * Converts properties data type
     */
    public function actionConvert() {
        //preparing undo file
        $filename = X3::app()->basePath . '/dump';
        if(!is_dir($filename) || !is_writable($filename))
            die('Undo file could not be written! Dump directory either not allowed to write or does not exists! '.$filename);
        $filename .= '/lastprop.undo';
        if(isset($_GET['undo'])){
            if(!is_file($filename))
                die("No undo file found! Sorry...");
            $undo = file_get_contents($filename);
            $undo = json_decode($undo,true);
            if($undo == null)
                die('Something wrong with undo file so it can not be read. Sorry...');
            
            if($_GET['undo']=='skip' || !X3::db()->query($undo['convert']))
                die("Can not restore old data type. Check the following sql and do it manualy and then proceed with <a href=\"/shop/convert/undo/skip\">this link to skip</a><hr/><i>'{$undo['convert']}'</i><hr/>");
            $query = new X3_MySQL_Query('shop_proplist');
            X3::db()->startTransaction();    
            foreach($undo['data'] as $i=>$data){
                if(!empty($data)){
                    $sql = $query->insert($data)->buildSQL();
                    //echo "$sql<br/>";
                    X3::db()->addTransaction($sql);
                }
                X3::db()->addTransaction($undo['queries'][$i]);
            }
            if(!X3::db()->commit()){
                X3::db()->rollback();
                die(X3::db()->getErrors());
            }else
                echo 'Ok!';
            exit;
        }
        if(!isset($_GET['pid']) || !$_GET['pid']>0 || !isset($_GET['to']) || !in_array($_GET['to'],array('string','integer','boolean','decimal')))
            die('Wrong data type');
        $pid = $_GET['pid'];
        $to = $_GET['to'];
        $undo = array('convert'=>'','data'=>array(),'queries'=>array());
        if(!in_array($to,array('string','boolean','decimal','integer','content')))
                die('wrong data type');
        $prop = X3::db()->fetch("SELECT id, type, name, group_id, label FROM shop_properties WHERE id={$pid}");
        if(!$prop)
            die(X3::db()->getErrors());
        //from string
        if($prop['type']=='string'){
            if($to == 'string') exit;
            $plist = X3::db()->query("SELECT * FROM shop_proplist WHERE group_id={$prop['group_id']} AND property_id={$pid}") or die('[]');
            X3::db()->startTransaction();
            X3::db()->addTransaction("UPDATE shop_properties SET `type`='$to' WHERE id={$pid}");
            if($to == 'string')
                X3::db()->addTransaction("ALTER TABLE `prop_{$prop['group_id']}` MODIFY `{$prop['name']}` VARCHAR(255) NULL CHARACTER SET utf8");
            else if($to == 'decimal')
                X3::db()->addTransaction("ALTER TABLE `prop_{$prop['group_id']}` MODIFY `{$prop['name']}` DECIMAL(10,2) NULL");
            else if($to == 'integer')
                X3::db()->addTransaction("ALTER TABLE `prop_{$prop['group_id']}` MODIFY `{$prop['name']}` INT NULL");
            else if($to == 'boolean')
                X3::db()->addTransaction("ALTER TABLE `prop_{$prop['group_id']}` MODIFY `{$prop['name']}` TINYINT(1) NULL");
            if($prop['type'] == 'string')
                $undo['queries'][] = "ALTER TABLE `prop_{$prop['group_id']}` MODIFY `{$prop['name']}` VARCHAR(255) NULL CHARACTER SET utf8";
            else if($prop['type'] == 'decimal')
                $undo['queries'][] = "ALTER TABLE `prop_{$prop['group_id']}` MODIFY `{$prop['name']}` DECIMAL(10,2) NULL";
            else if($prop['type'] == 'integer')
                $undo['queries'][] = "ALTER TABLE `prop_{$prop['group_id']}` MODIFY `{$prop['name']}` INT NULL";
            else if($prop['type'] == 'boolean')
                $undo['queries'][] = "ALTER TABLE `prop_{$prop['group_id']}` MODIFY `{$prop['name']}` TINYINT(1) NULL";
            $undo['data'][] = array();
            $undo['convert'] = "UPDATE shop_properties SET `type`={$prop['type']} WHERE id={$pid}";
            while($p = mysql_fetch_assoc($plist)){
                if($to == 'decimal' || $to == 'integer' || $to == 'boolean'){
                    $go = false;
                    //some times (havent figured why yet) properties acqure wrong, string, values
                    if($p['value']!==null && !is_numeric($p['value'])){
                        $zz = X3::db()->fetch("SELECT MIN(id) real_id FROM shop_proplist WHERE `value` LIKE '{$p['value']}'");
                        if($to == 'decimal')
                            $val = (double)$zz['real_id'];
                        if($to == 'integer')
                            $val = (int)$zz['real_id'];
                        if($to == 'boolean')
                            $val = (int)$zz['real_id']>0?1:0;
                        $go = true;
                    }else
                        if($to == 'decimal')
                            $val = (double)$p['value'];
                        if($to == 'integer')
                            $val = (int)$p['value'];
                        if($to == 'boolean')
                            $val = (int)$p['value']>0?1:0;
                    if($val == 0)
                        $val = 'NULL';
                    X3::db()->addTransaction("UPDATE prop_{$prop['group_id']} SET `{$prop['name']}`='{$val}' WHERE `{$prop['name']}` LIKE '{$p['id']}'");
                    $undo['queries'][] = "UPDATE prop_{$prop['group_id']} SET `{$prop['name']}`='{$p['id']}' WHERE `{$prop['name']}` LIKE '{$val}'";
                    $undo['data'][] = $p;
                    if($go)
                        $p['value'] = '<b style="color:red">' . $p['value'] . '</b>';
                    //echo "Converting <b>{$prop['label']}</b> converting from <em>'{$p['value']}'</em> to <em>'$val'</em><br/>";
                }

            }
            X3::db()->addTransaction("DELETE FROM shop_proplist WHERE group_id={$prop['group_id']} AND property_id={$pid}");
            if(!X3::db()->commit()){
                X3::db()->rollback();
                die(X3::db()->getErrors());
            }
            @file_put_contents($filename, json_encode($undo));
        //to string
        }else if($to=='string'){
            if($to == $prop['type']) exit;
            X3::db()->startTransaction();
            X3::db()->addTransaction("UPDATE shop_properties SET `type`='$to' WHERE id={$pid}");         
            if($to == 'string')
                X3::db()->addTransaction("ALTER TABLE `prop_{$prop['group_id']}` MODIFY `{$prop['name']}` VARCHAR(255) NULL CHARACTER SET utf8");
            else if($to == 'decimal')
                X3::db()->addTransaction("ALTER TABLE `prop_{$prop['group_id']}` MODIFY `{$prop['name']}` DECIMAL(10,2) NULL");
            else if($to == 'integer')
                X3::db()->addTransaction("ALTER TABLE `prop_{$prop['group_id']}` MODIFY `{$prop['name']}` INT NULL");
            else if($to == 'boolean')
                X3::db()->addTransaction("ALTER TABLE `prop_{$prop['group_id']}` MODIFY `{$prop['name']}` TINYINT(1) NULL");
            if($prop['type'] == 'string')
                $undo['queries'][] = "ALTER TABLE `prop_{$prop['group_id']}` MODIFY `{$prop['name']}` VARCHAR(255) NULL CHARACTER SET utf8";
            else if($prop['type'] == 'decimal')
                $undo['queries'][] = "ALTER TABLE `prop_{$prop['group_id']}` MODIFY `{$prop['name']}` DECIMAL(10,2) NULL";
            else if($prop['type'] == 'integer')
                $undo['queries'][] = "ALTER TABLE `prop_{$prop['group_id']}` MODIFY `{$prop['name']}` INT NULL";
            else if($prop['type'] == 'boolean')
                $undo['queries'][] = "ALTER TABLE `prop_{$prop['group_id']}` MODIFY `{$prop['name']}` TINYINT(1) NULL";
            $undo['data'][] = array();
            $undo['convert'] = "UPDATE shop_properties SET `type`='{$prop['type']}' WHERE id={$pid}";
            $models = X3::db()->query("SELECT `{$prop['name']}` FROM prop_{$prop['group_id']}");
            $inserted = array();
            while($model = mysql_fetch_assoc($models)){
                $key = "value".$model[$prop['name']];
                if($model[$prop['name']]!='NULL' && $model[$prop['name']]!=null && !isset($inserted[$key])){
                    $soex = X3_String::create($model[$prop['name']])->dmstring();
                    X3::db()->query("INSERT INTO shop_proplist (`property_id`, `group_id`, `value`, `title`, `soundex`) VALUES ('{$prop['id']}','{$prop['group_id']}','{$model[$prop['name']]}','{$model[$prop['name']]}','{$soex}')",false);
                    $inserted[$key] = $iid = mysql_insert_id();
                    X3::db()->addTransaction("UPDATE prop_{$prop['group_id']} SET `{$prop['name']}`='$iid' WHERE `{$prop['name']}` LIKE '{$model[$prop['name']]}'");
                    $undo['queries'][] = "UPDATE prop_{$prop['group_id']} SET `{$prop['name']}`='{$model[$prop['name']]}' WHERE `{$prop['name']}` LIKE '$iid'";
                    $undo['data'][] = array();            
                }
            }
            $undo['queries'][] = "DELETE FROM shop_proplist WHERE group_id={$prop['group_id']} AND property_id={$pid}";
            $undo['data'][] = array();            
            if(!X3::db()->commit()){
                X3::db()->rollback();
                die(X3::db()->getErrors());
            }
            @file_put_contents($filename, json_encode($undo));
        //other data types
        }else {
            if($to == $prop['type']) exit;
            X3::db()->startTransaction();
            X3::db()->addTransaction("UPDATE shop_properties SET `type`='$to' WHERE id={$pid}");
            if($to == 'string')
                X3::db()->addTransaction("ALTER TABLE `prop_{$prop['group_id']}` MODIFY `{$prop['name']}` VARCHAR(255) NULL CHARACTER SET utf8");
            else if($to == 'decimal')
                X3::db()->addTransaction("ALTER TABLE `prop_{$prop['group_id']}` MODIFY `{$prop['name']}` DECIMAL(10,2) NULL");
            else if($to == 'integer')
                X3::db()->addTransaction("ALTER TABLE `prop_{$prop['group_id']}` MODIFY `{$prop['name']}` INT NULL");
            else if($to == 'boolean')
                X3::db()->addTransaction("ALTER TABLE `prop_{$prop['group_id']}` MODIFY `{$prop['name']}` TINYINT(1) NULL");
            if($prop['type'] == 'string')
                $undo['queries'][] = "ALTER TABLE `prop_{$prop['group_id']}` MODIFY `{$prop['name']}` VARCHAR(255) NULL CHARACTER SET utf8";
            else if($prop['type'] == 'decimal')
                $undo['queries'][] = "ALTER TABLE `prop_{$prop['group_id']}` MODIFY `{$prop['name']}` DECIMAL(10,2) NULL";
            else if($prop['type'] == 'integer')
                $undo['queries'][] = "ALTER TABLE `prop_{$prop['group_id']}` MODIFY `{$prop['name']}` INT NULL";
            else if($prop['type'] == 'boolean')
                $undo['queries'][] = "ALTER TABLE `prop_{$prop['group_id']}` MODIFY `{$prop['name']}` TINYINT(1) NULL";
            $undo['data'][] = array();            
            $undo['convert'] = "UPDATE shop_properties SET `type`='{$prop['type']}' WHERE id={$pid}";
            $models = X3::db()->query("SELECT `{$prop['name']}` FROM prop_{$prop['group_id']}");
            while($model = mysql_fetch_assoc($models)){
                if($to == 'integer'){
                    $val = (int)$model[$prop['name']];
                }else
                if($to == 'decimal'){
                    $val = (double)$model[$prop['name']];
                }else
                if($to == 'boolean'){
                    $val = (((int)$model[$prop['name']])>0?1:0);
                }
                X3::db()->addTransaction("UPDATE prop_{$prop['group_id']} SET `{$prop['name']}`='$val' WHERE `{$prop['name']}` LIKE '{$model[$prop['name']]}'");
                $undo['queries'][] = "UPDATE prop_{$prop['group_id']} SET `{$prop['name']}`='{$model[$prop['name']]}' WHERE `{$prop['name']}` LIKE '$val'";
                $undo['data'][] = array();
            }
            if(!X3::db()->commit()){
                X3::db()->rollback();
                die(X3::db()->getErrors());
            }
            @file_put_contents($filename, json_encode($undo));
        }
        exit('OK! Проверте результаты и если надо отмените действие - <a href="/shop/convert/undo/1" target="_blank">отменить</a>');
    }
}

?>
