<?php

namespace TodoListBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use TodoListBundle\Entity\Task;

class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', null, [
                'label' => 'Название задачи',
                'attr' => [
                    'maxlength' => 100
                ]
            ])
            ->add('description', null, [
                'label' => 'Описание задачи',
                'attr' => [
                    'maxlength' => 255
                ]
            ])
            ->add('taskList', EntityType::class, [
                'label' => 'Список',
                'class' => 'TodoListBundle\Entity\TaskList',
            ])
            ->add('save', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
        ]);
    }
}
