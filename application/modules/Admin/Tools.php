<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Default
 *
 * @author Soul_man
 */
class Admin_Tools extends X3_Module {

    public function filter() {
        return array(
                /*    'allow'=>array(
                  '*'=>array('*'),
                  'user'=>array('map','limit','feedback','error'),
                  'sale'=>array('map','limit','feedback','error'),
                  'admin'=>array('map','limit','feedback','error')
                  ),
                  'deny'=>array(
                  '*'=>array('*'),
                  'admin'=>array('index'),
                  'user'=>array('index'),
                  'sale'=>array('index')
                  ),
                  'handle'=>'redirect:/user/settings.html' */
        );
    }

    public function menu($cur = 'process') {
        $a = array(
            '/admin/tools/process' => 'Процессы',
            '/admin/tools/cleancache'=>'Кэш',
            '/admin/tools/redirects'=>'Перенаправления',
            '/admin/tools/restore'=>'Обитель времени',
            '/admin/storage'=>'Хранилище',
            '/admin/user'=>'Пользователи',
            '/admin/tools/tasks'=>'Задания',
        );
        $p = array();
        $modules = X3::app()->module->modules[X3::user()->group]['Admin_Tools'];
        foreach ($a as $k => $v) {
            $j = pathinfo($k,PATHINFO_BASENAME);
            if(strpos($k,'tools')===false || (strpos($k,'tools')!=false && in_array($j, $modules['direct'])))
            if (strpos($k,$cur)!==false)
                $p[] = "<span>$v</span>";
            else
                $p[] = "<a href=\"$k\">$v</a>";
        }
        return implode(' :: ', $p);
    }
    

    public function route() { //Using X3_Request $url propery to parse
        return array(
            '/^sitemap\.xml$/' => 'actionMap',
            '/^download\/(.+?).html/' => array(
                'class' => 'Download',
                'argument' => '$1'
            )
        );
    }
    public function moduleTitle() {
        return 'Инструменты';
    }
    
    public function actionRestore() {
        $table = $_POST['table'];
        $date = $_POST['date'];
        exec("php run.php job restore table=$table date=$date &");
        exit;
    }
    
    public function actionShowfile() {
        if(!X3::user()->isAdmin()) exit;
        $f = $_POST['filename'];
        if(is_file($f)){
            echo file_get_contents($f);
        }
        exit;
    }
    
    public function actionSavefile() {
        if(!X3::user()->isAdmin()) exit;
        $f = $_POST['filename'];
        if(is_file($f)){
            echo 'OK';
            @file_put_contents($f,$_POST['text']);
        }
        exit;
    }
    
    public function actionDeletefile() {
        if(!X3::user()->isAdmin()) exit;
        $f = $_POST['filename'];
        if(is_file($f)){
            @unlink($f);
        }
        exit;
    }
    
    public function process() {
        $var = array();
        exec('ps -eo "%p %c %C %U %G"', $var);
        array_pop($var);
        array_shift($var);
        $process = array();
        foreach ($var as $i => $p) {
            $k = explode(" ", trim(preg_replace("/[\s]{2,}/", " ", $p)));
            $process[$i]['pid'] = $k[0];
            $process[$i]['cmd'] = $k[1];
            $process[$i]['cpu'] = $k[2];
            $process[$i]['user'] = $k[3];
            $process[$i]['group'] = $k[4];
        }        
        return $process;
    }
    
    public function actionProcess() {
        if(!IS_AJAX || !X3::user()->isAdmin()) throw new X3_404();
        $process = $this->process();
        echo json_encode($process);
        exit;
    }
    
    public function execStopwork() {
        if(false === ($a = file_put_contents(X3::app()->basePath . DIRECTORY_SEPARATOR . 'dump/site.stop','stop')))
            die($a);
        X3::app()->module->redirect('/admin/tools/restore');
        exit;
    }
    
    public function execStartwork() {
        if(is_file(X3::app()->basePath . DIRECTORY_SEPARATOR . 'dump/site.stop'))
            @unlink(X3::app()->basePath . DIRECTORY_SEPARATOR . 'dump/site.stop');
        X3::app()->module->redirect('/admin/tools/restore');
        exit;
    }

    public function execProcess() {
        $process = $this->process();
        X3::app()->module->template->addData(array('process' => $process));
    }
    
    public function execCleancache() {
        $files = array();
        $dir = 'application/cache';
        $h = opendir($dir);
        if(!$h) return false;
        while($file = readdir($h)){
            if($file!='.' && $file!='..' && !is_dir("$dir/$file")){
                if($_GET['cleancache']=='yes')
                    @unlink("$dir/$file");
                else
                $files[] = array(
                    'filename'=>$file,
                    'time'=>  filemtime("$dir/$file")
                );
            }
        }
        closedir($h);
        X3::app()->module->template->addData(array('files' => $files));        
    }
    
    public function execRedirects() {
        if(isset($_POST['redirects'])){
            $r = $_POST['redirects'];
            if(!empty($r)){
                @file_put_contents(X3::app()->basePath . '/redirects.txt', $r);
            }
        }
    }
    
    public function execTasks() {
        $informer = new Informer;
        if(isset($_POST['Informer'])){
            $inf = $_POST['Informer'];
            $tmp = array('message'=>$inf['message'],'user_id'=>$inf['user_id']);
            $inf['message'] = json_encode($tmp);
            $informer->getTable()->acquire($inf);
            if($informer->id>0)
                $informer->getTable()->setIsNewRecord(false);
            $informer->category = 'tasks';
            $informer->created_at = time();
            if($informer->save()){
                X3::app()->module->redirect('/admin/tools/tasks');
            }else{
                $informer->message = $tmp['message'];
                $informer->user_id = $tmp['user_id'];
            }
        }
        if(isset($_GET['delete'])){
            Informer::deleteByPk((int)$_GET['delete']);
            X3::app()->module->redirect('/admin/tools/tasks');
        }
        if(isset($_GET['edit'])){
            $informer = Informer::getByPk((int)$_GET['edit']);
            $message = json_decode($informer->message);
            $informer->message = $message->message;
            $informer->user_id = $message->user_id;
        }
        X3::app()->module->template->addData(array('informer'=>$informer));
    }
    
    public function execRestore() {
        $tables = array('company_item'=>'Товары компаний','shop_item'=>'Товары в базе','shop_groups'=>'Группы');
        $files = array();
        $file = X3::app()->basePath . DIRECTORY_SEPARATOR . 'dump' . DIRECTORY_SEPARATOR;
        $time = time();
        $ftime = $time-604800;
        foreach($tables as $name=>$table){
            for($i=$ftime;$i<=$time + 84600;$i+=84600){
                $d = date('d_m',$i);
                $tmp = $file . $name . "." . $d . '.sql';
                if(is_file($tmp)){
                    $files[$name][$d] = array('time'=>date('d.m.Y H:i:s',  filemtime($tmp)));
                }
            }
        }
        X3::app()->module->template->addData(array('files' => $files, 'tables'=>$tables));
    }

}

?>
