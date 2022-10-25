<?php
/**
 * @package   T3 Blank
 * @copyright Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>

<?php if ($this->checkSpotlight('spotlight-home2', 'position-4, position-5')) : ?>
	<!-- SPOTLIGHT 2 -->
	<div class="wrap t3-sl t3-sl-1">
    <div class="container">
        
  		<?php $this->spotlight('spotlight-home2', 'position-4, position-5') ?>
    </div>
	</div>
	<!-- //SPOTLIGHT 2 -->
<?php endif ?>