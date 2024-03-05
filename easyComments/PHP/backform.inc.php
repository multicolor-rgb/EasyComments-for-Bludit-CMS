<?php
$fileLog = PATH_CONTENT . 'easyCommentsLog.txt';
global $L;
 
global $security;
$tokenCSRF = $security->getTokenCSRF();
?>

<div style="width:100%;background:#fafafa;border:solid 1px #ddd;padding:10px;box-sizing:border-box;margin-bottom:20px;">
<?php echo $L->get('place');?> <code style='background:#000;padding:5px;margin:0 5px;color:#FAFA33;font-weight:bold;'>&lt;?php easyComments();?&gt;</code> 
</div>

<h4><?php echo $L->get('log'); ?></h4>


<div style="width:100%;height:auto;padding:5px;background:#000;color:#fff;line-height:1.7;margin-bottom:10px;">

    <?php

    echo @file_get_contents($fileLog) == '' ? 'Nothing here yet' : file_get_contents($fileLog); ?>



</div>


<form method="post">
<input type="hidden" id="jstokenCSRF" name="tokenCSRF" value="<?php echo $tokenCSRF;?>">
    <input type="submit" name="deletelog" style="border:solid 1px;padding:5px 15px;background:#333;color:#fff;
    display:inline-block;border-radius:5px;text-decoration:none;" value="<?php echo $L->get('clearlog'); ?>">
</form>
<br><br>


<h4><?php echo $L->get('enteremail'); ?></h4>

<form method="post">
<input type="hidden" id="jstokenCSRF" name="tokenCSRF" value="<?php echo $tokenCSRF;?>">

    <input type="text" style="width:100%;padding:10px;box-sizing:border-box;margin-bottom:5px;" value="<?php echo @file_get_contents(PATH_CONTENT . 'easyCommentsMail.txt'); ?>" placeholder="" name="adminemail">

    <input type="submit" name="saveadminemail" style="border:solid 1px;padding:5px 15px;background:#333;color:#fff;display:inline-block;border-radius:5px;text-decoration:none;margin-bottom:20px;" value="<?php echo  $L->get('SAVEEMAIL'); ?>">
</form>


<script type='text/javascript' src='https://storage.ko-fi.com/cdn/widget/Widget_2.js'></script><script type='text/javascript'>kofiwidget2.init('Support Me on Ko-fi', '#29abe0', 'I3I2RHQZS');kofiwidget2.draw();</script> </div>