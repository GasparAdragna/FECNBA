<?php

namespace App\Services;

use App\Models\ExpoToken;
use App\Models\Notification;
use ExpoSDK\Expo;
use ExpoSDK\ExpoMessage;

class NotificationService
{
  public function send($title, $body)
    {
      $tokens = ExpoToken::all();
      $arrayTokens = [];

      foreach ($tokens as $token) {
          array_push($arrayTokens, $token->token);
      }

      $message = (new ExpoMessage([
          'title' => $title,
          'body' => $body,
      ]))->playSound();

      Expo::addDevicesNotRegisteredHandler(function ($tokens) {
          foreach ($tokens as $token) {
              ExpoToken::where('token', $token)->delete();
          }
      });

      $chunks = array_chunk($arrayTokens, 100);
      $errors = [];

      foreach ($chunks as $chunk) {
          try {
              (new Expo)->send($message)->to($chunk)->push();
          } catch (\Throwable $th) {
              $errors += $chunk;
          }
      }

      $retryErrors = [];
      if (count($errors)) {
          foreach ($errors as $chunk) {
              try {
                  (new Expo)->send($message)->to($chunk)->push();
              } catch (\Throwable $th) {
                  $retryErrors += $chunk;
              }
          }
      }
      
      $notification = Notification::create([
          'title' => $title,
          'body' => $body,
      ]);

      return [
        'status' => 'Se envió correctamente la notificación',
        'errors' => $retryErrors,
      ];

    }
}

?>