<?php

  function translated_post_date($start_unix){
    $lang = defined('ICL_LANGUAGE_CODE') ? ICL_LANGUAGE_CODE : 'en';
    if ($lang == 'en') {
      return en_date($start_unix);
    }else {
      return fr_date($start_unix);
    }
  }

  function en_date($start_unix){
    $date = date_i18n("F j, Y",$start_unix);
    return $date;
  }

  function fr_date($start_unix){
    $date = date_i18n("j F Y",$start_unix);
    return $date;
  }

  function translated_event_date($start_unix, $end_unix){
    $lang = defined(ICL_LANGUAGE_CODE) ? ICL_LANGUAGE_CODE : 'en';
    if ($lang == 'en') {
      return en_event_date($start_unix, $end_unix);
    }else {
      return fr_event_date($start_unix, $end_unix);
    }
  }

  function en_event_date($start_unix,$end_unix){
    // years aren't the same
    if($end_unix){
      $date = date_i18n("F j, Y",$start_unix)  . ' - '.date_i18n("F j, Y",$end_unix);
    }else{
      $date = date_i18n("F j, Y",$start_unix);
    }
    return $date;
  }

  function fr_event_date($fr_start_unix,$fr_end_unix){
    $timezone = '';
    if(get_field('timezone')){
      $timezone = ' ' . get_field('timezone');
    }
    if($fr_end_unix){
      $date = date_i18n("j F Y",$fr_start_unix) . ' ' . __('à', 'sassquatch') . ' ' . date_i18n("j F Y",$fr_end_unix);
    }else{
      $date = date_i18n("j F Y",$fr_start_unix);
    }
    return $date;
  }
