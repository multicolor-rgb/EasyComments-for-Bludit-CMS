<?php
$fileLog = PATH_CONTENT . 'easyCommentsLog.txt';
global $L;

global $security;
$tokenCSRF = $security->getTokenCSRF();
?>

<h3>EasyComments</h3>
<hr>
<div class="p-2 bg-light border mb-3">
    <?php echo $L->get('place'); ?> <code style='background:#000;padding:5px;margin:0 5px;color:#FAFA33;font-weight:bold;'>&lt;?php easyComments();?&gt;</code>
</div>

<h4><?php echo $L->get('log'); ?></h4>


<div class="p-2 bg-light border mb-3">

    <?php

    echo @file_get_contents($fileLog) == '' ? 'Nothing here yet' : file_get_contents($fileLog); ?>



</div>


<form method="post">
    <input type="hidden" id="jstokenCSRF" name="tokenCSRF" value="<?php echo $tokenCSRF; ?>">
    <input type="submit" name="deletelog" class="btn btn-primary mb-3"  value="<?php echo $L->get('clearlog'); ?>">
</form>
<br><br>


<h4><?php echo $L->get('enteremail'); ?></h4>

<form method="post">
    <input type="hidden" id="jstokenCSRF" name="tokenCSRF" value="<?php echo $tokenCSRF; ?>">

    <input type="text"  class="form form-control mb-3" value="<?php echo @file_get_contents(PATH_CONTENT . 'easyCommentsMail.txt'); ?>" placeholder="" name="adminemail">

    <input type="submit" name="saveadminemail"  class="btn btn-primary mb-3" value="<?php echo  $L->get('SAVEEMAIL'); ?>">
</form>

<hr>
<script type='text/javascript' src='https://storage.ko-fi.com/cdn/widget/Widget_2.js'></script>
<script type='text/javascript'>
    kofiwidget2.init('Support Me on Ko-fi', '#29abe0', 'I3I2RHQZS');
    kofiwidget2.draw();
</script>
</div>