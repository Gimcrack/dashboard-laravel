<?php

namespace App\Dashboard;

use Twilio;
use Mail;

class Notifier {

    public static function mail( $view, $data = [], $to = null, $subject = null, $from = "itdashboard@msb.matsugov.lan" )
    {
      $to = $to ? explode("\n",$to ) : explode(";",env('ADMIN_EMAIL'));
      $subject = $subject ?: env('SITE_TITLE') . " Alert";

      foreach( $to as $recipient )
      {
        Mail::send($view, $data , function($message) use ($recipient, $subject, $from) {
          $message->to($recipient)->from($from)->subject($subject);
        });
      }

    }

    /**
     * Send a text message
     * @method text
     * @param  [type] $number  [description]
     * @param  [type] $message [description]
     * @return [type]          [description]
     */
    public static function text( $number, $message )
    {
      $numbers = explode("\n",$number);
      foreach($numbers as $num)
      {
        Twilio::message( static::formatPhone($num) , $message);
      }

    }

    /**
     * Is it currently quiet hours
     * @method isQuietHours
     * @return boolean      [description]
     */
    public static function isQuietHours()
    {
      $config = ( date('N') < 6  ) ?
          config('alerts.quiet_hours.weekday') :
          config('alerts.quiet_hours.weekend');
      return ( date('H') < $config['before'] || date('H') > $config['after'] );
    }


    /**
     * Format a phone number for sending a text
     * @method formatPhone
     * @param  [type]      $number [description]
     * @return [type]              [description]
     */
    public static function formatPhone($number)
    {
      switch( true )
      {
        case strlen($number == 7) :
          return "+1907" . $number;

        case strlen($number) == 10 :
          return "+1" . $number;

        case strlen($number) == 11 :
          return "+" . $number;
      }

      return Log::error("{$number} is not a valid phone number");

    }
}
