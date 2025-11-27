<?php
/**
 * @package Helix3 Framework
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2015 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or Later
*/
//no direct accees
defined ('_JEXEC') or die('resticted aceess');

class Helix3FeatureSocial {

	private $helix3;
	public $position;

	public function __construct( $helix3 ){
		$this->helix3 = $helix3;
		$this->position = $this->helix3->getParam('social_position');
	}

	public function renderFeature() {

		$facebook 	= $this->helix3->getParam('facebook');
		$twitter  	= $this->helix3->getParam('twitter');
		$googleplus = $this->helix3->getParam('googleplus');
		$pinterest 	= $this->helix3->getParam('pinterest');
		$youtube 	= $this->helix3->getParam('youtube');
		$linkedin 	= $this->helix3->getParam('linkedin');
		$dribbble 	= $this->helix3->getParam('dribbble');
		$behance 	= $this->helix3->getParam('behance');
		$skype 		= $this->helix3->getParam('skype');
		$flickr 	= $this->helix3->getParam('flickr');
		$vk 		= $this->helix3->getParam('vk');

		if( $this->helix3->getParam('show_social_icons') && ( $facebook || $twitter || $googleplus || $pinterest || $youtube || $linkedin || $dribbble || $behance || $skype || $flickr || $vk ) ) {
			$html  = '<ul class="social-icons">';

			if( $facebook ) {
				$html .= '<li><a target="_blank" href="'. $facebook .'"><i class="fa fa-facebook"></i>'.JText::_('SOCIAL_ICON_FACEBOOK').'</a></li>';
			}
			if( $twitter ) {
				$html .= '<li><a target="_blank" href="'. $twitter .'"><i class="fa fa-twitter"></i>'.JText::_('SOCIAL_ICON_TWITTER').'</a></li>';
			}
			if( $googleplus ) {
				$html .= '<li><a target="_blank" href="'. $googleplus .'"><i class="fa fa-google-plus"></i>'.JText::_('SOCIAL_ICON_GPLUS').'</a></li>';
			}
			if( $pinterest ) {
				$html .= '<li><a target="_blank" href="'. $pinterest .'"><i class="fa fa-pinterest"></i>'.JText::_('SOCIAL_ICON_PINTEREST').'</a></li>';
			}
			if( $youtube ) {
				$html .= '<li><a target="_blank" href="'. $youtube .'"><i class="fa fa-youtube"></i>'.JText::_('SOCIAL_ICON_YOUTUBE').'</a></li>';
			}
			if( $linkedin ) {
				$html .= '<li><a target="_blank" href="'. $linkedin .'"><i class="fa fa-linkedin"></i>'.JText::_('SOCIAL_ICON_LINKEDIN').'</a></li>';
			}
			if( $dribbble ) {
				$html .= '<li><a target="_blank" href="'. $dribbble .'"><i class="fa fa-dribbble"></i>'.JText::_('SOCIAL_ICON_DRIBBBLE').'</a></li>';
			}
			if( $behance ) {
				$html .= '<li><a target="_blank" href="'. $behance .'"><i class="fa fa-behance"></i>'.JText::_('SOCIAL_ICON_BEHANCE').'</a></li>';
			}
			if( $flickr ) {
				$html .= '<li><a target="_blank" href="'. $flickr .'"><i class="fa fa-flickr"></i>'.JText::_('SOCIAL_ICON_FLICKR').'</a></li>';
			}
			if( $vk ) {
				$html .= '<li><a target="_blank" href="'. $vk .'"><i class="fa fa-vk"></i>'.JText::_('SOCIAL_ICON_VK').'</a></li>';
			}
			if( $skype ) {
				$html .= '<li><a href="skype:'. $skype .'?chat"><i class="fa fa-skype"></i>'.JText::_('SOCIAL_ICON_SKYPE').'</a></li>';
			}

			$html .= '</ul>';

			return $html;
		}

	}
}