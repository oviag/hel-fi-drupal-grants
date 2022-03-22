<?php

namespace Drupal\form_tool_embed\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Returns responses for Form Tool Embed routes.
 */
class FormToolEmbedController extends ControllerBase {

  /**
   * Builds the response.
   *
   * @throws \Auth0\SDK\Exception\InvalidTokenException
   */
  public function build() {


    /** @var \Drupal\helfi_helsinki_profiili\HelsinkiProfiiliUserData $hpud */
    $hpud = \Drupal::service('helfi_helsinki_profiili.userdata');

//    $d = $hpud->getUserData();
//    $dd = $hpud->getUserProfileData(true);
//    $ddd = $hpud->getTokenData();

    // Access token to get api access tokens in next step.
    $accessToken = $hpud->getAccessToken();

    // Use access token to fetch profiili token from token service.
    $apiAccessToken = $hpud->getHelsinkiProfiiliToken($accessToken);

    $d = 'asdf';

    $config = new \Auth0\SDK\Configuration\SdkConfiguration(
      null,
      null,
      'tunnistamo.test.hel.ninja',
      'tunnistamo.test.hel.ninja',
      'avustusasiointi-ui-dev',
      null,
      '9b68a5b7f6941d72dcfe68ac389db134247bd4dc651c6e16e6a052b2'
    );
    // The Auth0 SDK includes a helpful token processing utility we'll leverage for this:
    $token = new \Auth0\SDK\Token($config, $apiAccessToken["https://api.hel.fi/auth/lomaketyokaluapidev"], \Auth0\SDK\Token::TYPE_ID_TOKEN);

    // Verify the token: (This will throw an \Auth0\SDK\Exception\InvalidTokenException if verification fails.)
    $token->verify();

    // Validate the token claims: (This will throw an \Auth0\SDK\Exception\InvalidTokenException if validation fails.)
    $token->validate();


    $build['content'] = [
      '#theme' => 'testi1',
      '#content' => 'testi1',
    ];

    return $build;
  }


  /**
   * Builds the response.
   */
  public function build2() {

    $build['content'] = [
      '#theme' => 'testi2',
      '#content' => 'testi2',
    ];

    return $build;
  }
  /**
   * Builds the response.
   */
  public function build3() {

    $build['content'] = [
      '#theme' => 'testi3',
      '#content' => 'testi3',
    ];

    return $build;
  }

}
