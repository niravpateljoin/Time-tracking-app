<?php

namespace App\Form\Type;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use App\Entity\Task;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;


class TaskType extends AbstractType
{
	/**
	 * @param FormBuilderInterface $builder
	 * @param array                $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
	        ->add('title')
	        ->add('comment')
	        ->add('createdAt', DateTimeType::class, [
			    'label' => 'Date and Time',
			]);
		$this->addSubmitToFormBuilder($builder);
	}

	protected function addSubmitToFormBuilder(FormBuilderInterface $builder, $name = 'save'){
		$builder->add($name, SubmitType::class, [
			'attr' => ['class' => 'btn-primary'],
		]);
	}
	
	public function configureOptions(OptionsResolver $resolver)
    {
		$resolver->setDefaults([
			'data_class' => Task::class,
			'password' => true
		]);
    }

}