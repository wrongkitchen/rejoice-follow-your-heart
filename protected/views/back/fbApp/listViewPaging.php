<?php if ($listData['rowsData']): ?>
    <?php $paging = $listData['paging']; ?>
    <ul class="pagination pagination-sm">
        <li class="<?php echo ($paging['prevPage']) ? '': 'disabled' ?>">
            <a href="javascript:;" <?php if($paging['prevPage']): ?> onClick="loadListContent('<?php echo $paging['prevPage']?>')"<?php endif;?>>«</a>
        </li>
        <?php if($paging['pageArray']): ?>
            <?php foreach($paging['pageArray'] as $page) : ?>
                <li class="<?php echo ($page == $paging['currentPage']) ? 'disabled' : '';?>">
                    <a href="javascript:;" <?php if($page != $paging['currentPage']): ?> onclick="loadListContent('<?php echo $page; ?>');"<?php endif;?>><?php echo $page; ?></a>
                </li>
            <?php endforeach; ?>
        <?php endif;?>
        <li class="<?php echo ($paging['nextPage']) ? '': 'disabled' ?>">
            <a href="javascript:;" <?php if($paging['nextPage']): ?> onClick="loadListContent('<?php echo $paging['nextPage']?>')"<?php endif;?>>»</a>
        </li>
    </ul>
<?php endif; ?>