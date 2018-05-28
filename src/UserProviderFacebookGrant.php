<?php

namespace yedincisenol\UserProviderFacebook;

use yedincisenol\UserProvider\UserProviderGrantAbstract;

class UserProviderFacebookGrant extends UserProviderGrantAbstract
{

    /**
     * {@inheritdoc}
     */
    public function getIdentifier()
    {
        return 'facebook';
    }

}