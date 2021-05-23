<?php

namespace App\Controller\Admin;

use App\Entity\Professeurs;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ProfesseursCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Professeurs::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Professeurs')
            ->setEntityLabelInPlural('Professeurs')
            ->setSearchFields(['id', 'equipesstring'])
            ->overrideTemplate('crud/index', 'Admin/customizations/list_profs.html.twig');
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('equipesstring');
    }

    public function configureFields(string $pageName): iterable
    {
        $equipesstring = TextField::new('equipesstring');
        $user = AssociationField::new('user');
        $equipes = AssociationField::new('equipes');
        $id = IntegerField::new('id', 'ID');
        $userNom = TextareaField::new('user.nom');
        $userPrenom = TextareaField::new('user.prenom');
        $userAdresse = TextareaField::new('user.adresse', 'adresse');
        $userCode = TextareaField::new('user.code', 'CP');
        $userVille = TextareaField::new('user.ville');
        $userEmail = TextareaField::new('user.email', 'courriel');
        $userPhone = TextareaField::new('user.phone');
        $rne = TextareaField::new('rne', 'code UAI');
        $nomlycee = TextareaField::new('nomlycee', 'lycée');
        $communelycee = TextareaField::new('communelycee', 'ville du lycée');
        $equipesString = ArrayField::new('equipesString');

        if (Crud::PAGE_INDEX === $pageName) {
            return [$userNom, $userPrenom, $userAdresse, $userCode, $userVille, $userEmail, $userPhone, $rne, $nomlycee, $communelycee, $equipesString];
        } elseif (Crud::PAGE_DETAIL === $pageName) {
            return [$id, $equipesstring, $user, $equipes];
        } elseif (Crud::PAGE_NEW === $pageName) {
            return [$equipesstring, $user, $equipes];
        } elseif (Crud::PAGE_EDIT === $pageName) {
            return [$equipesstring, $user, $equipes];
        }
    }
}
