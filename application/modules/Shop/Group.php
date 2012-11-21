<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of News
 *
 * @author Soul_man
 */
class Shop_Group extends X3_Module_Table {

    public $encoding = 'UTF-8';
    public $tableName = 'shop_group';
    public $prop = null;
    public $manuf = null;
    public $header = false;
    
    public $_fields = array(
        'id'=>array('integer[10]','unsigned','primary','auto_increment'),
        'category_id'=>array('string[255]','default'=>''),
        'title'=>array('string[255]'),
        'text'=>array('text','default'=>'NULL'),
        'properties'=>array('text','default'=>'NULL'),
        'filters'=>array('text','default'=>'[]'),
        'suffix'=>array('string[512]','default'=>'NULL'),
        'short_desc'=>array('string[512]','default'=>'NULL'),
        'status'=>array('boolean','default'=>'1'),
        'usename'=>array('boolean','default'=>'1'),
        'bbracket'=>array('boolean','default'=>'0'),
        'status'=>array('boolean','default'=>'1'),
        'weight'=>array('integer[5]','unsigned','default'=>'0'),
        'created_at'=>array('integer[10]','unsigned','default'=>'0'),
        'metaheader'=>array('string[512]','default'=>'[group] [name]'),
        'metatitle'=>array('string','default'=>'[name] [articule]'),
        'metakeywords'=>array('content','default'=>'[name] [articule], [name], [articule]'),
        'metadescription'=>array('content','default'=>''),
        'priceheader'=>array('string[512]','default'=>'[group] [name]'),
        'pricetitle'=>array('string','default'=>'[name] [articule]'),
        'pricekeywords'=>array('content','default'=>'[name] [articule], [name], [articule]'),
        'pricedescription'=>array('content','default'=>'[name] [articule]'),
        'feedheader'=>array('string[512]','default'=>'[group] [name]'),
        'feedtitle'=>array('string','default'=>'[name] [articule]'),
        'feedkeywords'=>array('content','default'=>'[name] [articule], [name], [articule]'),
        'feeddescription'=>array('content','default'=>'[name] [articule]'),
        'servheader'=>array('string[512]','default'=>'[group] [name]'),
        'servtitle'=>array('string','default'=>'[name] [articule]'),
        'servkeywords'=>array('content','default'=>'[name] [articule], [name], [articule]'),
        'servdescription'=>array('content','default'=>'[name] [articule]'),
        'allheader'=>array('string[512]','default'=>'[group] [name]'),
        'alltitle'=>array('string','default'=>''),
        'allkeywords'=>array('content','default'=>''),
        'alldescription'=>array('content','default'=>''),
    );
    
    public function fieldNames() {
        return array(
            'title'=>'Название',
            'category_id'=>'Категория',
            'text'=>'Описание',
            'suffix'=>'Суффикс товара',
            'short_desc'=>'Краткое описание',
            'status'=>'Видимость',
            'usename'=>'Имя с граббера',
            'bbracket'=>'Имя до скобок',
            'weight'=>'Порядок',
            'metaheader'=>'Заголовок H1',
            'metatitle'=>'metatitle',
            'metadescription'=>'metadescription',
            'metakeywords'=>'metakeywords',
            'priceheader'=>'Заголовок H1',
            'pricetitle'=>'metatitle',
            'pricedescription'=>'metadescription',
            'pricekeywords'=>'metakeywords',
            'feedheader'=>'Заголовок H1',
            'feedtitle'=>'metatitle',
            'feeddescription'=>'metadescription',
            'feedkeywords'=>'metakeywords',
            'servheader'=>'Заголовок H1',
            'servtitle'=>'metatitle',
            'servdescription'=>'metadescription',
            'servkeywords'=>'metakeywords',
            'allheader'=>'Заголовок H1',
            'alltitle'=>'metatitle',
            'alldescription'=>'metadescription',
            'allkeywords'=>'metakeywords',
        );
    }
    
    public function moduleTitle() {
        return 'Группы';
    }
    
