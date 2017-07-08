<?php

// namespace Chadicus\Slim\OAuth2\Routes;
namespace Controller;

use Chadicus\Slim\OAuth2\Http;
use Chadicus\Slim\OAuth2\Routes;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use OAuth2;

use Service\CurlRequest;

/**
 * Slim route for /authorization endpoint.
 */
final class Authorize implements Routes\RouteCallbackInterface
{
    const ROUTE = '/authorize';

    /**
     * The slim framework view helper.
     *
     * @var object
     */
    // private $view;

    /**
     * The oauth2 server imstance.
     *
     * @var OAuth2\Server
     */
    private $server;

    /**
     * The template for /authorize
     *
     * @var string
     */
    // private $template;

    /**
     * Extracts user_id from the incoming request.
     *
     * @var UserIdProviderInterface
     */
    private $userIdProvider;

    /**
     * Construct a new instance of Authorize.
     *
     * @param OAuth2\Server           $server         The oauth2 server imstance.
     * @param object                  $view           The slim framework view helper.
     * @param string                  $template       The template for /authorize.
     * @param UserIdProviderInterface $userIdProvider Object to extract a user_id based on the incoming request.
     *
     * @throws \InvalidArgumentException Thrown if $view is not an object implementing a render method.
     */
    public function __construct(
        OAuth2\Server $server,
        // $view,
        // $template = '/authorize.phtml',
        Routes\UserIdProviderInterface $userIdProvider = null
    ) {
        // if (!is_object($view) || !method_exists($view, 'render')) {
        //     throw new \InvalidArgumentException('$view must implement a render() method');
        // }
        //
        $this->server = $server;
        // $this->view = $view;
        // $this->template = $template;

        if ($userIdProvider == null) {
            $userIdProvider = new Routes\UserIdProvider();
        }

        $this->userIdProvider = $userIdProvider;
    }

    /**
     * Invoke this route callback.
     *
     * @param ServerRequestInterface $request   Represents the current HTTP request.
     * @param ResponseInterface      $response  Represents the current HTTP response.
     * @param array                  $arguments Values for the current route’s named placeholders.
     *
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $arguments = [])
    {
        $oauth2Request = Http\RequestBridge::toOAuth2($request);
        $oauth2Response = new OAuth2\Response();

        if (!$this->server->validateAuthorizeRequest($oauth2Request, $oauth2Response)) {
            return Http\ResponseBridge::fromOAuth2($oauth2Response);
        }

        $authorized = $oauth2Request->request('authorized');
        if (empty($authorized)) {
            $response = Http\ResponseBridge::fromOAuth2($oauth2Response);
            $this->view->render($response, $this->template, ['client_id' => $oauth2Request->query('client_id')]);
            return $response->withHeader('Content-Type', 'text/html');
        }

        $this->server->handleAuthorizeRequest(
            $oauth2Request,
            $oauth2Response,
            $authorized === 'yes',
            $this->userIdProvider->getUserId($request, $arguments)
        );

        $code = substr($oauth2Response->getHttpHeader('Location'), strpos($oauth2Response->getHttpHeader('Location'), 'code=')+5, 40);
        // print_r($code);
        $respuesta['code'] = $code;
        $respuesta['success'] = true;
        return $response->withJson($respuesta);
        // $path = "/api/token";
        // $postFields = array(
        //   "code" => $code,
        // 	"grant_type" => "authorization_code"
        // );
        //
        // $curlRequest = new CurlRequest();
        // $curlRequest->addContextOption(CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        // $curlRequest->addContextOption(CURLOPT_USERPWD, "social:secret");
        // $curlRequest->addContextOption(CURLOPT_POSTFIELDS, json_encode($postFields));
        // $curlRequest->addContextOption(CURLOPT_CUSTOMREQUEST, "POST");
        // $curlRequest->addContextOption(CURLOPT_URL, URL_BASE.$path);
        // $curlResponse = $curlRequest->sendCurlRequest();
        //
        // // print_r($curlResponse);
        // return $response->withJson($curlResponse);
        // // return Http\ResponseBridge::fromOAuth2($oauth2Response);
    }
}
