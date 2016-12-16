<?php
/**
 * The Sidebar containing the main widget areas.
 */
?>
<div id="sidebar" class="clearfix">
        <div class="col-lg-3 col-lg-pull-6">
            <aside id="left-widget" class="widget-container">
                <?php if ( is_active_sidebar( 'left-sidebar' ) ) { ?>
                    <?php dynamic_sidebar( 'left-sidebar' ); ?>
                <?php } ?>
            </aside>
        </div>
        <div class="col-md-3 ">
            <aside id="right-widget" class="widget-container">
                <?php if ( is_active_sidebar( 'rigth-sidebar' ) ) { ?>
                    <?php dynamic_sidebar( 'rigth-sidebar' ); ?>
                <?php }  ?>

            </aside>
        </div>
</div>

<!-- END sidebar -->