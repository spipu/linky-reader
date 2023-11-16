<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Spipu\UserBundle\Entity\AbstractUser;

#[ORM\Entity(repositoryClass: 'Spipu\UserBundle\Repository\UserRepository')]
#[ORM\Table(name: "spipu_user")]
class User extends AbstractUser
{
}
