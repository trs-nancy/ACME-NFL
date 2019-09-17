<?php

namespace Drupal\acme_sports_nfl\Plugin\views\query;

use Drupal\views\Plugin\views\query\QueryPluginBase;
use Drupal\views\ResultRow;

/**
 * ACME NFL API views query plugin which wraps calls to the ACME NFL API.
 *
 * @ViewsQuery(
 *   id = "acme_nfl",
 *   title = @Translation("ACME NFL API"),
 *   help = @Translation("Query against the ACME NFL API.")
 * )
 */
class AcmeNflApiViewsQuery extends QueryPluginBase {

  /**
   * {@inheritdoc}
   */
  public function ensureTable($table, $relationship = NULL) {
    return '';
  }

  /**
   * {@inheritdoc}
   */
  public function addField($table, $field, $alias = '', $params = []) {
    return $field;
  }

  /**
   * {@inheritdoc}
   */
  public function execute(\Drupal\Views\ViewExecutable $view) {
    $json = \Drupal::service('acme_sports_nfl.data_service')->getAcmeNflData();
    if ($json['results']['data']['team']) {
      $index = 0;
      foreach ($json['results']['data']['team'] as $team) {
        $row['id'] = $team['id'];
        $row['name'] = $team['name'];
        $row['nickname'] = $team['nickname'];
        $row['display_kname'] = $team['display_name'];
        $row['conference'] = $team['conference'];
        $row['division'] = $team['division'];
        $row['index'] = $index++;
        $view->result[] = new ResultRow($row);
      }
    }
  }

}
