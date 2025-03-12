<?php /* @var $this Controller */ ?>
<?php $this->beginContent('//layouts/main');?>
<?php if ($this->menu): ?>
    <div id="sidebar">
        <?php if (count($this->menu) > 0): ?>
            <?php foreach ($this->menu as $item): ?>
                <?php echo CHtml::link($item['label'], $item['url'], array('class' => 'btn btn-primary')); ?>
            <?php endforeach; ?>
        <?php endif; ?>
    </div><!-- sidebar -->
<?php endif; ?>
    <div id="content">
        <?php echo $content; ?>
    </div><!-- content -->
<?php $this->endContent(); ?>