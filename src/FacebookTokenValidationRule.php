<?php

namespace yedincisenol\UserProviderFacebook;

use Illuminate\Contracts\Validation\Rule;

class FacebookTokenValidationRule implements Rule
{

    private $scopes = null;
    private $appAccessToken = null;

    private static $endpoint = 'https://graph.facebook.com/me?access_token=:access_token';
    private static $inspect = 'https://graph.facebook.com/debug_token?input_token=:access_token&access_token=:app_access_token';


    public function __construct($scopes = null, $appAccessToken = null)
    {
        $this->scopes           =   $scopes;
        $this->appAccessToken   =   $appAccessToken;
    }

    public function passes($attribute, $value)
    {
        if ($this->scopes) {
            return $this->validateWithScopes($value);
        }

        return $this->validate($value);
    }

    private function validateWithScopes($accessToken)
    {
        $endpoint = str_replace([':access_token', ':app_access_token'],
            [$accessToken, $this->appAccessToken],
            self::$inspect
        );

        try
        {
            $response = json_decode(file_get_contents($endpoint));
            if (!array_diff($this->scopes, $response->data->scopes) && $response->data->is_valid == 1) {
                return true;
            }

        } catch (\Exception $e) {
            return false;
        }
    }

    private function validate($accessToken)
    {
        $endpoint = str_replace(':access_token', $accessToken, self::$endpoint);
        try {
            file_get_contents($endpoint);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function message()
    {
        return ':attribute is invalid or scopes is not match!';
    }
}