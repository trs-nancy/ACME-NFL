<?php

/**
 * @file
 * Contains acme_sports_nfl.views.inc.
 */

/**
 * Implements hook_views_data().
 */
function acme_sports_nfl_views_data() {
  $data = [];

  // Base data.
  $data['acme_nfl']['table']['group'] = t('ACME NFL Data');
  $data['acme_nfl']['table']['base'] = [
    'title' => t('ACME NFL API Data'),
    'help' => t('NFL data provided by the ACME API\'s endpoint.'),
    'query_id' => 'acme_nfl',
  ];

  // Field data.
  $data['acme_nfl']['name'] = [
    'title' => t('Name'),
    'help' => t('Team Name.'),
    'field' => [
      'id' => 'standard',
    ],
  ];

  $data['acme_nfl']['nickname'] = [
    'title' => t('Nickname'),
    'help' => t('Team Nickame.'),
    'field' => [
      'id' => 'standard',
    ],
  ];

  $data['acme_nfl']['display_kname'] = [
    'title' => t('Display Name'),
    'help' => t('Team Display Name.'),
    'field' => [
      'id' => 'standard',
    ],
  ];
  $data['acme_nfl']['id'] = [
    'title' => t('ID'),
    'help' => t('Team ID.'),
    'field' => [
      'id' => 'numeric',
    ],
  ];
  $data['acme_nfl']['conference'] = [
    'title' => t('Conference'),
    'help' => t('Team Conference.'),
    'field' => [
      'id' => 'standard',
    ],
  ];
  $data['acme_nfl']['division'] = [
    'title' => t('Division'),
    'help' => t('Team Division.'),
    'field' => [
      'id' => 'standard',
    ],
  ];

  return $data;
}
