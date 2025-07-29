<?php

namespace App\Controller\Admin;

use App\Entity\Services;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use phpDocumentor\Reflection\Types\Boolean;

class ServicesCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Services::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            TextField::new('title'),
            TextField::new('icon')
                ->setLabel('Icon')
                ->setHelp('Enter the icon class using https://fontawesome.com/search (e.g., "fa fa-icon-name")'),
            TextEditorField::new('description')->setLabel('Description')->setHelp('Enter a small description of the service to be displayed in listing page')->onlyOnForms(),
            BooleanField::new('isActive'),
            TextEditorField::new('detailedDescription')
                ->setLabel('Detailed Description')
                ->setHelp('Enter a detailed description of the service, supports HTML formatting.')->onlyOnForms(),
        ];
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $entityInstance->setSlug($this->generateSlug($entityInstance->getTitle()));
        parent::updateEntity($entityManager, $entityInstance);
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $entityInstance->setSlug($this->generateSlug($entityInstance->getTitle()));
        parent::persistEntity($entityManager, $entityInstance);
    }

    private function generateSlug(string $name): string
    {
        return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name), '-'));
    }
}
