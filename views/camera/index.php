<?php
use yii\helpers\Url;
use app\models\Camera;;

$get = Yii::$app->request->get();

$camera = Camera::find()->where(['id' =>$get['id']])->one();
if(!$camera->public){
    return false;
}
$link_url = Url::to(['/public_cabinet/camera/index'], true) . '?token=' .$camera->getToken().'&view=one';

$image = $camera->getLastImage();
$image_date = date("d.m.Y H:i:s", strtotime($image->created));
$image_src = $image->getThumbnailUrl();


$border_color = isset($get['bc']) ? $get['bc'] : "C6E0DD";
$border_radius = isset($get['br']) ? $get['br'] : "2";
$text_size = isset($get['ts']) ? $get['ts'] : "14";
$padding = isset($get['ts']) ? $get['ts'] - 2 : "12";
$text_color = isset($get['tc']) ? $get['tc'] : "548C98";
$width = isset($get['w']) ? $get['w'] : "200";

?>

var script_wrapper = document.getElementById('timephoto-camera-<?=$camera->id?>');
var a_wrapper = document.createElement('a');
a_wrapper.href = "<?=$link_url?>"
a_wrapper.style.cssText = "display:block; width:<?=$width?>px; border:2px solid #<?=$border_color?>; border-radius:<?=$border_radius?>px; position:relative; padding: <?=$padding?>px 0;";

img = document.createElement("IMG");
img.src = "<?=$image_src?>";
img.style.cssText = "width:<?=$width?>px; height:auto;"

hint = document.createElement("DIV");
hint.style.cssText = "width:<?=$width?>px; position:absolute; bottom:0; padding-top:3px; background:#<?=$border_color?>; text-align:center; font:<?=$text_size?>px/<?=$text_size?>px Sans-serif; color:#<?=$text_color?>;";
hint.innerHTML = "<?=$image_date?>";

title = document.createElement("DIV");
title.style.cssText = "width:<?=$width?>px; position:absolute; top:0; padding-bottom:3px; background:#<?=$border_color?>; text-align:center; font:<?=$text_size?>px/<?=$text_size?>px Sans-serif; color:#<?=$text_color?>;";
title.innerHTML = "<?=$camera->name?>";

a_wrapper.appendChild(title);
a_wrapper.appendChild(img);
a_wrapper.appendChild(hint);

script_wrapper.parentNode.insertBefore(a_wrapper, script_wrapper.nextSibling);


