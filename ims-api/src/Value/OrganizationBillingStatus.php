<?php

namespace App\Value;

enum OrganizationBillingStatus: string {
    case INACTIVE = 'inactive';
    case ACTIVE = 'active';
}