    public static function getLink($gid,$pid=null,$gtitle=false,$ptitle=false) {
        if($gid===0) return false;
        if($pid > 0){
            if($ptitle===false){
                $prop = X3::db()->fetch("SELECT title FROM shop_proplist spl WHERE spl.id=$pid LIMIT 1");
                if($prop == null){
                    return false;
                }
                $ptitle = $prop['title'];
            }
            $link = strtolower(X3_String::create($ptitle)->translit(false,"'")).'-group'.$gid.'-p'.$pid;
        }else{
            if($gtitle === false){
                $g = X3::db()->fetch("SELECT title FROM shop_group WHERE id=$gid");
                $gtitle = $g['title'];
            }
            $link = strtolower(X3_String::create($gtitle)->translit(false,"'")).'-group'.$gid;
        }
        return $link;
    }
        
    public function _setCategory_id($val) {
        if(is_array($val)){
            $this->table->setAttribute('category_id',implode(',', $val));
        }elseif(is_string ($val)){
            $this->table->setAttribute('category_id',$val);
        }
    }

    public function cache(){
        return array(
        //    'cache'=>array('actions'=>'index','role'=>'*','expire'=>'+2 minutes'),
        );
    }
    
    public function countGoods() {
        $cnt = X3::db()->fetch("SELECT COUNT(0) AS `c` FROM shop_item WHERE group_id=$this->id");
        return $cnt['c'];
    }
        
    public function prepareMeta($model,$prefix="meta") {
        $item = $model->getProperties();
        $props = X3::db()->fetchAll("SELECT * FROM shop_properties WHERE group_id=$this->id");
        $metatitle = "{$prefix}title";
        $metakeywords = "{$prefix}keywords";
        $metadescription = "{$prefix}description";
        $metaheader = "{$prefix}header";
        $mt = $this->$metatitle;
        $mk = $this->$metakeywords;
        $md = $this->$metadescription;
        $mh = $this->$metaheader;
        $ms = array(&$mt,&$mk,&$md,&$mh);
        foreach($props as $prop){
            $m = array();
            foreach($ms as &$mz){
                if(preg_match("/\[([@!]{0,2}){$prop['id']}\]/",$mz,$m)>0){
                    if($prop['type'] == 'string' || $prop['type'] == 'content'){
                        $val = X3::db()->fetch("SELECT value, title FROM shop_proplist WHERE id={$item->{$prop['name']}}");
                        $val = $val['title']!=''?$val['title']:$val['value'];
                    }elseif($prop['type']=='decimal'){
                        //$mm = mb_substr($prop['label'],$i=mb_strrpos($prop['label'], '(')+1,mb_strrpos($prop['label'], ')')-$i);
                        //$val = $item->{$prop['name']}>0?preg_replace("/[0]+$/", "",$item->{$prop['name']})."0 $mm":'';
                        $val = $item->{$prop['name']}>0?X3_String::create($item->{$prop['name']})->format("%.02f"):'';
                    }elseif($prop['type'] == 'boolean'){
                        $val = ($item->{$prop['name']}==1)?$prop['label']:'';
                    }else{
                        //$mm = mb_substr($prop['label'],$i=mb_strrpos($prop['label'], '(')+1,mb_strrpos($prop['label'], ')')-$i);
                        //$val = $item->{$prop['name']}>0?trim((int)$item->{$prop['name']}) . " $mm":"";
                        $val = $item->{$prop['name']}>0?trim((int)$item->{$prop['name']}):"";
                    }
                    if(!empty($m) && $m[1]!=''){
                        if(strpos($m[1], '!')!==false){
                            $ml = trim(SysSettings::getValue('Shop_Group.Multiword','content','Единственные числа',null,''),"\r\n ");
                            $ml = explode("\n",$ml);
                            foreach($ml as $Mm){
                                $Mm = trim($Mm,"\r\n\ ");
                                $Mm = array_map(function($item){return $item;},explode('=',$Mm));
                                $val = str_replace($Mm[0],$Mm[1],$val);
                            }
                            if(preg_match("/[иы]$/", $val)>0){
                                $val = mb_substr($val, 0,-1,X3::app()->encoding);
                            }
                        }
                        if(strpos($m[1], '@')!==false){
                            $val = X3_String::create($val)->toLowerCase();
                        }
                    }
                    $mz = str_replace($m[0], $val, $mz);
                }
            }
        }
        foreach ($ms as &$mz){
            $m = array();
            if(preg_match_all("/\[([@!]{0,2})(group|manufacturer|name|articule)\]/",$mz,$m)>0){
                foreach($m[0] as $i=>$mx){
                    if(isset($m[2][$i]) && $m[2][$i] == 'group'){
                        $val = $model->getGroup()->title;
                    }elseif(isset($m[2][$i]) && $m[2][$i] == 'manufacturer'){
                        $val = $model->manufacturerTitle();
                    }elseif(isset($m[2][$i]) && $m[2][$i] == 'name'){
                        $val = $model->title;
                    }elseif(isset($m[2][$i]) && $m[2][$i] == 'articule'){
                        $val = $model->articule!=''?'('.$model->articule.')':'';
                    }else
                        $val = '';
                    if(!empty($m) && $m[1][$i]!=''){
                        if(strpos($m[1][$i], '!')!==false){
                            $ml = trim(SysSettings::getValue('Shop_Group.Multiword','content','Единственные числа',null,''),"\r\n ");
                            $ml = explode("\n",$ml);
                            foreach($ml as $Mm){
                                $Mm = trim($Mm,"\r\n\ ");
                                $Mm = array_map(function($item){return $item;},explode('=',$Mm));
                                $val = str_replace($Mm[0],$Mm[1],$val);
                            }
                            if(preg_match("/[иы]$/", $val)>0){
                                $val = mb_substr($val, 0,-1,X3::app()->encoding);
                            }
                        }
                        if(strpos($m[1][$i], '@')!==false){
                            $val = X3_String::create($val)->toLowerCase();
                        }
                    }
                    $mz = str_replace($mx, $val, $mz);
                }
            }
            $m = array();
            //echo $mz . '<br/>';
            $mz = Shop_Item::condition($mz);
        }
        $model->metatitle = str_replace("  "," ",$mt);
        $model->metakeywords = str_replace("  "," ",$mk);
        $model->metadescription = str_replace("  "," ",$md);
        $model->header = $mh;
    }
    
