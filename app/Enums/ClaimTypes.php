<?php

namespace App\Enums;

enum ClaimTypes: string
{
    case EmailAddress = 'http://schemas.xmlsoap.org/ws/2005/05/identity/claims/emailaddress';
    case MyLoginID = 'http://schemas.xmlsoap.org/identity/claims/myloginid';
    case FirstName = 'http://schemas.xmlsoap.org/identity/claims/firstname';
    case LastName = 'http://schemas.xmlsoap.org/identity/claims/lastname';
    case DateOfBirth = 'http://schemas.xmlsoap.org/identity/claims/dateofbirth';
    case Type = 'http://schemas.xmlsoap.org/identity/claims/type';
    case LastOnline = 'http://schemas.xmlsoap.org/identity/claims/lastonline';
    case ServiceProvidersGoogleID = 'http://schemas.xmlsoap.org/identity/claims/serviceproviders.google.id';
    case ServiceProvidersMicrosoftID = 'http://schemas.xmlsoap.org/identity/claims/serviceproviders.microsoft.id';
    case ServiceProvidersWondeID = 'http://schemas.xmlsoap.org/identity/claims/serviceproviders.wonde.id';
    case OrganisationMyLoginID = 'http://schemas.xmlsoap.org/identity/claims/organisation.myloginid';
    case OrganisationWondeID = 'http://schemas.xmlsoap.org/identity/claims/organisation.wondeid';
    case OrganisationName = 'http://schemas.xmlsoap.org/identity/claims/organisation.name';
    case OrganisationPhaseOfEducation = 'http://schemas.xmlsoap.org/identity/claims/organisation.phaseofeducation';
    case OrganisationUrn = 'http://schemas.xmlsoap.org/identity/claims/organisation.urn';
    case OrganisationLACode = 'http://schemas.xmlsoap.org/identity/claims/organisation.lacode';
    case OrganisationEstablishmentNumber = 'http://schemas.xmlsoap.org/identity/claims/organisation.establishmentnumber';
}
