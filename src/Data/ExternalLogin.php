<?php

namespace App\Data;

use App\Entity\ExternalSource;
use Symfony\Component\Validator\Constraints as Assert;
class ExternalLogin
{
    /** @var ExternalSource */
    /** @Assert\NotBlank() */
    public $site;
    /** @var string */
    /** @Assert\NotBlank() */
    public $login;
    /** @var string */
    /** @Assert\NotBlank() */
    public $password;
    /** @var string */
    public $sessionId;
}
