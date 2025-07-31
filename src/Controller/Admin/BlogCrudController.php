<?php

namespace App\Controller\Admin;

use App\Entity\Blog;
use App\Form\FaqType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use Doctrine\ORM\EntityManagerInterface;
use Dom\Text;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class BlogCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Blog::class;
    }


    public function configureFields(string $pageName): iterable
    {
        $fileds =  [
            IdField::new('id')->onlyOnIndex(),
            TextField::new('title'),
            TextField::new('slug')->onlyOnForms(),
            TextareaField::new('content', 'Content')
                ->setFormTypeOption('attr', [
                    'class' => 'tinymce',
                    'data-controller' => 'tinymce',
                    'data-action' => 'turbo:load->tinymce#createEditor',
                ])->onlyOnForms(),
            Field::new('blogImage')
                ->setFormType(FileType::class)
                ->setLabel('Blog Image')
                ->onlyOnForms(),

            TextField::new('blogImage')
                ->setLabel('Preview')
                ->formatValue(function ($value, $entity) {
                    if (!$value) {
                        return '';
                    }

                    if (is_resource($value)) {
                        $value = stream_get_contents($value);
                    }

                    if (is_string($value) && strlen($value) > 0) {
                        $base64 = base64_encode($value);
                        return sprintf('<img src="data:image/jpeg;base64,%s" style="max-height:100px"/>', $base64);
                    }

                    return '';
                })
                ->onlyOnIndex()
                ->renderAsHtml(),
            TextField::new('blogAuthor'),
            BooleanField::new('isActive'),
            FormField::addPanel('Meta Information')->addCssClass('tab-general')->onlyOnForms(),
            TextField::new('metaTitle')->setLabel('Meta Title')->onlyOnForms(),
            TextareaField::new('metaDescription')->setLabel('Meta Description')->onlyOnForms(),
            TextField::new('metaKeywords')->setLabel('Meta Keywords')->onlyOnForms(),
            TextField::new('author')->setLabel('Meta Author')->onlyOnForms(),
            TextField::new('tags')->setLabel('Tags')->onlyOnForms(),
            CollectionField::new('faqs')
                ->setEntryType(FaqType::class)
                ->allowAdd()
                ->allowDelete()
                ->setFormTypeOptions([
                    'by_reference' => false,
                ])
                ->onlyOnForms(),
            TextareaField::new('faqSchema', 'FAQ Schema (JSON-LD)')
                ->setFormTypeOption('attr', ['rows' => 10, 'class' => 'monospace'])->onlyOnForms()
                ->formatValue(function ($value, $entity) {
                    return json_encode($value, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                }),
        ];

        return $fileds;
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $this->handleImageUpload($entityInstance);
        $entityInstance->setCreatedBy($this->getUser());
        parent::updateEntity($entityManager, $entityInstance);
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $this->handleImageUpload($entityInstance);
        $entityInstance->setCreatedBy($this->getUser());
        $entityInstance->setCreatedAt(date_create('now'));
        parent::persistEntity($entityManager, $entityInstance);
    }

    private function handleImageUpload(Blog $entity): void
    {
        $request = $this->getContext()->getRequest();
        $uploadedFile = $request->files->get('Blog')['blogImage'] ?? null;

        if ($uploadedFile instanceof UploadedFile) {
            $binaryData = file_get_contents($uploadedFile->getPathname());
            $entity->setBlogImage($binaryData);
        }
    }
}
