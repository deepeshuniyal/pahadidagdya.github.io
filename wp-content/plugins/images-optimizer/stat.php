<?php

// Restricting Direct Access
defined('ABSPATH') or die(require_once('404.php'));

$statistics = get_post_meta($post->ID, $this->__('data'), true);

// Alpah Image Data
if(isset( $statistics['alpha-image']['impressions'] ) && !empty( $statistics['alpha-image']['impressions'] )) $a_imp = $statistics['alpha-image']['impressions'];
	else $a_imp = '0';

if(isset( $statistics['alpha-image']['clicks'] ) && !empty( $statistics['alpha-image']['clicks'] )) $a_clicks = $statistics['alpha-image']['clicks'];
	else $a_clicks = '0';

// Beta Image Data
if(isset( $statistics['beta-image']['impressions'] ) && !empty( $statistics['beta-image']['impressions'] )) $b_imp = $statistics['beta-image']['impressions'];
	else $b_imp = '0';

if(isset( $statistics['beta-image']['clicks'] ) && !empty( $statistics['beta-image']['clicks'] )) $b_clicks = $statistics['beta-image']['clicks'];
	else $b_clicks = '0';

?>
<br>
<br>
<table style="display:none;">
	<tr>
	<td><label for='<?php echo $this->__('status_id'); ?>'> <?php _e('Activate Featured Image Plugin For this Post', $this->__('text_domain')); ?> </label></td>
	<td><input id='<?php echo $this->__('status_id'); ?>' type= 'checkbox' name= '<?php echo $this->__('status'); ?>' <?php if($status == 'on') echo 'checked';?> /></td>
	</tr>

</table>

<h2><b>Stastics</b></h2>
<table id='statistics-table'>

	<tr> <th></th> <th>Views</th> <th>Clicks</th> </tr>
	<tr><td>Feature Image</td> <td><?php echo $b_imp; ?></td> <td><?php echo $b_clicks; ?></td> </tr>
	<tr><td>A/B Test Featured Image</td> <td><?php echo $a_imp; ?></td> <td><?php echo $a_clicks; ?></td> </tr>

</table>
<div class="reset_data_container" style="display:none;">
	<label for='<?php echo $this->__('reset_id'); ?>'> <?php _e('Reset Data', $this->__('text_domain')); ?> </label>
	<input id='<?php echo $this->__('reset_id'); ?>' type= 'checkbox' name= '<?php echo $this->__('reset'); ?>' />
</div>
<style>

	#statistics-table th{

		padding: 10px;
		text-align: center;
		color: #000;

	}

	#statistics-table tr{

		text-align: center;
		border-bottom: 1px solid #4AEEF0;

	}

</style>
