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
class Shop_Category extends X3_Module_Table {

    public $encoding = 'UTF-8';
    public $tableName = 'shop_category';

    public $_fields = array(
        'id'=>array('integer[10]','unsigned','primary','auto_increment'),
        'image'=>array('file','default'=>'NULL','allowed'=>array('jpg','gif','png','jpeg'),'max_size'=>10240),
        'title'=>array('string[255]','orderable'),
        'text'=>array('text','default'=>'NULL'),
        'status'=>array('boolean','default'=>'1','orderable'),
        'weight'=>array('integer[5]','unsigned','default'=>'0'),
        'created_at'=>array('integer[10]','unsigned','default'=>'0'),
        'metatitle' => array('string', 'default' => '', 'language'),
        'metakeywords' => array('content', 'default' => '', 'language'),
        'metadescription' => array('content', 'default' => '', 'language'),        
    );
    
    public function fieldNames() {
        return array(
            'image'=>'Иконка',
            'title'=>'Название',
            'text'=>'Описание',
            'status'=>'Видимость',
            'weight'=>'Порядок',            
        );
    }
    
    public function moduleTitle() {
        return 'Категории';
    }
    
    public function cache(){
        return array(
        //    'cache'=>array('actions'=>'index','role'=>'*','expire'=>'+2 minutes'),
        );
    }
    
    public function beforeValidate() {
        if($this->created_at == 0)
            $this->created_at = time();
    }

    public function getDefaultScope() {
        return array(
            '@order'=>'weight ASC'
        );
    }
    
    public function actionShow() {
        if(!isset($_GET['id'])) 
            throw new X3_404();
        $id = (int)$_GET['id'];
        $model = self::getByPk($id);
        if($model === NULL || $model->status == false)
            throw new X3_404();
        $groups = Shop_Group::get(array('@condition'=>array('status','category_id'=>array('REGEXP'=>"'^$id,|,$id,|,$id$|^$id$'")),'@order'=>'weight, title'));
        $ids = array();
        $grs = array();
        foreach ($groups as $gr) {
            $ids[] = $gr->id;
            $prs = X3::db()->fetchAll("SELECT id, property_id, title FROM shop_proplist WHERE status AND property_id IN (SELECT id FROM shop_properties WHERE group_id={$gr->id} AND isgroup) ORDER BY weight, value");
            if(!empty($prs)){
                foreach ($prs as $pr) {
                    $p = X3::db()->fetch("SELECT `name` FROM shop_properties WHERE id='{$pr['property_id']}' LIMIT 1");
                    $name = $p['name'];
                    $img = X3::db()->fetch("SELECT si.image FROM shop_item si INNER JOIN prop_{$gr->id} p ON p.id=si.id WHERE `$name` LIKE '{$pr['id']}' AND si.group_id=$gr->id AND si.image<>'' AND si.image IS NOT NULL ORDER BY si.id DESC LIMIT 1");
                    if(!empty($img)){
                        $img = '/uploads/Shop_Item/135x107xf/'.$img['image'];
                    }else
                        $img = false;
                    $grs[] = array(
                        'id'=>$gr->id,
                        'pid'=>$pr['property_id'],
                        'url'=>"/".Shop_Group::getLink($gr->id,$pr['id'],false,$pr['title']).".html",
                        'image'=>$img,
                        'title'=>$pr['title']
                    );
                }
            }else{
                $img = Shop_Item::get(array('@condition'=>array('group_id'=>$gr->id,'image'=>array('<>'=>"''"),'image'=>array(' IS'=>' NOT NULL')),'@order'=>'created_at DESC, id'),1)->image;
                $grs[] = array(
                    'id'=>$gr->id,
                    'pid'=>0,
                    'url'=>"/".Shop_Group::getLink($gr->id,false,$gr->title).".html",
                    'image'=>'/uploads/Shop_Item/135x107xf/'.$img,
                    'title'=>$gr->title
                );
            }
        }
        $count = Shop_Item::num_rows(array('@condition'=>array('group_id'=>array('IN'=>"(".implode(',',$ids).")"),'status','ci.price'=>array('>'=>'0')),
            '@join'=>'INNER JOIN company_item as ci ON ci.item_id = shop_item.id'));
        $bread = Breadcrumbs::category($model);
        SeoHelper::setMeta($model->metatitle,$model->metakeywords,$model->metadescription);
        $this->template->render('show',array('model'=>$model,'count'=>$count,'groups'=>$grs,
            'bread'=>$bread
        ));        
    }
}
?>
