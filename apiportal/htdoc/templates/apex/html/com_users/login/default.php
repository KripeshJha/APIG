<?php
defined('_JEXEC') or die('Restricted access');

JHtml::_('behavior.keepalive');
JHtml::_('jquery.framework');

JLoader::register('ApiPortalHelper',
    JPATH_BASE . DS . 'components' . DS . 'com_apiportal' . DS . 'helpers' . DS . 'apiportal.php');
JLoader::register('ApiPortalValidator',
    JPATH_BASE . DS . 'components' . DS . 'com_apiportal' . DS . 'helpers' . DS . 'validator.php');
JLoader::register('ApiPortalModelLoginAttemptsIp',
    JPATH_BASE . DS . 'components' . DS . 'com_apiportal' . DS . 'models' . DS . 'loginattemptsip.php');

$registrationEnabled = true;
$config = ApiPortalHelper::getAPIManagerAppInfo();
if (property_exists($config, 'registrationEnabled')) {
    $registrationEnabled = $config->registrationEnabled;
}

$maxFieldLen = ApiPortalValidator::MAX_FIELD_LEN;

$requiredMsg = JText::_('JGLOBAL_FIELD_REQUIRED');
$maxLengthMsg = str_replace('%s', '{0}', JText::_('JGLOBAL_FIELD_TOO_LONG'));

$signUpURL = JRoute::_('index.php?option=com_apiportal&view=registration', false);
$resetURL = JRoute::_('index.php?option=com_apiportal&view=reset', false);
$loginURL = JRoute::_('index.php?options=com_users&task=user.login', false);

$landingURL = JURI::base(false) . 'index.php?option=com_apiportal&task=landing.page';

$app = JFactory::getApplication();

// Check for any existing login data from a previous login attempt
$data = array('username' => '');
$formData = $app->getUserState('users.login.form.data', null);
if ($formData) {
    $data = array_merge($data, $formData);
}
$app->setUserState('users.login.form.data', null);

// Check for a return value override in the URL
$return = $app->getInput()->get('return', null, 'STRING');
$return = $this->escape($return);
if ($return) {
    // URL value overrides any preset default return value
    $data['return'] = $return;
} else {
    $data['return'] = base64_encode($landingURL);
}

/*
 * If 'state' is set, we've been redirected here via ApiPortalHelper::checkSession()
 * or the overridden JApplicationSite->authorise().
 */
$state = $app->getInput()->get('session', null, 'STRING');
if ($state) {
    if ($state == 'expired') {
        $app->enqueueMessage(JText::_('JGLOBAL_SESSION_TIMEOUT'), 'notice');
    } else {
        $app->enqueueMessage(JText::_('JGLOBAL_SESSION_ERROR'), 'notice');
    }
}


$error = $app->getInput()->get('session', null, 'STRING');
if ($error == 'invalid_data') {
    $app->enqueueMessage(JText::_('JGLOBAL_AUTH_INVALID_PASS'), 'warning');
}

$message = $app->input->getString('msg');

if ($message == 'password_changed') {
    $app->enqueueMessage(JText::_('COM_APIPORTAL_PASSWORD_CHANGED'));
}

if ($message == 'password_reset_success') {
    $app->enqueueMessage(JText::_('COM_APIPORTAL_RESET_SUCCESS'));
}

if ($message == 'registration_success') {
    $app->enqueueMessage(JText::_('COM_APIPORTAL_REGISTRATION_VALIDATION_SUCCESS'));
}

if ($message == 'apicatalog_failed') {
    $app->enqueueMessage(JText::_('COM_APIPORTAL_APICATALOG_LOADING_FAILED'), 'error');
}

if ($message == 'applications_failed') {
    $app->enqueueMessage(JText::_('COM_APIPORTAL_APPLICATIONS_LOADING_FAILED'), 'error');
}
// For input validation
$document = JFactory::getDocument();
$document->addScript('components/com_apiportal/assets/js/jquery.validate.min.js');
?>

<div class="col col-md-12">
    <h1><?= $this->params->get('show_page_heading') ? $this->escape($this->params->get('page_heading')) : JText::_('COM_USERS_SIGN_IN_TITLE'); ?></h1>
    <jdoc:include type="modules" name="login-message-consent" style="none"/>
