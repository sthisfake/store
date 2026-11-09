<?php

namespace App\Enums;

enum ProductStatus: string
{
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
    case PreSale = 'preSale';
    case ARCHIVED = 'archived';
    case SOON = 'soon';
}