<?php
namespace radius\V1\Rest\Account;
use Lib\Base\Exception as Exception;

// test use
use Zend\Stdlib\Hydrator;

class AccountResourceFactory
{
    public function __invoke($services)
    {
        //return new AccountResource();
        return new AccountResource($services->get('Lib\Radius\Respondent\Account'));

    }
}
