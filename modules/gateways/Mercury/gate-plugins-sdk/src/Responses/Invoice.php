<?php

namespace MercuryCash\SDK\Responses;

use MercuryCash\SDK\Traits\FromArrayTrait;
use MercuryCash\SDK\Traits\ToArrayTrait;
use MercuryCash\SDK\Interfaces\ResponseInterface;

class Invoice implements ResponseInterface
{
    use FromArrayTrait;
    use ToArrayTrait;


}