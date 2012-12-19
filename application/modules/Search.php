<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Search
 *
 * @author Soul_man
 */
class Search extends X3_Module_Table{
    
    public $encoding = 'UTF-8';
    public $tableName = 'data_search';
    
    public $_fields = array(
            'id'=>array('integer[11]','unsigned','primary','auto_increment'),
            'ip'=>array('string[32]'),
            'keyword'=>array('string[255]','index'),
            'count'=>array('integer','default'=>'1')
    );
    
    
    public function actionIndex() {
        $type = 'items';
        if(isset($_GET['type']) && in_array($_GET['type'],array('items','news','articles','services','sales'))){
            $type = $_GET['type'];
            X3::user()->search_group = 0;
            if($type != X3::user()->SearchType){
                X3::user()->SearchPage = 0;
                X3::user()->SearchType = $type;
            }
        }elseif(X3::user()->SearchType!='')
            $type = X3::user()->SearchType;
        
        if(isset($_GET['group']))
            X3::user()->search_group = $_GET['group'];
        if(isset($_GET['search'])){
            if($_GET['search'] != X3::user()->search){
                X3::user()->search_group = 0;
                X3::user()->SearchPage = 0;
                X3::user()->SearchType = 'items';
            }
            X3::user()->search = $_GET['search'];
        }
        $numbers = array(0,0,0,0,0);
        if(($search = strip_tags(X3::user()->search))!='' && ($search = trim($search))!=''){
            $search = preg_replace("/[\s]+/"," ",$search);
            $search = preg_replace("/<>/","",$search);
            //keywords logic
            $words = explode(' ', $search);
            X3::db()->query("LOCK TABLES data_search WRITE");
            foreach($words as $word){
                $tmp = mysql_real_escape_string($word);
                $s = X3::db()->fetch("SELECT id,count FROM data_search WHERE keyword LIKE '$tmp'");
                if($s){ if($s['ip'] != $_SERVER['REMOTE_ADDR'])
                    X3::db()->query("UPDATE data_search SET count=".($s['count']+1)." WHERE id=".$s['id']);
                }else{
                    $s = new self;
                    $s->ip = $_SERVER['REMOTE_ADDR'];
                    $s->keyword = $word;
                    $s->count = 1;
                    if(!$s->save()){
                        var_dump($s->getTable()->getErrors());exit;
                    }
                }
            }
            X3::db()->query("UNLOCK TABLES");
            $qitems=Shop_Item::getInstance()->searchItem($search, 'query',false,'AND');
            $qnews=News::getInstance()->search($search, 'query',false,'OR');
            $qarticle=Article::getInstance()->search($search, 'query',false,'OR');
            $qservice=Service::getInstance()->search($search, 'query',false,'OR');
            $qsale=Sale::getInstance()->search($search, 'query',false,'OR');
            $count = Shop_Item::num_rows($qitems);
            $ncount = News::num_rows($qnews);
            $acount = Article::num_rows($qarticle);
            $scount = Company::num_rows($qservice);
            $rcount = Sale::num_rows($qsale);
            $width = 0;
            if(!isset($_GET['type']) && (isset($_GET['search']) && $_GET['search'] != X3::user()->search)){
            if($count==0) X3::user()->SearchType = $type = 'news';
            if($count==0 && $ncount==0) X3::user()->SearchType = $type = 'articles';
            if($count==0 && $ncount==0 && $acount==0) X3::user()->SearchType = $type = 'services';
            if($count==0 && $ncount==0 && $acount==0 && $scount==0) X3::user()->SearchType = $type = 'sales';
            }
            if($count>0) $width += 77;if($ncount>0) $width += 77;if($acount>0) $width += 77;if($scount>0) $width += 77;if($rcount>0) $width += 77;
            $numbers = array($count,$ncount,$acount,$scount,$rcount);
            $block = false;
            switch($type){
                case 'items':
                    $block = $qitems;
                    if(X3::user()->search_group>0)
                        $qitems['@condition']['group_id'] = X3::user()->search_group;
                    $paginator = new Paginator(__CLASS__,$count);
                    $qitems['@order'] = 'shop_item.weight,shop_item.created_at DESC';
                    $qitems['@offset'] = $paginator->offset;
                    $qitems['@limit'] = $paginator->limit;
                    $items = new X3_MySQL_Query('shop_item');
                    $models = $items->formQuery($qitems)->buildSQL();
                    //echo $models;die;
                break;
                case 'news':
                    $paginator = new Paginator(__CLASS__,$ncount);
                    $qnews['@order'] = 'data_news.created_at DESC';
                    $qnews['@offset'] = $paginator->offset;
                    $qnews['@limit'] = $paginator->limit;
                    $items = new X3_MySQL_Query('data_news');
                    $models = $items->formQuery($qnews)->buildSQL();
                break;
                case 'articles':
                    $paginator = new Paginator(__CLASS__,$acount);
                    $qarticle['@order'] = 'data_article.created_at DESC';
                    $qarticle['@offset'] = $paginator->offset;
                    $qarticle['@limit'] = $paginator->limit;
                    $items = new X3_MySQL_Query('data_article');
                    $models = $items->formQuery($qarticle)->buildSQL();
                break;
                case 'services':
                    $block = array(
                        'services'=>X3::db()->fetchAll("SELECT ds.id,ds.name,ds.title FROM data_service ds WHERE status AND (SELECT COUNT(0) FROM company_service cs WHERE cs.services LIKE CONCAT('%\"',ds.id,'\"%'))>0 ORDER BY title"),
                        'groups'=>X3::db()->fetchAll("SELECT g.id,g.title FROM shop_group g WHERE status AND (SELECT COUNT(0) FROM company_service cs WHERE cs.groups LIKE CONCAT('%\"',g.id,'\"%'))>0 ORDER BY title")
                    );
                    $paginator = new Paginator(__CLASS__,$scount);
                    $qservice['@offset'] = $paginator->offset;
                    $qservice['@limit'] = $paginator->limit;
                    $items = new X3_MySQL_Query('data_company');
                    $items->select = 'data_company.*';
                    $models = $items->formQuery($qservice)->buildSQL();
                    //var_dump($models);exit;
                break;
                case 'sales':
                    $paginator = new Paginator(__CLASS__,$rcount);
                    $qsale['@offset'] = $paginator->offset;
                    $qsale['@limit'] = $paginator->limit;
                    $items = new X3_MySQL_Query('data_sale');
                    $models = $items->formQuery($qsale)->buildSQL();
                break;
            }
        }
        $this->template->render('index',array('models'=>$models,'type'=>$type,'paginator'=>$paginator,'numbers'=>$numbers,'block'=>$block,'width'=>$width,
            'bread'=>'<div style="position:relative;float:left;"><a class="list_shops" ><span>Результаты поиска по запросу “'.$search.'”</span></a></div>'));
    }
    
    
}

?>
