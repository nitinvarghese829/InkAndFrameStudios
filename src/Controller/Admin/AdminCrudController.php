<?php

namespace App\Controller\Admin;

use App\Entity\Admin;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AdminCrudController extends AbstractCrudController
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public static function getEntityFqcn(): string
    {
        return Admin::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),

            TextField::new('email'),

            TextField::new('password')
                ->setFormType(PasswordType::class)
                ->setFormTypeOptions([
                    'mapped' => false,
                    'required' => $pageName === Crud::PAGE_NEW,
                ])
                ->onlyOnForms(),

            ChoiceField::new('roles')
                ->setChoices([
                    'Admin' => 'ROLE_ADMIN',
                    'Editor' => 'ROLE_EDITOR',
                ])
                ->setFormTypeOptions([
                    'multiple' => false,
                    'expanded' => false,
                    'mapped' => false, // we will handle it manually
                ])
                ->onlyOnForms(),
        ];
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $this->handlePasswordAndRole($entityInstance);
        parent::persistEntity($entityManager, $entityInstance);
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $this->handlePasswordAndRole($entityInstance);
        parent::updateEntity($entityManager, $entityInstance);
    }


    private function handlePasswordAndRole(Admin $admin): void
    {
        $request = $this->getContext()->getRequest();
        $data = $request->request->all()['Admin'] ?? [];

        // Handle password
        if (!empty($data['password'])) {
            $hashedPassword = $this->passwordHasher->hashPassword($admin, $data['password']);
            $admin->setPassword($hashedPassword);
        }

        // Handle single role
        if (!empty($data['roles']) && is_string($data['roles'])) {
            $admin->setRoles([$data['roles']]);
        }
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        $qb = parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters);

        // Exclude users who have ROLE_SUPER_ADMIN in their roles
        $alias = $qb->getRootAliases()[0];
        $qb->andWhere($qb->expr()->notLike("$alias.roles", ':superRole'))
            ->setParameter('superRole', '%"ROLE_SUPER_ADMIN"%');

        return $qb;
    }
}