    public function prepareGeneralMeta($filters) {
        $mt = $this->alltitle;
        $mk = $this->allkeywords;
        $md = $this->alldescription;
        $mh = $this->allheader;
        $manuf = '';
        if(X3::app()->group>0){
            $manuf = Manufacturer::getByPk(X3::app()->group)->title;        
        }elseif(X3::user()->{"manu_$this->id"}!=null && count(X3::user()->{"manu_$this->id"})==1)
            $manuf = Manufacturer::getByPk(X3::user()->{"manu_$this->id"}[0])->title;        
        $ms = array(&$mt,&$mk,&$md);
        if($filters != null && !empty($filters)){
            $props = X3::db()->fetchAll("SELECT * FROM shop_properties WHERE group_id=$this->id");
            foreach($props as $prop){
                $m = array();
                foreach ($ms as &$mz)
                if(preg_match("/\[([@!]{0,2}){$prop['id']}\]/",$mz,$m)>0){
                    if(($prop['type'] == 'string' || $prop['type'] == 'content')){
                        if(is_array($filters['string'][$prop['name']])){
                            $vals = X3::db()->fetchAll("SELECT value, title FROM shop_proplist WHERE id IN (".implode(',',$filters['string'][$prop['name']]).")");
                            $val = array();
                            foreach($vals as $v)
                                $val[] = $v['title']!=''?$v['title']:$v['value'];
                            $val = implode(', ', $val);
                        }else{
                            $val = X3::db()->fetch("SELECT value, title FROM shop_proplist WHERE id={$filters['string'][$prop['name']]}");
                            $val = $val['title']!=''?$val['title']:$val['value'];
                        }
                    }elseif($prop['type']=='decimal'){
                        //$mm = mb_substr($prop['label'],$i=mb_strrpos($prop['label'], '('),mb_strrpos($prop['label'], ')')-$i);
                        //$val = preg_replace("/[0]+$/", "",$filters['scalar'][$prop['name']])."0 $mm";
                        $val = X3_String::create($filters['scalar'][$prop['name']])->format("%.02f");
                    }elseif($prop['type'] == 'boolean'){
                        $val = ($item->{$prop['name']}==1)?$prop['label']:'';
                    }else{
                        //$mm = mb_substr($prop['label'],$i=mb_strrpos($prop['label'], '('),mb_strrpos($prop['label'], ')')-$i);
                        //$val = $filters['scalar'][$prop['name']] . " $mm";
                        $val = $filters['scalar'][$prop['name']];
                    }
                    if(!empty($m) && $m[1]!=''){
                        if(strpos($m[1], '!')!==false){
                            $ml = trim(SysSettings::getValue('Shop_Group.Multiword','content','Единственные числа',null,''),"\r\n ");
                            $ml = explode("\n",$ml);
                            foreach($ml as $Mm){
                                $Mm = trim($Mm,"\r\n\ ");
                                $Mm = array_map(function($item){return $item;},explode('=',$Mm));
                                $val = str_replace($Mm[0],$Mm[1],$val);
                            }
                            if(preg_match("/[иы]$/", $val)>0){
                                $val = mb_substr($val, 0,-1,X3::app()->encoding);
                            }
                        }
                        if(strpos($m[1], '@')!==false){
                            $val = X3_String::create($val)->toLowerCase();
                        }
                    }
                    $mz = str_replace($m[0], $val, $mz);
                }
            }
        }
        foreach ($ms as &$mz){
            $m = array();
            if(preg_match_all("/\[([@!]{0,2})(group|manufacturer|name|articule)\]/",$mz,$m)>0){
                foreach($m[0] as $i=>$mx){
                    if(isset($m[2][$i]) && $m[2][$i] == 'group'){
                        $val = $this->title;
                    }elseif(isset($m[2][$i]) && $m[2][$i] == 'manufacturer'){
                        $val = $manuf;
                    }elseif(isset($m[2][$i]) && $m[2][$i] == 'name'){
                        $val = $this->title;
                    }else
                        $val = '';
                    if(!empty($m) && $m[1][$i]!=''){
                        if(strpos($m[1][$i], '!')!==false){                            
                            $ml = trim(SysSettings::getValue('Shop_Group.Multiword','content','Единственные числа',null,''),"\r\n ");
                            $ml = explode("\n",$ml);
                            foreach($ml as $Mm){
                                $Mm = trim($Mm,"\r\n\ ");
                                $Mm = array_map(function($item){return $item;},explode('=',$Mm));
                                $val = str_replace($Mm[0],$Mm[1],$val);
                            }                            
                            if(preg_match("/[иы]$/", $val)>0){
                                $val = mb_substr($val, 0,-1,X3::app()->encoding);
                            }
                        }
                        if(strpos($m[1][$i], '@')!==false){
                            $val = X3_String::create($val)->toLowerCase();
                        }
                    }
                    $mz = str_replace($mx, $val, $mz);
                }
            }
            $m = array();
            $mz = Shop_Item::condition($mz);
        }
        $this->alltitle = str_replace("  "," ",$mt);
        $this->allkeywords = str_replace("  "," ",$mk);
        $this->alldescription = str_replace("  "," ",$md);
        $this->allheader = $mh;
    }
    