</div>
<div class="col col-md-6">
    <?php if($this->params->get('logindescription_show') && trim($this->params->get('login_description'))) { ?>
        <p class="login-description">
            <?= $this->params->get('login_description') ?>
        </p>
    <?php } ?>
    <form id="login-form" method="post" action="<?php echo $loginURL; ?>" novalidate>
        <div class="form-group">
            <label class="control-label" for="username">
                <?php echo JText::_('COM_APIPORTAL_USER_VIEW_LOGIN_NAME_LABEL'); ?>:
            </label>
            <input type="text" class="form-control" tabindex='1' id="username" name="username"
                   value="<?php echo $this->escape($data['username']); ?>"/>
            <div class="validation-message"></div>
            <?php if ($registrationEnabled) { ?>
                <?php $tabindex = 5; ?>
                <span class="help-block">
      <?php echo JText::_('COM_USERS_SIGN_IN_SIGN_UP_HELP'); ?>
            <a href="<?php echo $signUpURL; ?>" tabindex='4'>
        <?php echo JText::_('COM_USERS_SIGN_IN_SIGN_UP_LINK_LABEL'); ?>
      </a>
    </span>
            <?php } else { ?>
                <?php $tabindex = 4; ?>
            <?php } ?>
        </div><!-- .form-group -->
        <div class="form-group">
            <label class="control-label" for="password">
                <?php echo JText::_('COM_USERS_SIGN_IN_PASSWORD_LABEL'); ?>:
            </label>
            <input type="text" class="form-control" tabindex='2'
                   id="password" name="password" autocomplete="off"/>
            <div class="validation-message"></div>
            <?php if (isset($config->resetPasswordEnabled) && $config->resetPasswordEnabled) { ?>
                <span class="help-block">
              <a href="<?php echo $resetURL; ?>" tabindex='<?php echo $tabindex++; ?>'>
                <?php echo JText::_('COM_USERS_SIGN_IN_RESET_LINK_LABEL'); ?>
              </a>
            </span>
            <?php } ?>
        </div><!-- .form-group -->
        <?php if ($this->tfa) : ?>
            <div class="form-group">
                <label class="control-label" for="secretkey" id="secretkey-lbl">
                    <?php echo JText::_('JGLOBAL_SECRETKEY'); ?>:
                </label>
                <input type="text" class="form-control" tabindex='2' id="secretkey" name="secretkey" autocomplete="off" size="25"/>
                <div class="validation-message"></div>
                <span class="help-block">
            <?php echo JText::_('COM_USERS_OPTIONAL'); ?>
        </span>
            </div><!-- .form-group -->
        <?php endif; ?>

        <div class="form-group">
            <button type="submit" class="btn btn-primary" id="btn-login" tabindex='3'>
                <?php echo JText::_('COM_USERS_SIGN_IN_PRIMARY_ACTION_LABEL'); ?>
            </button>
        </div><!-- .form-group -->

        <?php echo JHtml::_('form.token'); ?>
        <input type="hidden" name="return" value="<?php echo $data['return']; ?>"/>
        <?php
        $config = ApiPortalHelper::getContainer()->get(ApiPortalConfiguration::class);
        $attemptsAllowed = (int)$config->getAttemptsBeforeRecaptcha();
        $lockUser = $config->getLockUser();
        $lockByIp = $config->getLockByIp();
        $showCaptcha = false;

        // Recaptcha is displayed only when locking is enabled and there is entered username already
        // OR locking by IP is enabled. In that way recaptcha can be displayed for locked by IP user
        // even when the form is not sent (Closing current tab in browser and opening new one for example).
        if ((!empty($data["username"]) && $lockUser) || ($lockUser && $lockByIp)) {
            if (!empty($data["username"])) {
                $apiPortalModelLoginAttempts = new ApiPortalModelLoginAttempts(JFactory::getDbo());
                $loginAttempts = $apiPortalModelLoginAttempts->getLoginAttempts($data["username"]);
            }

            if ($lockByIp) {
                $apiPortalModelLoginAttemptsIp = new ApiPortalModelLoginAttemptsIp(JFactory::getDbo());
                $loginAttemptsIp = $apiPortalModelLoginAttemptsIp->getLoginAttempts($_SERVER['REMOTE_ADDR']);
            }

            if ((!empty($loginAttempts->attempts) && (int)$loginAttempts->attempts >= $attemptsAllowed)
                || (!empty($loginAttemptsIp->attempts) && (int)$loginAttemptsIp->attempts >= $attemptsAllowed)) {
                JPluginHelper::importPlugin('captcha');
                $plugin = JPluginHelper::getPlugin('captcha', 'recaptcha');

                if ($plugin) {
                    $params = !empty($plugin) ? new JRegistry($plugin->params) : null;
                    $showCaptcha = true;

                    JFactory::getApplication()->triggerEvent('onInit', ['login_custom_recaptcha']);
                    JFactory::getApplication()->triggerEvent('onDisplay', [null, 'login_custom_recaptcha', 'class=""']);
                }
            }
        }
        ?>

        <?php if ($showCaptcha): ?>
            <div class="form-group">
                <div id="login_custom_recaptcha" class="g-recaptcha  required"
                     data-sitekey="<?php echo $params->get('public_key', ''); ?>"
                     data-theme="<?php echo $params->get('theme2', ''); ?>"
                     data-size="<?php echo $params->get('size', ''); ?>"></div>
            </div><!-- .form-group -->
        <?php endif; ?>
    </form>
</div>
<div class="col col-md-6">
    <?php if($this->params->get('login_image')) { ?>
        <img src="<?php echo $this->escape($this->params->get('login_image')); ?>" class="login-image center-block img-responsive" alt="<?= JText::_('COM_USERS_LOGIN_IMAGE_ALT'); ?>" />
    <?php } ?>
</div>
<script src="<?= JUri::base() ?>/components/com_apiportal/assets/js/apiportal.js"></script>
<script type="text/javascript" nonce="<?= $_SERVER["HTTP_CSP_NONCE"] ?>">
    // jQuery is loaded in 'noconflict' mode.
    jQuery(document).ready(function ($) {
        $("#password").attr('type','password');

        $('#login-form').validate({
            rules: {
                username: {
                    email: false,
                    required: true,
                    maxlength: <?php echo $maxFieldLen; ?>
                },
                password: {
                    requiredWithWhitespace: true
                }
            },
            messages: {
                username: {
                    requiredWithWhitespace: '<?php echo $requiredMsg; ?>',
                    maxLengthWithWhitespace: '<?php echo sprintf($maxLengthMsg, $maxFieldLen); ?>'
                },
                password: {
                    requiredWithWhitespace: '<?php echo $requiredMsg; ?>'
                },
            },
            onkeyup: false,
            errorPlacement: function (label, elem) {
                elem.next('.validation-message').append(label);
            }
        });
    });
</script>
