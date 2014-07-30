<?php 
    $menuConfig     = Yii::app()->params['menuConfig'];
    $controllerID   = strtolower(Yii::app()->controller->id);
?>

<ul class="nav nav-pills nav-stacked">
    <li class="v-menu-header"><a href="#">Menu</a></li>
    <?php if ($menuConfig): ?>
        <?php foreach($menuConfig as $thisMenuItem):?>
            <?php $visible = (array)$thisMenuItem['visible']; ?>
            <?php if ($visible[0] == '*' ||  $this->adminType == 'super' || in_array($this->adminType,$visible)): ?>

                <?php if (sizeof($thisMenuItem['child']) <= 0): ?>
                    <li class="<?php echo ($controllerID == strtolower($thisMenuItem['controller'])) ? 'active' : '' ;?>">
                        <a href="<?php echo $this->createUrl($thisMenuItem['controller'].'/'.$thisMenuItem['action']); ?>"><?php echo $thisMenuItem['label']; ?></a>
                    </li>
                <?php else:?>
                    <li class="dropdown <?php echo ($controllerID == strtolower($thisMenuItem['controller'])) ? 'active' : '' ;?>">
                        <a href="<?php echo ($thisMenuItem['controller'] != '') ? $this->createUrl($thisMenuItem['controller'].'/'.$thisMenuItem['action']) : '';?>" class="dropdown-toggle" data-toggle="dropdown">
                            <?php echo $thisMenuItem['label']; ?><b class="caret"></b>
                        </a>
                        <ul class="dropdown-menu ">
                            <?php foreach($thisMenuItem['child'] as $thisChildMenuItem):?>
                                <?php $visible = (array)$thisChildMenuItem['visible']; ?>
                                <?php if ($visible[0] == '*' ||  $this->adminType == 'super' || in_array($this->adminType,$visible)): ?>
                                    <li>
                                        <a href="<?php echo $this->createUrl($thisChildMenuItem['controller'].'/'.$thisChildMenuItem['action'],(array)$thisChildMenuItem['query']); ?>">
                                            <?php echo $thisChildMenuItem['label']; ?>
                                        </a>
                                    </li>
                                <?php endif; ?>
                            <?php endforeach;?>
                        </ul>
                    </li> 
                <?php endif; ?>
            <?php endif; ?>
        <?php endforeach?>
    <?php endif; ?>
    <li><a href="<?php echo $this->createUrl('Site/Logout'); ?>">Logout</a></li>
</ul>
