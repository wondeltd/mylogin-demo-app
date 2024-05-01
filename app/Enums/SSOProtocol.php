<?php

namespace App\Enums;

enum SSOProtocol: string
{
    case OAuth = 'o_auth';
    case SAML = 'saml';
}
