<?php
defined('_JEXEC') or die;

$app = JFactory::getApplication();
$app->redirect(JRoute::_(JURI::root()));
$app->close();