    public function handleFilters($bsession=true) {
        $query = array('@condition'=>array('shop_item.group_id'=>$this->id,'shop_item.status'));
        $Tprop = "prop_$this->id";
        $manu = "manu_$this->id";
        $price = "price_$this->id";
        $query['@join'] = "INNER JOIN company_item ON company_item.item_id=shop_item.id ";
        $query['@condition']['price']=array(' > '=>"0");
        if(isset($_POST['Manufacturer']) || X3::app()->user->$manu!==null){
            $manufacturer = isset($_POST['Manufacturer'])?$_POST['Manufacturer']:X3::user()->$manu;
            if(isset($_POST['Manufacturer'])){
                if($bsession)
                    X3::app()->user->$manu = $manufacturer;
                $_GET['page'] = 1;
            }elseif(isset($_POST['Price'])){
                unset(X3::app()->user->$manu);
                $manufacturer = array();
            }
            if($bsession){
                if(count($manufacturer)===1){
                    $man = Manufacturer::getByPk($manufacturer[0]);
                    $url = explode('/',X3::app()->request->url);
                    $url = $url[0];
                    X3::app()->user->$manu = null;
                    header("HTTP/1.1 303 See Other");
                    header("Location: /".$url.'/'.$man->name.'.html');
                }elseif(isset($_GET['manuf'])){
                    $url = explode('/',X3::app()->request->url);
                    $url = $url[0];
                    header("HTTP/1.1 303 See Other");
                    header('Location: /'.$url.'.html');    
                }
            }
            if(!empty($manufacturer))
                $query['@condition']['manufacturer_id'] = array('IN'=>"(".implode(',',$manufacturer).")");
        }elseif(!isset($_POST['Manufacturer']) && isset($_GET['manuf'])){
            $this->manuf = $man = Manufacturer::get(array('name'=>$_GET['manuf']),1);
            if($man!==null){
                $query['@condition']['manufacturer_id'] = $man->id;
                X3::app()->group = $man->id;
            }
        }
        if(isset($_POST['Price']) || X3::app()->user->$price!==null){
            $prices = isset($_POST['Price'])?$_POST['Price']:X3::user()->$price;
            if(isset($_POST['Price'])){
                if($bsession)
                    X3::app()->user->$price = $prices;
                $_GET['page'] = 1;
            }
            $query['@condition']['price'] = array('BETWEEN'=>(int)$prices['min'] . " AND ".(int)$prices['max']);
        }
        if(X3::app()->property>0){
            $prop = $this->prop = X3::db()->fetch("SELECT name,type FROM shop_properties WHERE id=(SELECT property_id FROM shop_proplist WHERE id=".X3::app()->property.")");
            if($prop==null) throw new X3_404;
            if(isset($_POST['Filter'])){
                $_POST['Filter'][$prop['type']][$prop['name']]=X3::app()->property;
            }
            $_tmp = array();
            if(X3::app()->user->$Tprop!=null && $bsession){
                $_tmp = X3::app()->user->$Tprop;
            }
            $_tmp[$prop['type']][$prop['name']]=X3::app()->property;
            X3::app()->user->$Tprop = $_tmp;
            unset($_tmp);
        }
        if(isset($_POST['Filter']) || X3::app()->user->$Tprop!==null){
            $filters = isset($_POST['Filter'])?$_POST['Filter']:X3::app()->user->$Tprop;
            if(isset($_POST['Filter'])){
                if($bsession){
                    $_tmp = X3::app()->user->$Tprop;
                    X3::app()->user->$Tprop = array_extend($_tmp, $filters);
                    unset($_tmp);
                }
                $_GET['page'] = 1;
            }
            $query['@join'] .= " INNER JOIN $Tprop ON $Tprop.id=shop_item.id";
            //strings
            if(!empty($filters['string']))
            foreach($filters['string'] as $name=>$filter){
                if(is_array($filter))
                    $query['@condition']["$Tprop.$name"]=array('IN'=>"(".implode(',',$filter).")");
                elseif((int)$filter>0)
                    $query['@condition']["$Tprop.$name"]=$filter;
            }
            //scalar
            if(!empty($filters['scalar']))
            foreach($filters['scalar'] as $name=>$filter){
                if(is_array($filter)){
                    $filter['from'] = str_replace(',', '.', $filter['from']);
                    $filter['till'] = str_replace(',', '.', $filter['till']);
                    if(trim($filter['from'])=='')
                        $query['@condition']["$Tprop.$name"]=array('<='=>$filter['till']);
                    elseif(trim($filter['till'])=='')
                        $query['@condition']["$Tprop.$name"]=array('>='=>$filter['from']);
                    else
                        $query['@condition']["$Tprop.$name"]=array('@@'=>"($Tprop.$name BETWEEN {$filter['from']} AND {$filter['till']} OR $Tprop.$name IS NULL)");
                }else{
                    $filter = str_replace(',', '.', $filter);
                    if(trim($filter)!='')
                        $query['@condition']["$Tprop.$name"]=$filter;
                }
            }
            //boolean
            if(!empty($filters['boolean']))
            foreach($filters['boolean'] as $name=>$filter){
                $query['@condition']["$Tprop.$name"]=1;
            }
        }
        $query['@select']="DISTINCT shop_item.*";
        /*if(X3::app()->property!=null){
            $prop = X3::db()->fetch("SELECT name FROM shop_properties WHERE id=(SELECT property_id FROM shop_proplist WHERE id=".X3::app()->property.")");
            if($prop==null) throw new X3_404;
            if(strpos($query['@join'],$Tprop)===false) 
                $query['@join'] .= " INNER JOIN $Tprop ON $Tprop.id=shop_item.id";
            $query['@condition']["$Tprop.{$prop['name']}"]=X3::app()->property;
        }*/
        //$q = new Shop_Item();
        //echo $q->getTable()->formQuery($query)->buildSQL();exit;
        return $query;
    }
    
