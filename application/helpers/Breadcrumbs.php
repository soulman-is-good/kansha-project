<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of KWidget
 *
 * @author Soul_man
 */
class Breadcrumbs extends X3_Widget {
    
    
    public static function items($model) {
        if(get_class($model) == 'Shop_Item')
            $group = $model->getGroup();
        else
            $group = $model;
        $bread = array();
        $secs = X3::db()->query("SELECT * FROM shop_category WHERE status ORDER BY weight, title");
        $grs = X3::db()->query("SELECT * FROM shop_group WHERE status AND category_id LIKE '$group->category_id' ORDER BY weight, title");
        $cats = array();        
        $i=1;
        $catsid = explode(',',$group->category_id);
        while($ss = mysql_fetch_assoc($secs)){
            $url = "/".strtolower(X3_String::create($ss['title'])->translit(false,"'"))."-c".$ss['id'].".html";
            if(in_array($ss['id'],$catsid))
                $cats[0] = array($url=>$ss['title']);
            else
                $cats[$i++] = array($url=>$ss['title']);
        }
        
        ksort($cats);
        $bread[] = $cats;
        $cats = array();
        $i=1;
        while($g = mysql_fetch_assoc($grs)){
            $prs = X3::db()->fetchAll("SELECT id, value, title, description FROM shop_proplist WHERE status AND property_id IN (SELECT id FROM shop_properties WHERE group_id={$g['id']} AND isgroup)");
            if(!empty($prs)){
                foreach ($prs as $pr) {
                    $url = "/".Shop_Group::getLink($g['id'],$pr['id'],false,$pr['title']).".html";
                    if(X3::user()->property_id == $pr['id']){
                        X3::app()->groupTitle = $pr['title'];
                        if(!empty($pr['description']))
                            X3::app()->group_description = $pr['description'];
                        $cats[0]=array($url=>$pr['title']);
                    }else{
                        $cats[$i++]=array($url=>$pr['title']);                        
                    }
                }
            }else{
                $url = "/".Shop_Group::getLink($g['id'],null,$g['title']).".html";
                if(X3::user()->property_id == null && $group->id == $g['id']){
                    X3::app()->groupTitle = $g['title'];
                    if(!empty($g['text']))
                        X3::app()->group_description = $g['text'];
                    $cats[0]=array($url=>$g['title']);
                }else
                    $cats[$i++]=array($url=>$g['title']);
            }
        }
        ksort($cats);
        $bread[] = $cats;
        
        return $bread;        
    }
    
    public static function category($model) {
        $bread = array();
        $secs = X3::db()->fetchAll("SELECT * FROM shop_category WHERE status ORDER BY weight, title");
        $cats = array();        
        $i=1;
        foreach($secs as $ss){
            $url = "/".strtolower(X3_String::create($ss['title'])->translit(false,"'"))."-c".$ss['id'].".html";
            if($ss['id']==$model->id)
                $cats[0] = array($url=>$ss['title']);
            else
                $cats[$i++] = array($url=>$ss['title']);
        }
        ksort($cats);
        $bread[] = $cats;
        
        return $bread;        
    }
    
    public static function service() {
        $bread = array();
        
        $cats = array('/services.html'=>'Услуги');        
        ksort($cats);
        $bread[] = $cats;
        
        return $bread;        
    }
    
    public static function pages($model) {
        $bread = array();
        if($model->parent_id>0){
            $models = Page::get(array('parent_id'=>$model->parent_id));
            $para = Page::getByPk($model->parent_id);
            $bread[] = array("/page/$para->name.html"=>$para->short_title);
            $z = 1;
            foreach($models as $m)
                if($m->id == $model->id)
                    $cats[0] = array("/page/$model->name.html"=>$model->short_title);
                else
                    $cats[$z++] = array("/page/$m->name.html"=>$m->short_title);
        }else{
            $cats = array("/page/$model->name.html"=>$model->short_title);
        }
        ksort($cats);
        $bread[] = $cats;
        
        return $bread;        
    }
}

?>
