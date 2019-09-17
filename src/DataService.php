<?php

namespace Drupal\acme_sports_nfl;

use Drupal\Component\Serialization\Json;

/**
 * Class DataService.
 */
class DataService {

  /**
   * Constructs a new DataService object.
   */
  public function __construct() {
  }

  /**
   * Function getAcmeNflData.
   *
   * Custom function to return API data.
   *
   * @return mixed
   *   Array of results from JSON.
   */
  public function getAcmeNflData() {
    $api_url = \Drupal::config('acme_sports_nfl.api_settings')->get('acme_nfl_api');
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_URL, $api_url);
    $result = curl_exec($ch);
    curl_close($ch);
    return Json::decode($result);
  }

}
