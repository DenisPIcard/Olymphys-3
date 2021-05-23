<?php

namespace App\Controller\Admin;

use App\Entity\Equipesadmin;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;

class EtablissementsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Equipesadmin::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setSearchFields(['id', 'lettre', 'numero', 'titreProjet', 'nomLycee', 'denominationLycee', 'lyceeLocalite', 'lyceeAcademie', 'prenomProf1', 'nomProf1', 'prenomProf2', 'nomProf2', 'rne', 'contribfinance', 'origineprojet', 'recompense', 'partenaire', 'description', 'nbeleves'])
            ->setPaginatorPageSize(50)
            ->overrideTemplate('crud/index', 'Admin/customizations/list_equipescia.html.twig');
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(EntityFilter::new('edition'));
    }

    public function configureFields(string $pageName): iterable
    {
        $lettre = TextField::new('lettre');
        $numero = IntegerField::new('numero');
        $selectionnee = Field::new('selectionnee');
        $titreProjet = TextField::new('titreProjet');
        $nomLycee = TextField::new('nomLycee');
        $denominationLycee = TextField::new('denominationLycee');
        $lyceeLocalite = TextField::new('lyceeLocalite');
        $lyceeAcademie = TextField::new('lyceeAcademie');
        $prenomProf1 = TextField::new('prenomProf1');
        $nomProf1 = TextField::new('nomProf1');
        $prenomProf2 = TextField::new('prenomProf2');
        $nomProf2 = TextField::new('nomProf2');
        $rne = TextField::new('rne', 'Code UAI');
        $contribfinance = TextField::new('contribfinance');
        $origineprojet = TextField::new('origineprojet');
        $recompense = TextField::new('recompense');
        $partenaire = TextField::new('partenaire');
        $createdAt = DateTimeField::new('createdAt');
        $description = TextareaField::new('description');
        $inscrite = Field::new('inscrite');
        $nbeleves = IntegerField::new('nbeleves');
        $rneId = AssociationField::new('rneId');
        $centre = AssociationField::new('centre');
        $edition = AssociationField::new('edition');
        $idProf1 = AssociationField::new('idProf1');
        $idProf2 = AssociationField::new('idProf2');
        $id = IntegerField::new('id', 'ID');
        $editionEd = TextareaField::new('edition.ed');
        $rneIdNom = TextareaField::new('rneId.nom');
        $rneIdAcademie = TextareaField::new('rneId.academie');
        $rneIdAdresse = TextareaField::new('rneId.adresse', 'adresse');
        $rneIdCommune = TextareaField::new('rneId.commune', 'commune');
        $rneIdCodePostal = TextareaField::new('rneId.codePostal', 'CP');

        if (Crud::PAGE_INDEX === $pageName) {
            return [$editionEd, $rneIdNom, $rne, $rneIdAcademie, $rneIdAdresse, $rneIdCommune, $rneIdCodePostal];
        } elseif (Crud::PAGE_DETAIL === $pageName) {
            return [$id, $lettre, $numero, $selectionnee, $titreProjet, $nomLycee, $denominationLycee, $lyceeLocalite, $lyceeAcademie, $prenomProf1, $nomProf1, $prenomProf2, $nomProf2, $rne, $contribfinance, $origineprojet, $recompense, $partenaire, $createdAt, $description, $inscrite, $nbeleves, $rneId, $centre, $edition, $idProf1, $idProf2];
        } elseif (Crud::PAGE_NEW === $pageName) {
            return [$lettre, $numero, $selectionnee, $titreProjet, $nomLycee, $denominationLycee, $lyceeLocalite, $lyceeAcademie, $prenomProf1, $nomProf1, $prenomProf2, $nomProf2, $rne, $contribfinance, $origineprojet, $recompense, $partenaire, $createdAt, $description, $inscrite, $nbeleves, $rneId, $centre, $edition, $idProf1, $idProf2];
        } elseif (Crud::PAGE_EDIT === $pageName) {
            return [$lettre, $numero, $selectionnee, $titreProjet, $nomLycee, $denominationLycee, $lyceeLocalite, $lyceeAcademie, $prenomProf1, $nomProf1, $prenomProf2, $nomProf2, $rne, $contribfinance, $origineprojet, $recompense, $partenaire, $createdAt, $description, $inscrite, $nbeleves, $rneId, $centre, $edition, $idProf1, $idProf2];
        }
    }
}
