<div class="container">
    <div class="page-header">
        <h2><?php echo $this->pageTitle?></h2>
    </div>
    <div class="container">
        <?php $this->renderPartial('detailView/navMenu'); ?>
    </div>
    <div class="detail-view-pad-top">&nbsp;</div>
    <div id="dataContainer" class="container" style="overflow-x:auto">
        <div style="font-size:32px;font-weight: bold;color:#F00;">
            <?php if (is_array($errors)):?>
                <?php foreach ($errors as $_thisError): ?>
                    -&nbsp;<?php echo $_thisError; ?><br>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>