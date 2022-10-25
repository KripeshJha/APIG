<?php
/**
 * @package   T3 Blank
 * @copyright Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>
<!-- BACK TOP TOP BUTTON -->
<div id="back-to-top" data-spy="affix" data-offset-top="300" class="back-to-top hidden-xs hidden-sm affix-top">
    <button class="btn btn-primary ico-chevron-up" title="Back to Top"></button>
</div>
<script type="text/javascript" nonce="<?= $_SERVER["HTTP_CSP_NONCE"] ?>">
    (function ($) {
        // Back to top
        $('#back-to-top').on('click', function () {
            $("html, body").animate({scrollTop: 0}, 500);
            return false;
        });
    })(jQuery);
</script>
<!-- BACK TO TOP BUTTON -->

<!-- FOOTER -->
<footer id="t3-footer" class="wrap t3-footer"> <!-- class="navbar-fixed-bottom" -->

    <section class="t3-copyright" style="text-align: center">
        <div class="container">
            <div class="row">
                <small>
                    <jdoc:include type="modules" name="<?php $this->_p('footer') ?>" />
                </small>

            </div>
        </div>
    </section>

</footer>

