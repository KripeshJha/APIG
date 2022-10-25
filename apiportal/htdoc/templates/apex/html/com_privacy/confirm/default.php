<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_privacy
 *
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/** @var PrivacyViewConfirm $this */

JHtml::_('behavior.keepalive');
JHtml::_('behavior.formvalidator');

$confirmToken = JFactory::getApplication()->input->get('confirm_token', '', 'STRING');
?>
<div class="request-confirm<?php echo $this->pageclass_sfx; ?> com_apiportal">
    <?php if ($this->params->get('show_page_heading')) : ?>
        <div class="head">
            <h1 class="auto">
                <?php echo $this->escape($this->params->get('page_heading')); ?>
            </h1>
            <p class="auto">
                <em><?php echo $this->escape($this->params->get('masthead-slogan')); ?></em>
            </p>
        </div>
    <?php endif; ?>
    <div class="body auto">
        <form action="<?php echo JRoute::_('index.php?option=com_privacy&task=request.confirm'); ?>" method="post" class="form-validate form-horizontal well">
            <div class="form-group">
                <label class="col-sm-2 control-label" for="jform_email">
                    <?= JText::_('COM_APIPORTAL_COM_PRIVACY_FIELD_EMAIL_LABEL') ?>
                </label>
                <div class="col-sm-4">
                    <input type="email" class="form-control" id="jform_email" name="jform[email]" required>
                    <div class="validation-message"></div>
                </div>
            </div><!-- .form-group -->
            <div class="form-group">
                <label class="col-sm-2 control-label" for="jform_confirm_token">
                    <?= JText::_('COM_PRIVACY_FIELD_CONFIRM_CONFIRM_TOKEN_LABEL') ?>
                </label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" id="jform_confirm_token" name="jform[confirm_token]" value="<?= $confirmToken?>" required>
                    <div class="validation-message"></div>
                </div>
            </div><!-- .form-group -->

            <div class="control-group">
                <div class="controls">
                    <button type="submit" class="btn btn-primary validate">
                        <?php echo JText::_('JSUBMIT'); ?>
                    </button>
                </div>
            </div>
            <?php echo JHtml::_('form.token'); ?>
        </form>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.2/dist/jquery.validate.min.js"></script>
<script nonce="<?= $_SERVER["HTTP_CSP_NONCE"] ?>">
    jQuery(document).ready(function() {
        jQuery('.request-confirm form').validate({
            ignore: [],
            rules: {
                'email': {
                    email: true,
                    required: true,
                }
            },
            onkeyup: false,
            errorPlacement: function (label, elem) {
                elem.closest('div').parent().find('.validation-message').append(label);
            }
        })
    });

</script>