    public function actionReset() {
        $id = $_GET['id'];
        $Tprop = "prop_$id";
        $manu = "manu_$id";
        $price = "price_$id";        
        if(!is_null(X3::app()->user->$Tprop)){
            unset(X3::app()->user->$Tprop);
            unset(X3::user()->{"shop-page"});
        }
        if(!is_null(X3::app()->user->$manu)){
            unset(X3::app()->user->$manu);
            unset(X3::user()->{"shop-page"});
        }
        if(!is_null(X3::app()->user->$price)){
            unset(X3::app()->user->$price);
            unset(X3::user()->{"shop-page"});
        }
        X3::user()->Shop_GroupPage = 0;
        $url = explode('/',str_replace('http://www.kansha.kz/','',$_SERVER['HTTP_REFERER']));
        $url = str_replace('.html','',$url[0]);
        if(!IS_AJAX){
            header('Location: /'.$url.'.html');
        }else
            echo "/$url.html";
        exit;
    }
    
    public function actionCountup() {
        if(!empty($_POST) && isset($_GET['id'])){
            $id = (int)$_GET['id'];
            $model = self::getByPk($id);
            if(X3::user()->property_id>0)
                X3::app()->property = X3::user()->property_id;
            $query = $model->handleFilters(false);
            $q = new X3_MySQL_Query('shop_item');
            //echo $q->formQuery($query)->buildSQL();
            $count = Shop_Item::num_rows($query);
            if($count == 0)
                echo 'Не найдено моделей';
            else
                echo $count . X3_String::create($count)->numeral($count,array('<br/>товар найден','<br/>товара найдено','<br/>товаров найдено'));
        }
        exit;
    }
    
