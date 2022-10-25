<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_privacy
 *
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/** @var PrivacyViewRequest $this */

JHtml::_('behavior.keepalive');
JHtml::_('behavior.formvalidator');

?>
<div class="request-form<?php echo $this->pageclass_sfx; ?> com_apiportal">
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
    <?php if ($this->sendMailEnabled) : ?>
        <div class="body auto">
            <form action="<?php echo JRoute::_('index.php?option=com_privacy&task=request.submit'); ?>" method="post" class="form-validate form-horizontal well">
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="email">
                        <?= JText::_('COM_APIPORTAL_COM_PRIVACY_FIELD_EMAIL_LABEL') ?>
                    </label>
                    <div class="col-sm-3">
                        <input type="email" class="form-control" id="jform_email" name="jform[email]">
                        <div class="validation-message"></div>
                    </div>
                </div><!-- .form-group -->
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="role">
                        <?= JText::_('COM_PRIVACY_FIELD_REQUEST_TYPE_LABEL') ?>
                    </label>
                    <div class="col-sm-3">
                        <select class="form-control" id="jform_request_type" name="jform[request_type]">
                            <option value='export'><?= JText::_('COM_PRIVACY_REQUEST_TYPE_EXPORT') ?></option>
                            <option value='remove'><?= JText::_('COM_PRIVACY_REQUEST_TYPE_REMOVE') ?></option>
                        </select>
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
    <?php else : ?>
        <div class="alert alert-warning">
            <p><?php echo JText::_('COM_PRIVACY_WARNING_CANNOT_CREATE_REQUEST_WHEN_SENDMAIL_DISABLED'); ?></p>
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.2/dist/jquery.validate.min.js"></script>
<script nonce="<?= $_SERVER["HTTP_CSP_NONCE"] ?>">
    jQuery(document).ready(function() {
        jQuery('.request-form form').validate({
            ignore: [],
            rules: {
                'jform[email]': {
                    email: true,
                    required: true,
                }
            },
            onkeyup: false,
            messages: {
                'jform[email]': {
                    email: '<?= JText::_("JGLOBAL_FIELD_INVALID_EMAIL") ?>',
                    required: '<?= JText::_("JGLOBAL_FIELD_REQUIRED") ?>'
                }
            },
            errorPlacement: function (label, elem) {
                elem.closest('div').parent().find('.validation-message').append(label);
            }
        })
    });

</script>