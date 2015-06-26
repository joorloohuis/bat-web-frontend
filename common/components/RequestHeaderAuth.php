<?php

namespace common\components;

use yii\filters\auth\AuthMethod;

/**
 * QueryHeaderAuth is an action filter that supports the authentication based on the access token passed through a query header.
 *
 * @author Joor Loohuis <joor.loohuis@gmail.com>
 */
class RequestHeaderAuth extends AuthMethod
{

    /**
     * @var string the header name for passing the access token
     */
    public $tokenHeader = 'X-Access-Token';


    /**
     * @inheritdoc
     */
    public function authenticate($user, $request, $response)
    {
        $accessToken = $request->getHeaders()->get($this->tokenHeader);
        if (is_string($accessToken)) {
            $identity = $user->loginByAccessToken($accessToken, get_class($this));
            if ($identity !== null) {
                return $identity;
            }
        }
        if ($accessToken !== null) {
            $this->handleFailure($response);
        }

        return null;
    }
}
