<?php

namespace App\Data;

use Symfony\Component\Validator\Constraints as Assert;

class ContactData
{
    #[Assert\NotBlank()]
    #[Assert\Type('string')]
    #[Assert\Length(min: 1, max: 255)]
    #[Assert\Email()]
    public ?string $senderEmail;

    #[Assert\NotBlank()]
    #[Assert\Type('string')]
    #[Assert\Length(min: 1, max: 255)]
    public ?string $message;
}
