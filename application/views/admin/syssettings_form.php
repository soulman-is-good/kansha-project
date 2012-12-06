<?php
        if(preg_match('/\[(.+?)\]/', $model->type, $matches)>0){
            $rep = array_shift($matches);
            $arg = array_shift($matches);
            $type = str_replace($rep, "", $model->type);
        }else{
            $type = $model->type;
        }
        switch($type){
            case "integer":               
                echo $this->renderPartial('syssettings_form_string',array('model'=>$model,'class'=>'Syssettings'));
                break;
            case "string":
                echo $this->renderPartial('syssettings_form_string',array('model'=>$model,'class'=>'Syssettings'));
                break;
            case "html":
            case "content":
                echo $this->renderPartial('syssettings_form_content',array('model'=>$model,'class'=>'Syssettings'));
                break;
            case "text":
                echo $this->renderPartial('syssettings_form_text',array('model'=>$model,'class'=>'Syssettings'));
                break;
            case "file":
                echo $this->renderPartial('syssettings_form_file',array('model'=>$model,'class'=>'Syssettings'));
                break;
            default:
                echo $this->renderPartial('syssettings_form_string',array('model'=>$model,'class'=>'Syssettings'));
                break;
        }
?>
