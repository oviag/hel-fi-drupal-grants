<?php

namespace Drupal\form_tool_embed\Controller;

use Drupal\Component\Serialization\Json;
use Drupal\Core\Controller\ControllerBase;

use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;


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

    // Access token to get api access tokens in next step.
    $accessToken = $hpud->getAccessToken();

    // Use access token to fetch profiili token from token service.
    try {
      if ($accessToken) {
        $apiAccessToken = $hpud->getHelsinkiProfiiliToken($accessToken);

      }
    } catch (\Exception $e) {
      $d = 'asdf';
    } catch (GuzzleException $e) {
      $d = 'asdf';
    }

    $response = \Drupal::httpClient()
      ->get('http://hel-fi-form-tool-app:8080/form-tool-share/init-form', [
        'headers' => [
          'Accept' => 'application/json',
          'Content-Type' => 'application/json',
          'X-Auth-Token' => $apiAccessToken["https://api.hel.fi/auth/lomaketyokaluapidev"],
        ],
      ]);

    $bc = $response->getBody()->getContents();
    $cookiesFromResponse = $response->getHeader('Set-Cookie');
    $cookiesFromResponseExploded = explode(';', reset($cookiesFromResponse));
    $bbcc = Json::decode($bc);

    $session = explode('=', $cookiesFromResponseExploded[0]);
    $expires = explode('=', $cookiesFromResponseExploded[1]);
    $maxAge = explode('=', $cookiesFromResponseExploded[2]);

    $expTime = strtotime($expires[1]);

    //    $response = new Response();
    //    $cookie = new Cookie('Test','Derp', 0, '/' , NULL, FALSE);
    //    $response->headers->setCookie($cookie);
    //    $response->send();

    setcookie(
      $session[0],
      $session[1],
      $expTime,
      '/',
      'hel-fi-form-tool.docker.so',
      TRUE,
      TRUE
    );

    //    setcookie(
    //      string $name,
    //    string $value = "",
    //    int $expires_or_options = 0,
    //    string $path = "",
    //    string $domain = "",
    //    bool $secure = false,
    //    bool $httponly = false
    //)

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
