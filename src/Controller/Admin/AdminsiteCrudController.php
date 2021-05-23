<?php

namespace App\Controller\Admin;

use App\Entity\Edition;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class AdminsiteCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Edition::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle(Crud::PAGE_INDEX, 'Réglage du site')
            ->setSearchFields(['id', 'ed', 'ville', 'lieu', 'annee'])
            ->setPaginatorPageSize(30);
    }

    public function configureFields(string $pageName): iterable
    {
        $ed = TextField::new('ed');
        $annee = TextField::new('annee');
        $ville = TextField::new('ville');
        $date = DateTimeField::new('date');
        $lieu = TextField::new('lieu');
        $dateouverturesite = DateTimeField::new('dateouverturesite');
        $dateclotureinscription = DateTimeField::new('dateclotureinscription');
        $datelimcia = DateTimeField::new('datelimcia');
        $datelimnat = DateTimeField::new('datelimnat');
        $concourscia = DateTimeField::new('concourscia');
        $concourscn = DateTimeField::new('concourscn');
        $id = IntegerField::new('id', 'ID');

        if (Crud::PAGE_INDEX === $pageName) {
            return [$ed, $annee, $ville, $date, $lieu, $dateouverturesite, $dateclotureinscription, $datelimcia, $datelimnat, $concourscia, $concourscn];
        } elseif (Crud::PAGE_DETAIL === $pageName) {
            return [$id, $ed, $date, $ville, $lieu, $datelimcia, $datelimnat, $dateouverturesite, $concourscia, $concourscn, $dateclotureinscription, $annee];
        } elseif (Crud::PAGE_NEW === $pageName) {
            return [$ed, $annee, $ville, $date, $lieu, $dateouverturesite, $dateclotureinscription, $datelimcia, $datelimnat, $concourscia, $concourscn];
        } elseif (Crud::PAGE_EDIT === $pageName) {
            return [$ed, $annee, $ville, $date, $lieu, $dateouverturesite, $dateclotureinscription, $datelimcia, $datelimnat, $concourscia, $concourscn];
        }
    }
}
