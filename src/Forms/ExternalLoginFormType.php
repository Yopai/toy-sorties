<?php
namespace App\Forms;

use App\Entity\ExternalSource;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
class ExternalLoginFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('site', EntityType::class, [
            'class' => ExternalSource::class,
        ]);
        $builder->add('login', TextType::class, ['required' => false]);
        $builder->add('password', PasswordType::class, ['required' => false]);
    }
}
