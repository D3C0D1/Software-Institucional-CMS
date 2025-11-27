<?php
/**
 * @package		SP Flickr Gallery
 * @copyright	Copyright (C) 2010 - 2015 JoomShaper. All rights reserved.
 * @license		GNU General Public License version 2 or later; 
 */

// no direct access
defined('_JEXEC') or die;

?>

<div class="sp-flickr-gallery <?php echo $params->get('moduleclass_sfx'); ?>">
	<?php if($params->get('flickr_id')) {?>
	<div class="sp-flickr-gallery-content">
		<ul class="sp-flickr-gallery" data-id="<?php echo $params->get('flickr_id'); ?>" data-count="<?php echo $image_limit; ?>" data-setid="<?php echo $params->get('flickr_setid'); ?>">
		</ul>
	</div>
	<?php } else{ ?>
		<p>Please insert your flickr id To find your flickr ID visit  <a href="http://idgettr.com/">idGettr</a>. </p>
		<p><small>SP Flickr Gallery Module By <a target="_blank" href="http://www.joomshaper.com">JoomShaper</a></small></p>
	<?php }?>
</div>