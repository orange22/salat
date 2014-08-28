<?php /** @var $data array */ ?>
<ul>
    <?php foreach($data['fixed'] as $item) { ?>
        <li><?php echo $item ?></li>
    <?php } ?>
    <?php foreach($data['lang'] as $lang => $items) { ?>
        <em><?php echo $lang; ?></em>
        <?php foreach($items as $item) { ?>
            <li><?php echo $item ?></li>
        <?php } ?>
    <?php } ?>
</ul>