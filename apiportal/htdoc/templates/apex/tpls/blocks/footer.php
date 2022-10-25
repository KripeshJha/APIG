<?php
/**
 * @package   T3 Blank
 * @copyright Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$ppAcceptUrl = 'index.php?option=com_apiportal&task=user.acceptPrivacyPolicy';
$ppDeclineUrl = 'index.php?option=com_apiportal&task=user.declinePrivacyPolicy';
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

    <?php if ($this->checkSpotlight('footer-sl', 'footer-1, footer-2, footer-3, footer-4, footer-5, footer-6')) : ?>
        <!-- FOOTER SPOTLIGHT -->
        <div class="container hidden-xs">
            <?php $this->spotlight('footer-sl', 'footer-1, footer-2, footer-3, footer-4, footer-5, footer-6') ?>
        </div>
        <!-- //FOOTER SPOTLIGHT -->
    <?php endif ?>

    <section class="t3-copyright">
        <div class="container">
            <div class="row">
                <div class="<?php echo $this->getParam('t3-rmvlogo',
                    1) ? 'col-md-8' : 'col-md-12' ?> copyright <?php $this->_c('footer') ?>">
                    <jdoc:include type="modules" name="<?php $this->_p('footer') ?>"/>
                </div>
                <?php if ($this->getParam('t3-rmvlogo', 1)): ?>
                    <div class="col-md-4 poweredby text-hide">
                        <a class="t3-logo t3-logo-color" href="http://t3-framework.org" title="Powered By T3 Framework"
                           target="_blank" <?php echo method_exists('T3',
                            'isHome') && T3::isHome() ? '' : 'rel="nofollow"' ?>>Powered by <strong>T3
                                Framework</strong></a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

</footer>
<!-- //FOOTER -->

<?php
$container = ApiPortalHelper::getContainer();

$user = $container->get(\ApiPortal\User\UserInterface::class);
$privacyPolicy = $container->get(\ApiPortal\PrivacyPolicy\PrivacyPolicyInterface::class);
$privacyPolicyArticleId = $privacyPolicy->getArticle()->getId();
$showPrivacyPolicyPopUp = $privacyPolicyArticleId !== \ApiPortal\PrivacyPolicy\PrivacyPolicyInterface::NO_PRIVACY_POLICY_ARTICLE_ID && !$privacyPolicy->acceptedByUser($user) && $user->isLoggedIn();
?>

<?php if ($showPrivacyPolicyPopUp) { ?>
    <!-- PP Modal -->
    <div class="modal fade" id="pp-modal-dialog" tabindex="-1" role="dialog" aria-labelledby="pp-modal-label"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="pp-modal-label">
                        <?php echo JText::_('COM_USERS_SIGN_IN_PRIVACY_POLICY_DIALOG_TITLE'); ?>
                    </h3>
                </div><!-- .modal-header -->

                <div class="modal-body">
                    <div id="pp-modal-content"><?= $privacyPolicy->getArticle()->getText(); ?></div>
                    <div class="dialog-actions">
                        <a class="btn btn-success" href="#" id="acceptPP">
                            <?php echo JText::_('Accept'); ?>
                        </a>
                        <a class="btn btn-danger" href="#" id="declinePP">
                            <?php echo JText::_('Decline'); ?>
                        </a>
                    </div>
                </div>

                <div class="modal-footer"></div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <script nonce="<?= $_SERVER["HTTP_CSP_NONCE"] ?>">
        (function ($) { // privacy policy
            var $ppModal = $('#pp-modal-dialog').modal({
                backdrop: 'static',
                keyboard: false,
            });

            $ppModal.modal('show');

            $('#acceptPP').on('click', function (event) {
                event.preventDefault();

                $.apiportal.post('<?= $ppAcceptUrl; ?>', {
                    "<?= \Joomla\CMS\Session\Session::getFormToken() ?>": '1'
                })
                    .done(function (data) {
                        var parsedData = JSON.parse(data);

                        // if (parsedData.result != 'ACCEPTED') {
                        location.reload(true);
                        return;
                        // }

                        // TODO: do something for other responses
                    })
                    .fail(function (error) {
                        console.log('Internal server error');
                        console.error(error);
                    });
            });

            $('#declinePP').on('click', function (event) {
                event.preventDefault();

                $.apiportal.post('<?= $ppDeclineUrl; ?>', {
                    "<?= \Joomla\CMS\Session\Session::getFormToken() ?>": '1'
                })
                    .done(function (data) {
                        var parsedData = JSON.parse(data);

                        // if (parsedData.result != 'ACCEPTED') {
                        location.reload(true);
                        return;
                        // }

                        // TODO: do something for other responses
                    })
                    .fail(function (error) {
                        console.log('Internal server error');
                        console.error(error);
                    });
            });
        })(jQuery);
    </script>
<?php } ?>
