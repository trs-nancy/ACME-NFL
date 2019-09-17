<?php

namespace Drupal\acme_sports_nfl\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Component\Serialization\Json;
use Drupal\Core\Cache\CacheTagsInvalidator;
use Drupal\Core\Config\ConfigFactoryInterface;

/**
 * Class AcmeNflApiConfigForm.
 */
class AcmeNflApiConfigForm extends ConfigFormBase {

  /**
   * Drupal\Core\Cache\CacheTagsInvalidator definition.
   *
   * @var Drupal\Core\Cache\CacheTagsInvalidator
   */
  protected $cacheInvalidator;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory'),
      $container->get('cache_tags.invalidator')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function __construct(ConfigFactoryInterface $config_factory,
                              CacheTagsInvalidator $cacheInvalidator) {
    parent::__construct($config_factory);
    $this->cacheInvalidator = $cacheInvalidator;
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'acme_nfl_api_config_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'acme_sports_nfl.api_settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('acme_sports_nfl.api_settings');

    $form['nfl_api_url'] = [
      '#type' => 'textfield',
      '#title' => t('API Endpoint for ACME NFL Data'),
      '#description' => t('eg: http://delivery.chalk247.com/team_list/NFL.JSON?api_key=74db8efa2a6db279393b433d97c2bc843f8e32b0'),
      '#default_value' => $config->get('acme_nfl_api') ? $config->get('acme_nfl_api') : FALSE,
    ];

    $form['invalidate_cache'] = [
      '#type' => 'checkbox',
      '#title' => 'Invalidate Cache',
      '#description' => "Select to clear cached data for this endpoint.",
    ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    // Verify the URL is a valid JSON API endpoint.
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_URL, $form_state->getValue('nfl_api_url'));
    $result = curl_exec($ch);
    curl_close($ch);
    $json = Json::decode($result);
    if (!isset($json['results']['data']['team'])) {
      $form_state->setErrorByName('nfl_api_url', 'This is not valid a valid URL. Please enter a valid URL.');
    }
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('acme_sports_nfl.api_settings');
    $config->set('acme_nfl_api', $form_state->getValue('nfl_api_url'))->save();

    if ($form_state->getValue('invalidate_cache') == 1) {
      $this->cacheInvalidator->invalidateTags(['AcmeNflApi']);
      \Drupal::messenger()->addMessage('Cache Cleared for endpoint data.');
    }
    parent::submitForm($form, $form_state);
  }

}
