<?php
/**
 * @package   T3 Blank
 * @copyright Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>

<?php if ($this->checkSpotlight('spotlight-home1', 'position-1, position-9')) : ?>
	<!-- SPOTLIGHT 1 -->
	<div class="wrap t3-sl t3-sl-1">
    <div class="container">
  		<?php $this->spotlight('spotlight-home1', 'position-1, position-9') ?>
    </div>
	</div>
	<!-- //SPOTLIGHT 1 -->
<?php endif ?>