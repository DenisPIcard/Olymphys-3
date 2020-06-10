<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response ;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
//use Trikoder\Oauth2\Bundle\Security;
use Trikoder\Oauth2\Bundle;
use League\OAuth2\Server;

class OauthController extends AbstractController
    {

public function __construct()
{
    // Initiate the request handler which deals with $_GET, $_POST, etc
    //$request = new \OAuth2\Util\Request();
      $request = new Request();
    // Create the auth server, the three parameters passed are references
    //  to the storage models
    $this->authserver = new  League\OAuth2\Server(
        new ClientModel,
        new SessionModel,
        new ScopeModel
    );

    // Enable the authorization code grant type
    $this->authserver->addGrantType(new \OAuth2\Grant\AuthCode());

    // Set the TTL of an access token in seconds (default to 3600s / 1 hour)
    $this->authserver->setExpiresIn(86400);
}


 /**
     * @Route("/oauth/index", name="oauth_index")
     */

public function index()
{
    try {

        // Tell the auth server to check the required parameters are in the
        //  query string
        $params = $this->authserver->checkAuthoriseParams();

        // Save the verified parameters to the user's session
        Session::put('client_id', $params['client_id']);
        Session::put('client_details', $params['client_details']);
        Session::put('redirect_uri', $params['redirect_uri']);
        Session::put('response_type', $params['response_type']);
        Session::put('scopes', $params['scopes']);

        // Redirect the user to the sign-in route
        return Redirect::to('oauth/signin');

    } catch (Oauth2\Exception\ClientException $e) {

        // Throw an error here which says what the problem is with the
        //  auth params

    } catch (Exception $e) {

        // Throw an error here which has caught a non-library specific error

    }
}

    /**
     * @Route("/oauth/signin", name="oauth_signin")
     */
public function signin()
{
    // Retrieve the auth params from the user's session
    $params['client_id'] = Session::get('client_id');
    $params['client_details'] = Session::get('client_details');
    $params['redirect_uri'] = Session::get('redirect_uri');
    $params['response_type'] = Session::get('response_type');
    $params['scopes'] = Session::get('scopes');

    // Check that the auth params are all present
    foreach ($params as $key=>$value) {
        if ($value === null) {
            // Throw an error because an auth param is missing - don't
            //  continue any further
        }
    }

    // Process the sign-in form submission
    if (Input::get('signin') !== null) {
        try {

            // Get username
            $u = Input::get('username');
            if ($u === null || trim($u) === '') {
                throw new Exception('please enter your username.');
            }

            // Get password
            $p = Input::get('password');
            if ($p === null || trim($p) === '') {
                throw new Exception('please enter your password.');
            }

            // Verify the user's username and password
            // Set the user's ID to a session

        } catch (Exception $e) {
            $params['error_message'] = $e->getMessage();
        }
    }

    // Get the user's ID from their session
    $params['user_id'] = Session::get('user_id');

    // User is signed in
    if ($params['user_id'] !== null) {

        // Redirect the user to /oauth/authorise route
        return Redirect::to('oauth/authorise');

    }

    // User is not signed in, show the sign-in form
    else {
        return View::make('oauth.signin', $params);
    }
}
/**
     * @Route("/oauth/authorise", name="oauth_authorise")
     */

public function authorise()
{
    // Retrieve the auth params from the user's session
    $params['client_id'] = Session::get('client_id');
    $params['client_details'] = Session::get('client_details');
    $params['redirect_uri'] = Session::get('redirect_uri');
    $params['response_type'] = Session::get('response_type');
    $params['scopes'] = Session::get('scopes');

    // Check that the auth params are all present
    foreach ($params as $key=>$value) {
        if ($value === null) {
            // Throw an error because an auth param is missing - don't
            //  continue any further
        }
    }

    // Get the user ID
    $params['user_id'] = Session::get('user_id');

    // User is not signed in so redirect them to the sign-in route (/oauth/signin)
    if ($params['user_id'] === null) {
        return Redirect::to('signin');
    }

    // Check if the client should be automatically approved
    $autoApprove = ($params['client_details']['auto_approve'] === '1') ? true : false;

    // Process the authorise request if the user's has clicked 'approve' or the client
    if (Input::get('approve') !== null || $autoApprove === true) {

        // Generate an authorization code
        $code = $this->authserver->newAuthoriseRequest('user', $params['user_id'], $params);

        // Redirect the user back to the client with an authorization code
        return Redirect::to(
            \OAuth2\Util\RedirectUri::make($params['redirect_uri'],
            array(
                'code'  =>  $code,
                'state' =>  isset($params['state']) ? $params['state'] : ''
            )
        ));
    }

    // If the user has denied the client so redirect them back without an authorization code
    if (Input::get('deny') !== null) {
        return Redirect::to(
            \OAuth2\Util\RedirectUri::make($params['redirect_uri'],
            array(
                'error' =>  $this->authserver->exceptionCodes[2],
                'error_message' =>  $this->authserver->errors[$this->authserver->exceptionCodes[2]],
                'state' =>  isset($params['state']) ? $params['state'] : ''
            )
        ));
    }

    // The client shouldn't automatically be approved and the user hasn't yet
    //  approved it so show them a form
    return View::make('oauth.authorise', $params);
}




    }