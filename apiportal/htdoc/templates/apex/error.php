<?php
defined('_JEXEC') or die;

$expiredSession = false;
//IAP - 714, expired session
if ($this->error instanceof Pest_Unauthorized) {
    JFactory::getApplication()->logout();
    $expiredSession = true;
    $expiredSessionMsg = ' ' . JText::_('COM_APIPORTAL_PEST_UNAUTH_EXCEPTION') . ' ' . JText::_('COM_APIPORTAL_PEST_UNAUTH_USERMSG');
} else {
    if (!isset($this->error)) {
        $this->error = JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
        $this->debug = false;
    }
}
//get language and direction
$doc = JFactory::getDocument();
$this->language = $doc->language;
$this->direction = $doc->direction;

$errorImage = '<div style="margin-bottom:50px">
					<img style="max-width: 250px;display: block;margin-left: auto;margin-right: auto" src="' . $this->baseurl . '/templates/purity_iii/images/error-page.png" alt="Page Not Found"/>
				</div>';

$debug = JFactory::getConfig()->get('debug_lang');
$reporting = JFactory::getConfig()->get('error_reporting');

if ($reporting !== 'none' && ($reporting !== 'default' || ini_get('display_errors'))) {
    $errorImage = null;
    $headerTitle = $expiredSession ? JText::_('COM_APIPORTAL_PEST_UNAUTH_EXCEPTION') : $this->error->getCode() . '-' . htmlspecialchars($this->error->getMessage());
    $status = $expiredSession ? 401 : false;
    $mainTitle = $expiredSession ? $expiredSessionMsg : $this->error->getCode() . '-' . htmlspecialchars($this->error->getMessage());
    $body = '<p><strong>' . JText::_('JERROR_LAYOUT_NOT_ABLE_TO_VISIT') . '</strong></p>
	<ol>
		<li>' . JText::_('JERROR_LAYOUT_AN_OUT_OF_DATE_BOOKMARK_FAVOURITE') . '</li>
		<li>' . JText::_('JERROR_LAYOUT_SEARCH_ENGINE_OUT_OF_DATE_LISTING') . '</li>
		<li>' . JText::_('JERROR_LAYOUT_MIS_TYPED_ADDRESS') . '</li>
		<li>' . JText::_('JERROR_LAYOUT_YOU_HAVE_NO_ACCESS_TO_THIS_PAGE') . '</li>
		<li>' . JText::_('JERROR_LAYOUT_REQUESTED_RESOURCE_WAS_NOT_FOUND') . '</li>
		<li>' . JText::_('JERROR_LAYOUT_ERROR_HAS_OCCURRED_WHILE_PROCESSING_YOUR_REQUEST') . '</li>
	</ol>
	<p>
		<strong>' . JText::_('JERROR_LAYOUT_PLEASE_TRY_ONE_OF_THE_FOLLOWING_PAGES') . '</strong>
		<a class="btn btn-primary" href="' . $this->baseurl . '/"
		   title="' . JText::_('JERROR_LAYOUT_GO_TO_THE_HOME_PAGE') . '">' . JText::_('JERROR_LAYOUT_HOME_PAGE') . '</a>
	</p>

	<p>' . JText::_('JERROR_LAYOUT_PLEASE_CONTACT_THE_SYSTEM_ADMINISTRATOR') . '.</p>

	<div id="techinfo">
		<p>' . htmlspecialchars($this->error->getMessage()) . '</p>

		<p>' .
    $this->debug ? $this->renderBacktrace() : null
        . '</p>
	</div>';
} elseif ($expiredSession) {
    $headerTitle = JText::_('COM_APIPORTAL_PEST_UNAUTH_EXCEPTION');
    $status = 401;
    $mainTitle = JText::_('COM_APIPORTAL_PEST_UNAUTH_EXCEPTION');
    $body = '<p><strong>' . JText::_('COM_APIPORTAL_PEST_UNAUTH_USERMSG') . '</strong>
			<a class="btn btn-primary" href="' . $this->baseurl . '/sign-in"
            title="' . JText::_('COM_APIPORTAL_PEST_UNAUTH_USERLOGIN') . '">' . JText::_('COM_APIPORTAL_PEST_UNAUTH_USERLOGIN') . '</a>
			</p>';
} elseif ($this->error->getCode() == 404) {
    // Log
    $guid = ApiPortalHelper::getGuid();
    $scriptUrl = JFactory::getApplication()->input->server->get('REQUEST_URI', '', 'STRING');
    $message = $guid ?
        JText::sprintf('COM_APIPORTAL_404_USER_LOG_MESSAGE', $guid, $scriptUrl) :
        JText::sprintf('COM_APIPORTAL_404_UNAUTHORIZED_LOG_MESSAGE', $scriptUrl);
    ApiPortalHelper::getContainer()
        ->get(\ApiPortal\Logger\LoggerInterface::class)
        ->log($message, JLog::ERROR);

    $errorImage = '<div style="">
					<img style="max-width: 250px;display: block;margin-left: auto;margin-right: auto" src="' . $this->baseurl . '/templates/purity_iii/images/error-page-404.png" alt="Page Not Found"/>
				</div>';

    $headerTitle = JText::_('COM_APIPORTAL_ERROR_PAGE_TITLE_NOT_FOUND');
    $status = 404;
    $mainTitle = JText::sprintf('COM_APIPORTAL_ERROR_PAGE_HEADER_MESSAGE', $this->error->getCode());
    $body = '<div style="text-align: center;"><p style="text-align: center; width: 290px;margin: auto; color: #838383"><strong>' . JText::_('JERROR_LAYOUT_PLEASE_TRY_ONE_OF_THE_FOLLOWING_PAGES') . '</strong><br /><br />
			<a class="btn btn-primary" style="background-color: #407ca0; padding: 15px 30px 15px 30px; border-radius: 40px;" href="' . $this->baseurl . '/"
            title="' . JText::_('JERROR_LAYOUT_GO_TO_THE_HOME_PAGE') . '">' . JText::_('JERROR_LAYOUT_HOME_PAGE') . '</a>
			</p></div>';
} else {
    $headerTitle = JText::_('COM_APIPORTAL_ERROR_PAGE_TITLE_WENT_WRONG');
    $status = false;
    $mainTitle = JText::_('COM_APIPORTAL_ERROR_PAGE_TITLE_WENT_WRONG');
    $body = '<p><strong>' . JText::_('JERROR_LAYOUT_PLEASE_TRY_ONE_OF_THE_FOLLOWING_PAGES') . '</strong>
			<a class="btn btn-primary" href="' . $this->baseurl . '/"
            title="' . JText::_('JERROR_LAYOUT_GO_TO_THE_HOME_PAGE') . '">' . JText::_('JERROR_LAYOUT_HOME_PAGE') . '</a>
			</p>';
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>"
      lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <title><?= $headerTitle ?></title>
    <link rel="stylesheet" href="<?php echo $this->baseurl; ?>/templates/purity_iii/css/error.css" type="text/css"/>
    <?php if ($this->direction == 'rtl') : ?>
        <link rel="stylesheet" href="<?php echo $this->baseurl; ?>/templates/system/css/error_rtl.css"
              type="text/css"/>
    <?php endif; ?>
    <?php
    if (JDEBUG || $debug) {
        ?>
        <link rel="stylesheet" href="<?php echo $this->baseurl ?>/media/cms/css/debug.css" type="text/css"/>
        <?php
    }
    ?>
</head>
<body>
<div class="error">
    <div id="outline">
        <div id="errorboxoutline">
            <?= $errorImage ?>
            <?php if($this->error->getCode() == 404):?>
                <div style="text-align: center; font-size: 30px; font-weight: bold; text-transform: uppercase">
                    <?= $mainTitle ?>
                </div>
            <?php else:?>
                <div id="errorboxheader">
                    <?= $mainTitle ?>
                </div>
            <?php endif;?>
            <div id="errorboxbody">
                <?= $body ?>
            </div>
        </div>
    </div>
</div>
</body>
</html>