    public function actionIndex() {
        $models = array();
        $q = X3::db()->query("SELECT sg.id id, sg.title title, sg.category_id FROM shop_group sg 
            WHERE status ORDER BY weight, title");
        $count = 0;
        $gcount = 0;
        while($g = mysql_fetch_object($q)){
            $g->count = X3::db()->count("SELECT `item_id` FROM shop_item si INNER JOIN company_item ci ON ci.item_id=si.id WHERE ci.price>0 AND si.group_id=$g->id GROUP BY `ci`.`item_id`");
            $count += $g->count;
            $cat = X3::db()->fetchAll("SELECT * FROM shop_category WHERE status AND id IN ($g->category_id) ORDER BY weight, title");
            if(!empty($cat))
            foreach($cat as $ca){
                $key = $ca['weight'];
                if(!isset($models[$key]['model'])){
                    $models[$key]['model']=(object)$ca;
                    $models[$key]['model']->count = 0;
                }
                $models[$key]['model']->count += $g->count;
                $ps = X3::db()->fetchAll("SELECT * FROM shop_properties WHERE isgroup AND group_id=$g->id ORDER BY weight, label");
                if(X3::user()->isAdmin()){
                //    echo "$g->id - $g->title - ".count($ps)."<br/>";
                }
                if(!empty($ps)){
                    foreach($ps as $pr){
                        if($pr['type']=='string' || $pr['type']=='content'){
                            $pls = X3::db()->query("SELECT * FROM shop_proplist sl WHERE group_id=$g->id AND property_id={$pr['id']} ORDER BY weight, value");
                            while($pl = mysql_fetch_assoc($pls)){
                                $gcount++;
                                $c = X3::db()->count("SELECT DISTINCT si.`id` FROM shop_item si INNER JOIN company_item ci ON ci.item_id=si.id INNER JOIN prop_$g->id p ON p.id=si.id WHERE `p`.`{$pr['name']}`='{$pl['id']}' AND ci.price>0");
                                $models[$key]['models'][]=(object)array(
                                    'url'=>"/".Shop_Group::getLink($g->id,$pl['id'],false,$pl['title']).".html",
                                    'id'=>$pl['id'],
                                    'title'=>$pl['title']!=''?$pl['title']:$pl['value'],
                                    'count'=>$c
                                );
                            }
                        }elseif($pr['type']=='boolean'){
                            $gcount++;
                            $c = X3::db()->count("SELECT si.`id` FROM shop_item si INNER JOIN shop_properties sl ON sl.group_id=si.group_id WHERE sl.status AND sl.id={$pr['id']}");
                            $cnt += $c;
                            $models[$key]['models'][]=(object)array(
                                'url'=>"/".Shop_Group::getLink($g->id,$pr['id'],false,$pr['label']).".html",
                                'id'=>$pr['id'],
                                'title'=>$pr['label'],
                                'count'=>$c
                            );
                        }
                    }
                }else{
                    $gcount++;
                    $models[$key]['models'][] = (object)array(
                        'url'=>"/".Shop_Group::getLink($g->id,false,$g->title).".html",
                        'id'=>$g->id,
                        'title'=>$g->title,
                        'count'=>$g->count,
                    );
                }
            }
        }
        ksort($models);
        $this->template->render('index',array('models'=>$models,'count'=>$count,'gcount'=>$gcount,'bread'=>'<span style="top:1px" class="sale">Все разделы</span>'));
    }

    public function actionShow(){ 
        if(!isset($_GET['id'])) 
            throw new X3_404();
        $id = (int)$_GET['id'];
        $model = self::getByPk($id);
        if($model === NULL || $model->status == false)
            throw new X3_404();
        if(isset($_GET['propid'])){
            if(X3::user()->property_id != $_GET['propid']){
                X3::user()->Shop_GroupPage = 0;
                X3::user()->{"prop_$id"} = null;
                X3::user()->{"manu_$id"} = null;
                X3::user()->{"price_$id"} = null;
            }
            X3::user()->property_id = X3::app()->property = (int)$_GET['propid'];
        }elseif(X3::user()->group_id != $id){
            X3::user()->Shop_GroupPage = 0;
            X3::user()->property_id = null;
            $gid = X3::user()->group_id;
            X3::user()->{"prop_$gid"} = null;
            X3::user()->{"manu_$gid"} = null;
            X3::user()->{"price_$gid"} = null;
        }
        X3::profile()->start('test01');
        X3::user()->group_id = $id;
        //var_dump(X3::user()->{"prop_$model->id"});exit;
        $query = $model->handleFilters();
        if(!isset($query['@condition']['price']))
            $query['@condition']['price']=array('>'=>'0 ');
        //var_dump(X3::user()->{"prop_$model->id"});exit;
        $count = Shop_Item::num_rows($query);
        $paginator = new Paginator(__CLASS__,$count);
        $query['@select']='DISTINCT shop_item.*, (SELECT MIN(price) FROM company_item WHERE company_item.item_id=shop_item.id) price';
        //$q = new X3_MySQL_Query('shop_item');die($q->formQuery($query)->buildSQL());
        $query['@order']='price ASC, rate DESC,shop_item.weight,shop_item.created_at DESC,title';
        $query['@limit']=$paginator->limit;
        $query['@offset']=$paginator->offset;
        $items = Shop_Item::getInstance()->table->formQuery($query)->buildSQL();
        $a = '';
        $b = '';
        $prop = false;
        if(X3::app()->property>0 && $this->prop!=null){
            $prop = $this->prop;
        }elseif(X3::app()->property>0){
            $prop = $this->prop = X3::db()->fetch("SELECT name,type FROM shop_properties WHERE id=(SELECT property_id FROM shop_proplist WHERE id=".X3::app()->property.")");
        }
        if($prop!==false){
            $a = "INNER JOIN prop_$model->id p ON p.id=si.id";
            $b = " AND `p`.`{$prop['name']}`='".X3::app()->property."'";
        }
        $mm = X3::db()->fetch("SELECT MIN(price) as `min`, MAX(price) as `max`, COUNT(0) as `cnt` FROM company_item 
            INNER JOIN shop_item si ON si.id=company_item.item_id $a WHERE si.group_id='$model->id' AND price>0 $b");
        $priceMin = (int)$mm['min'];
        $priceMax = (int)$mm['max'];
        X3::profile()->end('test01');
        if(isset($_POST['Price']) || X3::app()->user->{"price_$model->id"}!==null){
            $price = isset($_POST['Price'])?$_POST['Price']:X3::app()->user->{"price_$model->id"};
            $priceMin = $price['min'];
            $priceMax = $price['max'];
        //    $prices = "price BETWEEN $priceMin AND $priceMax"; 
        }
        if(empty($model->alltitle)) $model->alltitle = SysSettings::getValue("SeoTitleShop_Group");
        if(empty($model->allkeywords)) $model->allkeywords = SysSettings::getValue("SeoKeywordsShop_Group");
        if(empty($model->alldescription)) $model->alldescription= SysSettings::getValue("SeoDescriptionShop_Group");
        $model->prepareGeneralMeta(X3::user()->{"prop_$model->id"});
        SeoHelper::setMeta($model->alltitle, $model->allkeywords, $model->alldescription);
        $bread = Breadcrumbs::items($model);
        if(isset($_GET['manuf'])){
            $manuf = Manufacturer::get(array('name'=>$_GET['manuf']),1);
            if($manuf!=null && NULL!==($md = Manufacturer_Group::get(array('manufacturer_id'=>$manuf->id,'gid'=>''.$id,'pid'=>''.(int)X3::app()->property),1))){
                X3::app()->group_description = $md->text;
            }
        }
        $this->template->render('show',array('model'=>$model,'items'=>$items,'count'=>$count,'paginator'=>$paginator,
            'minPrice'=>(int)$mm['min'],'maxPrice'=>(int)$mm['max'],'num_prices'=>$mm['cnt'],
            'priceMin'=>$priceMin,'priceMax'=>$priceMax,
            'bread'=>$bread
        ));
    }
    
    public function actionGenmodels() {
        if(isset($_GET['id']) && !is_file("uploads/status-models{$_GET['id']}.log")){
            if($_GET['id']>0 && (NULL!==($model = Shop_Group::getByPk($_GET['id']))))
            exec('php run.php shop genmodels gid='.$model->id.' > /dev/null 2>&1 &');
            //system('php run.php shop genmodels gid='.$model->id);exit;
        }
        X3::app()->module->controller->redirect('/admin/shopgroup/edit/'.$model->id);
    }
    
    public function getDefaultScope() {
        return array(
            '@order'=>'title ASC'
        );
    }
           
    
    public function onDelete($tables,$condition) {
        if(strpos($tables,$this->tableName)!==false){
            //bloody slaughter begin
            $model = $this->table->select('*')->where($condition)->asObject(true);
            X3::log($tables."\n".$condition);
            $name = "prop_" . $model->id;
            if(!X3::db()->query("DROP TABLE `$name`")){
                X3::log("При удалении группы '$model->title' не удалось удалить таблицу свойств `$name`",'kansha_error');
            }
            Grabber_Groups::delete(array('group_id'=>$model->id));
            Shop_Item::delete(array('group_id'=>$model->id));
            Shop_Properties::delete(array('group_id'=>$model->id));
            Shop_Proplist::delete(array('group_id'=>$model->id));
            Shop_Properties::drop("prop_$model->id");
        }
        parent::onDelete($tables,$condition);
    }
    
}
?>
