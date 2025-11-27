<?php
/**
 * @package		SP Flickr Gallery
 * @copyright	Copyright (C) 2010 - 2015 JoomShaper. All rights reserved.
 * @license		GNU General Public License version 2 or later; 
 */

// no direct access
defined('_JEXEC') or die;

//Documnet Ready
$doc = JFactory::getDocument();

//Load JS
JHtml::_('jquery.framework');
$doc->addScript(JURI::base(true) . '/modules/mod_sp_flickrgallery/assets/js/sp-flickr-gallery.js');
//Load CSS
$doc->addStyleSheet(JURI::base(true) . '/modules/mod_sp_flickrgallery/assets/css/sp-flickr-gallery.css');

// Get Image Limit
$image_limit = $params->get('limit', 8);

// Get Column
$image_column = $params->get('columns', 4);

switch ($image_column) {
	case 3:
		$image_column = 33;
		break;

	case 4:
		$image_column = 25;
		break;

	default:
		$image_column = 25;
		break;
}

// Add styles
$style = 'ul.sp-flickr-gallery li {'
        . 'max-width: ' . $image_column . '%;'
        . '}';
$doc->addStyleDeclaration($style);

require JModuleHelper::getLayoutPath('mod_sp_flickrgallery', $params->get('layout'));