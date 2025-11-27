<?php

/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2018 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
 */
//no direct accees
defined('_JEXEC') or die('resticted aceess');

class SppagebuilderAddonTestimonialpro extends SppagebuilderAddons {

    public function render() {

        $class = (isset($this->addon->settings->class) && $this->addon->settings->class) ? $this->addon->settings->class : '';
        $style = (isset($this->addon->settings->style) && $this->addon->settings->style) ? $this->addon->settings->style : '';

        //Options
        $autoplay = (isset($this->addon->settings->autoplay) && $this->addon->settings->autoplay) ? ' data-sppb-ride="sppb-carousel"' : '';
        $arrows = (isset($this->addon->settings->arrows) && $this->addon->settings->arrows) ? $this->addon->settings->arrows : '';
        $controllers = (isset($this->addon->settings->controllers) && $this->addon->settings->controllers) ? $this->addon->settings->controllers : true;

        //Output
        $output = '';
        $output .= '<div id="sppb-testimonial-pro-' . $this->addon->id . '" class="sppb-carousel sppb-testimonial-pro sppb-slide sppb-text-center' . $class . '"' . $autoplay . '>';

        $output .= '<div class="sppb-carousel-icon">';
        $output .= '<i class="fa fa-quote-right ">';
        $output .= '</i>';
        $output .= '</div>';
        $output .= '<div class="sppb-carousel-inner">';


        if ($controllers) {
            $output .= '<ol class="sppb-carousel-indicators">';
            foreach ($this->addon->settings->sp_testimonialpro_item as $key => $client_img) {
                $active_item = ($key == 0) ? 'active' : '';
                $output .= '<li data-sppb-target=".sppb-testimonial-pro" class="sppb-tm-indicators ' . $active_item . '" data-sppb-slide-to="' . $key . '">';
                $output .= '<img class="sppb-img-responsive sppb-avatar" src="' . $client_img->avatar . '" alt="">';
                $output .= '</li>';
            }
            $output .= '</ol>';
        }

        foreach ($this->addon->settings->sp_testimonialpro_item as $key => $slide_item) {

            $output .= '<div class="sppb-item ' . (($key == 0) ? ' active' : '') . '">';
            $output .= '<div class="sppb-testimonial-message">' . $slide_item->message . '</div>';

            if ($slide_item->url)
                $title .= ' - <span class="pro-client-url">' . $slide_item->url . '</span>';
            if ($slide_item->title)
                $output .= '<div class="sppb-testimonial-client">' . $slide_item->title . '</div>';

            $output .= '</div>'; //end .sppb-item
        }
        $output .= '</div>';

        if ($arrows) {
            $output .= '<div class="sppb-carousel-control-wrapper">';
            $output .= '<a href="#sppb-testimonial-pro-' . $this->addon->id . '" class="left sppb-carousel-control" data-slide="prev"><i class="fa fa-angle-left"></i></a>';
            $output .= '<a href="#sppb-testimonial-pro-' . $this->addon->id . '" class="right sppb-carousel-control" data-slide="next"><i class="fa fa-angle-right"></i></a>';
            $output .= '</div>';
        }

        $output .= '</div>';

        return $output;
    }

    public static function getTemplate() {
        $output = '
            <#
                var contentClass = (!_.isEmpty(data.class) && data.class) ? data.class : "";
                var style = (!_.isEmpty(data.style) && data.style) ? data.style : "";

                var autoplay = (typeof data.autoplay !=="undefined") ? data.autoplay : 0;
                var arrows = (typeof data.arrows !=="undefined") ? data.arrows : 0;
                var controllers = (!_.isEmpty(data.controllers) && data.controllers) ? data.controllers : 0;

                var slideAutoplay = (autoplay>0)?\' data-sppb-ride="sppb-carousel"\': \' data-sppb-ride=""\';
            #>
                <div id="sppb-testimonial-pro-{{data.id}}" class="sppb-carousel sppb-testimonial-pro sppb-slide sppb-text-center {{contentClass}}" {{{slideAutoplay}}}>

                <div class="sppb-carousel-icon">
                <i class="fa fa-quote-right ">
                </i>
                </div>
                <div class="sppb-carousel-inner">

                <# if (controllers>0) { #>
                    <ol class="sppb-carousel-indicators">
                    <# _.each (data.sp_testimonialpro_item, function(client_img, client_key) {
                    var activeClass = "";
                    if(client_key==0){
                        activeClass =" active";
                    } else {
                        activeClass = "";
                    }
                #>
                        <li data-sppb-target=".sppb-testimonial-pro" class="sppb-tm-indicators {{activeClass}}" data-sppb-slide-to="{{client_key}}">
                        <img class="sppb-img-responsive sppb-avatar" src="{{client_img.avatar}}" alt="">
                        </li>
                    <# }) #>
                    </ol>
                <# } #>

                <# _.each (data.sp_testimonialpro_item, function (slide_item, slide_key) {
                    var activeClass = "";
                    if(slide_key==0){
                        activeClass =" active";
                    } else {
                        activeClass = "";
                    }
                #>
                    <div class="sppb-item {{activeClass}}">
                    <div class="sppb-testimonial-message">{{{slide_item.message}}}</div>

                    <# if (slide_item.url){
                        var title = \' - <span class="pro-client-url">\' + slide_item.url + \'</span>\';
                    }
                    if (slide_item.title){
                    #>
                        <div class="sppb-testimonial-client">{{{slide_item.title}}}</div>
                    <# } #>
                    </div>
                <# }) #>
                </div>

                <# if (arrows>0) { #>
                    <div class="sppb-carousel-control-wrapper">
                    <a href="#sppb-testimonial-pro-{{data.id}}" class="left sppb-carousel-control" data-slide="prev"><i class="fa fa-angle-left"></i></a>
                    <a href="#sppb-testimonial-pro-{{data.id}}" class="right sppb-carousel-control" data-slide="next"><i class="fa fa-angle-right"></i></a>
                    </div>
                <# } #>
                </div>
                ';
        return $output;
    }

}
