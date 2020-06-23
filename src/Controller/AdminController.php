<?php

namespace App\Controller;

use App\Entity\Member;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\EasyAdminController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class AdminController.
 *
 * @author Dmitriy Atamaniuc <d.atamaniuc@gmail.com>
 */
final class AdminController extends EasyAdminController
{
    /** @var UserPasswordEncoderInterface */
    private $encoder;

    private function setUserPlainPassword(User $user): void
    {
        if ($user->getPlainPassword()) {
            $user->setPassword($this->encoder->encodePassword($user, $user->getPlainPassword()));
        }
    }

    /**
     * @required
     */
    public function setEncoder(UserPasswordEncoderInterface $encoder): void
    {
        $this->encoder = $encoder;
    }

    public function persistMemberEntity(Member $user): void
    {
        $this->setUserPlainPassword($user);

        $this->persistEntity($user);
    }

    public function updateMemberEntity(Member $user): void
    {
        $this->setUserPlainPassword($user);

        $this->updateEntity($user);
    }


}
