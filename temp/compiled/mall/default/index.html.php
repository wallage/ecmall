<?php echo $this->fetch('header.html'); ?>

<div class="keyword">
    <div class="keyword1"></div>
    <div class="keyword2"></div>
    热门搜索:
    <?php $_from = $this->_var['hot_keywords']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'keyword');if (count($_from)):
    foreach ($_from AS $this->_var['keyword']):
?>
    <a href="<?php echo url('app=search&keyword=' . $this->_var['keyword']. ''); ?>"><?php echo $this->_var['keyword']; ?></a>
    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
</div>

<div class="content">
    <div class="left" area="top_left" widget_type="area">
        <?php $this->display_widgets(array('page'=>'index','area'=>'top_left')); ?>
    </div>

    <div class="right">
        <div class="main">
            <div id="module_middle" area="cycle_image" widget_type="area">
                <?php $this->display_widgets(array('page'=>'index','area'=>'cycle_image')); ?>
            </div>

            <div class="sidebar" area="sales" widget_type="area">
                <?php $this->display_widgets(array('page'=>'index','area'=>'sales')); ?>
            </div>
        </div>

        <div area="top_right" widget_type="area">
            <?php $this->display_widgets(array('page'=>'index','area'=>'top_right')); ?>
        </div>

    </div>
</div>
<div class="clear"></div>
<div class="ad_banner" area="banner" widget_type="area">
    <?php $this->display_widgets(array('page'=>'index','area'=>'banner')); ?>
</div>

<div class="content">
    <div class="left" area="bottom_left" widget_type="area">
        <?php $this->display_widgets(array('page'=>'index','area'=>'bottom_left')); ?>
    </div>

    <div class="right" widget_type="area" area="bottom_right">
        <?php $this->display_widgets(array('page'=>'index','area'=>'bottom_right')); ?>
    </div>
</div>

<div class="clear"></div>
<div class="content" area="bottom_down" widget_type="area">
    <?php $this->display_widgets(array('page'=>'index','area'=>'bottom_down')); ?>
</div>

<?php echo $this->fetch('footer.html'); ?>