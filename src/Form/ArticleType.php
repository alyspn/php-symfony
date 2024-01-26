<?php
// src/Form/ArticleType.php
namespace App\Form;

use App\Entity\Article;
use App\Entity\Tag;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType as SymfonyTextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Title'
            ])
            ->add('body', TextareaType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Content'
            ])
            ->add('publicationDate', DateTimeType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Publication Date',
                'widget' => 'single_text', // to use HTML5 date input
            ])
            
            
                    ->add('tagList', TextType::class, [
                        'mapped' => false,
                        'required' => false,
                        'attr' => ['class' => 'form-control', 'placeholder' => 'Enter tags, separated by commas'],
                        'label' => 'Tags (comma separated)'
                    ])
                ;
            }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
