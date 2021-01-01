<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Acteur;
use App\Entity\Film;
use App\Entity\Genre;

class CinelandController extends AbstractController
{

    public function menu(Session $session)
    {
        return $this->render('menu.html.twig');
    }

    public function pageNotFound(Session $session)
    {
        $session->getFlashBag()
            ->add('pageNotFound', 'Il semblerait que vous ayez accéder à une page inexistante.');
        return $this->redirectToRoute('menu');
    }
    public function init()
    {

        $eM = $this->getDoctrine()->getManager();

        // * * * ACTEURS * * *
        for ($i = 0; $i < 11; $i++) {
            $acteurs[$i] = new Acteur;
        }
        $acteurs[0]->setNomPrenom("Galabru Michel")
            ->setDateNaissance(\DateTime::createFromFormat('d-m-Y', "27-10-1922"))
            ->setNationalite('france');

        $acteurs[1]->setNomPrenom("Deneuve Catherine")
            ->setDateNaissance(\DateTime::createFromFormat('d-m-Y', "22-10-1943"))
            ->setNationalite('france');

        $acteurs[2]->setNomPrenom("Depardieu Gérard")
            ->setDateNaissance(\DateTime::createFromFormat('d-m-Y', "27-12-1948"))
            ->setNationalite('russie');

        $acteurs[3]->setNomPrenom("Lanvin Gérard")
            ->setDateNaissance(\DateTime::createFromFormat('d-m-Y', "21-06-1950"))
            ->setNationalite('france');

        $acteurs[4]->setNomPrenom("Désiré Dupond")
            ->setDateNaissance(\DateTime::createFromFormat('d-m-Y', "23-12-2001"))
            ->setNationalite('groland');

        $acteurs[5]->setNomPrenom("Silence Frêne")
            ->setDateNaissance(\DateTime::createFromFormat('d-m-Y', "05-08-1997"))
            ->setNationalite('autriche');

        $acteurs[6]->setNomPrenom("Noir Lumineux")
            ->setDateNaissance(\DateTime::createFromFormat('d-m-Y', "18-02-1988"))
            ->setNationalite('irlande');

        $acteurs[7]->setNomPrenom("Pierre Moussue")
            ->setDateNaissance(\DateTime::createFromFormat('d-m-Y', "13-09-1957"))
            ->setNationalite('angleterre');

        $acteurs[8]->setNomPrenom("Acteur Ursule")
            ->setDateNaissance(\DateTime::createFromFormat('d-m-Y', "29-08-2012"))
            ->setNationalite('chine');

        $acteurs[9]->setNomPrenom("Poire Pomme")
            ->setDateNaissance(\DateTime::createFromFormat('d-m-Y', "27-12-2004"))
            ->setNationalite('hongrie');

        $acteurs[10]->setNomPrenom("Lance Vindicte")
            ->setDateNaissance(\DateTime::createFromFormat('d-m-Y', "22-11-1908"))
            ->setNationalite('australie');

        foreach ($acteurs as $acteur) {
            $eM->persist($acteur);
        }

        // * * * GENRE * * *
        for ($i = 0; $i < 10; $i++) {
            $genres[$i] = new Genre;
        }
        $genres[0]->setNom('animation');
        $genres[1]->setNom('policier');
        $genres[2]->setNom('drame');
        $genres[3]->setNom('comédie');
        $genres[4]->setNom('X');
        $genres[5]->setNom('sci-fi');
        $genres[6]->setNom('histoire');
        $genres[7]->setNom('science');
        $genres[8]->setNom('fantastique');
        $genres[9]->setNom('action');

        foreach ($genres as $genre) {
            $eM->persist($genre);
        }

        // * * * FILM * * *
        for ($i = 0; $i < 16; $i++) {
            $films[$i] = new Film;
        }

        $films[0]->setTitre('Astérix aux jeux olympiques')
            ->setDuree(117)
            ->setDateSortie(\DateTime::createFromFormat('d-m-Y', "20-01-2008"))
            ->setNote(8)
            ->setAgeMinimal(0);
        $genres[0]->addFilm($films[0]);

        $films[1]->setTitre('Le Dernier Métro')
            ->setDuree(131)
            ->setDateSortie(\DateTime::createFromFormat('d-m-Y', "17-09-1980"))
            ->setNote(15)
            ->setAgeMinimal(12)
            ->addActeur($acteurs[1])
            ->addActeur($acteurs[2]);
        $genres[2]->addFilm($films[1]);

        $films[2]->setTitre('Le choix des armes')
            ->setDuree(135)
            ->setDateSortie(\DateTime::createFromFormat('d-m-Y', "19-10-1981"))
            ->setNote(13)
            ->setAgeMinimal(18)
            ->addActeur($acteurs[1])
            ->addActeur($acteurs[2])
            ->addActeur($acteurs[3]);
        $genres[1]->addFilm($films[2]);

        $films[3]->setTitre('Les Parapluies de Cherbourg')
            ->setDuree(91)
            ->setDateSortie(\DateTime::createFromFormat('d-m-Y', "19-02-1964"))
            ->setNote(9)
            ->setAgeMinimal(0)
            ->addActeur($acteurs[1]);
        $genres[2]->addFilm($films[3]);

        $films[4]->setTitre('La Guerre des boutons')
            ->setDuree(90)
            ->setDateSortie(\DateTime::createFromFormat('d-m-Y', "18-04-1962"))
            ->setNote(7)
            ->setAgeMinimal(0)
            ->addActeur($acteurs[0]);
        $genres[3]->addFilm($films[4]);

        $films[5]->setTitre("L'étoile de la mort")
            ->setDuree(145)
            ->setDateSortie(\DateTime::createFromFormat('d-m-Y', "10-08-2000"))
            ->setNote(14)
            ->setAgeMinimal(12)
            ->addActeur($acteurs[5])
            ->addActeur($acteurs[7])
            ->addActeur($acteurs[3]);
        $genres[5]->addFilm($films[5]);

        $films[6]->setTitre('Le seigneur des anneaux')
            ->setDuree(162)
            ->setDateSortie(\DateTime::createFromFormat('d-m-Y', "08-10-1999"))
            ->setNote(19)
            ->setAgeMinimal(12)
            ->addActeur($acteurs[10])
            ->addActeur($acteurs[8])
            ->addActeur($acteurs[2]);
        $genres[8]->addFilm($films[6]);

        $films[7]->setTitre('Petit poney')
            ->setDuree(35)
            ->setDateSortie(\DateTime::createFromFormat('d-m-Y', "04-02-2002"))
            ->setNote(11)
            ->setAgeMinimal(0)
            ->addActeur($acteurs[9]);

        $films[8]->setTitre('Les pates, assez !')
            ->setDuree(46)
            ->setDateSortie(\DateTime::createFromFormat('d-m-Y', "14-09-1986"))
            ->setNote(8)
            ->setAgeMinimal(0)
            ->addActeur($acteurs[4]);
        $genres[7]->addFilm($films[8]);

        $films[9]->setTitre('Alien 5')
            ->setDuree(128)
            ->setDateSortie(\DateTime::createFromFormat('d-m-Y', "18-04-1978"))
            ->setNote(18)
            ->setAgeMinimal(0)
            ->addActeur($acteurs[7])
            ->addActeur($acteurs[5])
            ->addActeur($acteurs[6]);
        $genres[5]->addFilm($films[9]);

        $films[10]->setTitre('La ligne verte')
            ->setDuree(102)
            ->setDateSortie(\DateTime::createFromFormat('d-m-Y', "10-11-1945"))
            ->setNote(11)
            ->setAgeMinimal(0)
            ->addActeur($acteurs[4])
            ->addActeur($acteurs[8])
            ->addActeur($acteurs[7])
            ->addActeur($acteurs[10]);
        $genres[2]->addFilm($films[10]);

        $films[11]->setTitre('Retour vers le futur')
            ->setDuree(145)
            ->setDateSortie(\DateTime::createFromFormat('d-m-Y', "15-06-1986"))
            ->setNote(18)
            ->setAgeMinimal(0)
            ->addActeur($acteurs[4])
            ->addActeur($acteurs[7]);
        $genres[5]->addFilm($films[11]);

        $films[12]->setTitre('Mission impossible')
            ->setDuree(57)
            ->setDateSortie(\DateTime::createFromFormat('d-m-Y', "03-01-1985"))
            ->setNote(7)
            ->setAgeMinimal(0)
            ->addActeur($acteurs[9])
            ->addActeur($acteurs[5])
            ->addActeur($acteurs[3]);
        $genres[9]->addFilm($films[12]);

        $films[13]->setTitre('Skyfall')
            ->setDuree(98)
            ->setDateSortie(\DateTime::createFromFormat('d-m-Y', "10-11-2015"))
            ->setNote(13)
            ->setAgeMinimal(0)
            ->addActeur($acteurs[10]);
        $genres[9]->addFilm($films[13]);

        $films[14]->setTitre('Warcraft : le commencement')
            ->setDuree(103)
            ->setDateSortie(\DateTime::createFromFormat('d-m-Y', "25-08-2015"))
            ->setNote(12)
            ->setAgeMinimal(0)
            ->addActeur($acteurs[2])
            ->addActeur($acteurs[10]);
        $genres[8]->addFilm($films[14]);

        $films[15]->setTitre('Premier Contact')
            ->setDuree(117)
            ->setDateSortie(\DateTime::createFromFormat('d-m-Y', "13-05-2014"))
            ->setNote(17)
            ->setAgeMinimal(0)
            ->addActeur($acteurs[6])
            ->addActeur($acteurs[9])
            ->addActeur($acteurs[1]);
        $genres[5]->addFilm($films[15]);
        
        foreach($films as $film){
            $eM->persist($film);
        }













        $eM->flush();
        return $this->redirectToRoute('menu');
    }
}
