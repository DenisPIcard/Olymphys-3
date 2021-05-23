<?php

namespace App\Controller\Admin;

use App\Entity\Docequipes;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class DocequipesCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Docequipes::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Docequipes')
            ->setEntityLabelInPlural('Docequipes')
            ->setPageTitle(Crud::PAGE_INDEX, '<h2>Les documents à télécharger</h2>')
            ->setPageTitle(Crud::PAGE_EDIT, 'Editer le document')
            ->setPageTitle(Crud::PAGE_NEW, 'Nouveau document')
            ->setSearchFields(['id', 'fichier', 'type', 'titre', 'description'])
            ->setPaginatorPageSize(10);
    }

    public function configureFields(string $pageName): iterable
    {
        $type = TextField::new('type');
        $titre = TextField::new('titre');
        $description = TextField::new('description');
        $fichierFile = Field::new('fichierFile');
        $id = IntegerField::new('id', 'ID');
        $fichier = TextField::new('fichier')->setTemplatePath('Admin\\customizations\\vich_uploader_memoiresinter.html.twig');
        $updatedAt = DateTimeField::new('updatedAt');
        $updatedat = DateTimeField::new('updatedat', 'Mis à jour  le ');

        if (Crud::PAGE_INDEX === $pageName) {
            return [$type, $titre, $description, $fichier, $updatedat];
        } elseif (Crud::PAGE_DETAIL === $pageName) {
            return [$id, $fichier, $updatedAt, $type, $titre, $description];
        } elseif (Crud::PAGE_NEW === $pageName) {
            return [$type, $titre, $description, $fichierFile];
        } elseif (Crud::PAGE_EDIT === $pageName) {
            return [$type, $titre, $description, $fichierFile];
        }
    }
}
