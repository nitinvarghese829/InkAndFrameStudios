<?php

namespace App\Controller\Admin;

use App\Entity\Blog;
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
            TextEditorField::new('content', 'Content')->onlyOnForms(),
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

            BooleanField::new('isActive'),

            FormField::addPanel('Meta Information')->addCssClass('tab-general')->onlyOnForms(),
            TextField::new('metaTitle')->setLabel('Meta Title')->onlyOnForms(),
            TextareaField::new('metaDescription')->setLabel('Meta Description')->onlyOnForms(),
            TextField::new('metaKeywords')->setLabel('Meta Keywords')->onlyOnForms(),
            TextField::new('author')->setLabel('Author')->onlyOnForms(),
            TextField::new('tags')->setLabel('Tags')->onlyOnForms(),
        ];

        return $fileds;
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $this->handleImageUpload($entityInstance);
        $entityInstance->setSlug($this->generateSlug($entityInstance->getTitle()));
        $entityInstance->setCreatedBy($this->getUser());
        parent::updateEntity($entityManager, $entityInstance);
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $this->handleImageUpload($entityInstance);
        $entityInstance->setSlug($this->generateSlug($entityInstance->getTitle()));
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

    private function generateSlug(string $name): string
    {
        return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name), '-'));
    }
}
