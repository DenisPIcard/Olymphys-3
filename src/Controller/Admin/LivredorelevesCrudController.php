<?php

namespace App\Controller\Admin;

use App\Entity\Livredor;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;

class LivredorelevesCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Livredor::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle(Crud::PAGE_INDEX, '<h2>Le livre dor des élèves</h2>')
            ->setSearchFields(['id', 'nom', 'texte', 'categorie'])
            ->setPaginatorPageSize(50)
            ->overrideTemplate('crud/index', 'Admin/customizations/livredor.html.twig');
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(EntityFilter::new('edition'));
    }

    public function configureFields(string $pageName): iterable
    {
        $nom = TextField::new('nom');
        $texte = TextareaField::new('texte');
        $categorie = TextField::new('categorie');
        $edition = AssociationField::new('edition');
        $user = AssociationField::new('user');
        $equipe = AssociationField::new('equipe');
        $id = IntegerField::new('id', 'ID');
        $equipeLettre = TextareaField::new('equipe.lettre');
        $equipeTitreprojet = TextareaField::new('equipe.titreprojet');

        if (Crud::PAGE_INDEX === $pageName) {
            return [$equipeLettre, $equipeTitreprojet, $texte];
        } elseif (Crud::PAGE_DETAIL === $pageName) {
            return [$id, $nom, $texte, $categorie, $edition, $user, $equipe];
        } elseif (Crud::PAGE_NEW === $pageName) {
            return [$nom, $texte, $categorie, $edition, $user, $equipe];
        } elseif (Crud::PAGE_EDIT === $pageName) {
            return [$nom, $texte, $categorie, $edition, $user, $equipe];
        }
    }
}
