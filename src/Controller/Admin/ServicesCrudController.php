<?php

namespace App\Controller\Admin;

use App\Entity\Services;
use App\Service\SitemapGenerator;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ServicesCrudController extends AbstractCrudController
{
    private $sitemapGenerator;

    public function __construct(SitemapGenerator $sitemapGenerator)
    {
        $this->sitemapGenerator = $sitemapGenerator;
    }

    public static function getEntityFqcn(): string
    {
        return Services::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            TextField::new('title'),
            TextField::new('slug')->onlyOnForms(),
            TextField::new('icon')
                ->setLabel('Icon')
                ->setHelp('Enter the icon class using https://fontawesome.com/search (e.g., "fa fa-icon-name")'),
            TextareaField::new('description')
                ->setFormTypeOption('attr', [
                    'class' => 'tinymce',
                    'data-controller' => 'tinymce',
                    'data-action' => 'turbo:load->tinymce#createEditor',
                ])->setLabel('Description')
                ->setHelp('Enter a small description of the service to be displayed in listing page')->onlyOnForms(),
            BooleanField::new('isActive'),
            TextareaField::new('detailedDescription')
                ->setFormTypeOption('attr', [
                    'class' => 'tinymce',
                    'data-controller' => 'tinymce',
                    'data-action' => 'turbo:load->tinymce#createEditor',
                ])
                ->setLabel('Detailed Description')
                ->setHelp('Enter a detailed description of the service, supports HTML formatting.')->onlyOnForms(),

            FormField::addPanel('SEO Information')->setIcon('fa fa-search'),
            TextField::new('metaTitle')
                ->setLabel('Meta Title')
                ->setHelp('Used for the browser title and SEO.')->onlyOnForms(),
            TextareaField::new('metaDescription')
                ->setLabel('Meta Description')
                ->setHelp('Short description used by search engines.')->onlyOnForms(),
            TextField::new('metaKeywords')
                ->setLabel('Meta Keywords')
                ->setHelp('Comma-separated keywords (optional).')->onlyOnForms(),
            TextField::new('metaAuthor')
                ->setLabel('Meta Author')
                ->setHelp('Name of the author (used in meta tags).')->onlyOnForms(),
            TextField::new('metaTags')
                ->setLabel('Meta Tags'),

        ];
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $this->sitemapGenerator->generate();
        if ($entityInstance->getCreatedBy() === null) {
            $entityInstance->setCreatedBy($this->getUser());
            $entityInstance->setCreatedAt(date_create('now'));
        }
        $entityInstance->setUpdatedBy($this->getUser());
        $entityInstance->setUpdatedAt(date_create('now'));
        parent::updateEntity($entityManager, $entityInstance);
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $this->sitemapGenerator->generate();
        if ($entityInstance->getCreatedBy() === null) {
            $entityInstance->setCreatedBy($this->getUser());
            $entityInstance->setCreatedAt(date_create('now'));
        }
        $entityInstance->setUpdatedBy($this->getUser());
        $entityInstance->setUpdatedAt(date_create('now'));
        parent::persistEntity($entityManager, $entityInstance);
    }
}
