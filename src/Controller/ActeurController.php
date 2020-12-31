<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use App\Entity\Film;
use App\Entity\Acteur;
use App\Form\Type\FilmFormType;
use App\Form\Type\ActeurType;

class ActeurController extends AbstractController
{
    # * * * ACTION 3 et 5 * * * 
    #       *     *     *
    public function listeActeur(Request $request)
    {
        $acteur = new Acteur;
        $acteurs = $this->getDoctrine()
            ->getRepository(Acteur::class)->findAll();
        $form = $this->createForm(ActeurType::class, $acteur);
        $form->add('Apply', SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $eM = $this->getDoctrine()->getManager();
            $eM->persist($acteur);
            $eM->flush();
            return $this->redirectToRoute('liste_acteur');
        }
        return $this->render(
            'acteur/liste_acteur_et_form.html.twig',
            array(
                'acteurs' => $acteurs,
                'formulaire' => $form->createView(),
                'titre_form' => "Ajouter un acteur",
                'titre_liste' => "Liste des acteurs :"
            )
        );
    }
    # * * * ACTION 4 et 19 et 21* * * 
    #       *  *  *
    public function detailActeur($id)
    {
        $acteur = $this->getDoctrine()
            ->getRepository(Acteur::class)
            ->find($id);
        if (!$acteur) {
            throw $this->createNotFoundException(
                'Acteur[id=' . $id . '] doesn\'t exist'
            );
        }
        $films = $acteur->getFilms();
        $duree = 0;
        $genres = [];
        foreach ($films as $film) {
            $duree += $film->getDuree();
            if (!in_array($film->getGenre(), $genres)) $genres[] = $film->getGenre();
        }
        return $this->render(
            'acteur/detail_acteur.html.twig',
            [
                'nomPrenom' => $acteur->getNomPrenom(),
                'date_naissance' => $acteur->getDateNaissance()->format('d-m-Y'),
                'nationalite' => $acteur->getNationalite(),
                'films' => $acteur->getFilms(),
                'acteur' => $acteur,
                'duree_films' => $duree,
                'genres' => $genres
            ]
        );
    }
    # * * * ACTION 6 * * * 
    #       *  *  *
    public function modifierActeur($id)
    {
        $acteur = $this->getDoctrine()->getRepository(Acteur::class)->find($id);
        if (!$acteur) {
            throw $this->createNotFoundException('L\'acteur[id=' . $id . '] n\'existe pas');
        }
        $form = $this->createForm(
            ActeurType::class,
            $acteur,
            ['action' => $this->generateUrl(
                'modifier2_acteur',
                ['id' => $acteur->getId()]
            )]
        );
        $form->add('submit', SubmitType::class, array('label' => 'Modifier'));
        return $this->render(
            'acteur/modifier_acteur.html.twig',
            ['formulaire' => $form->createView()]
        );
    }
    public function modifier2Acteur(Request $request, $id)
    {
        $acteur = $this->getDoctrine()->getRepository(Acteur::class)->find($id);
        if (!$acteur)
            throw $this->createNotFoundException('acteur[id=' . $id . '] inexistante');
        $form = $this->createForm(
            ActeurType::class,
            $acteur,
            ['action' => $this->generateUrl(
                'modifier2_acteur',
                ['id' => $acteur->getId()]
            )]
        );
        $form->add('submit', SubmitType::class, array('label' => 'Modifier'));
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $eM = $this->getDoctrine()->getManager();
            $eM->persist($acteur);
            $eM->flush();
            $url = $this->generateUrl(
                'detail_acteur',
                ['id' => $acteur->getId()]
            );
            return $this->redirect($url);
        }
        return $this->render(
            'acteur/modifier_acteur.html.twig',
            ['formulaire' => $form->createView()]
        );
    }
    # * * * ACTION 7 * * * 
    #       *  *  *
    public function supprimerActeur($id)
    {
        $acteur = $this->getDoctrine()->getRepository(Acteur::class)->find($id);
        if (!$acteur) {
            throw $this->createNotFoundException('L\'acteur[id=' . $id . '] n\'existe pas');
        }
        $eM = $this->getDoctrine()->getManager();
        $eM->remove($acteur);
        $eM->flush();

        return $this->redirectToRoute('liste_acteur');
    }
    # * * * ACTION 15 * * * 
    #       *   *   *
    public function acteurSelonFilm(Request $request)
    {
        $film = new Film;
        $form = $this->createForm(
            FilmFormType::class,
            $film
        )
            ->add('Rechercher', SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $film = $this->getDoctrine()->getRepository(Film::class)
                ->findByName($form->getData()->getTitre());

            $acteurs = $film->getActeurs();
            return $this->render(
                'acteur/liste_acteur_et_form.html.twig',
                [
                    'formulaire' => $form->createView(),
                    'acteurs' => $acteurs,
                    'titre_liste' => "Liste des acteurs",
                    'titre_form' => "Rechercher les acteurs selon un film :"
                ]
            );
        }
        return $this->render(
            'acteur/liste_acteur_et_form.html.twig',
            [
                'formulaire' => $form->createView(),
                'acteurs' => [],
                'titre_liste' => "",
                'titre_form' => "Rechercher les acteurs selon un film :"
            ]
        );
    }
    # * * * ACTION 16 * * * 
    #       *   *   *
    public function acteurAvecTroisFilms()
    {
        $acteurs = $this->getDoctrine()->getRepository(Acteur::class)
            ->findAll();
        foreach ($acteurs as $acteur) {
            if (count($acteur->getFilms()) >= 3) {
                $acteurs3[] = $acteur;
            }
        }
        return $this->render(
            'acteur/liste_acteur.html.twig',
            [
                'acteurs' => $acteurs3,
                'titre_liste' => "Acteur(s) ayant plus de trois films : ",
                'titre_form' => ""
            ]
        );
    }
    # * * * ACTION 20 * * * 
    #       *   *   *
    public function listeActeursFilmChrono()
    {
        $acteurs = $this->getDoctrine()->getRepository(Acteur::class)
            ->findAll();
        $filmsSorted = [];
        foreach ($acteurs as $acteur) {
            $tab_films = [];
            $films = $acteur->getFilms();
            foreach ($films as $film) {
                $tab_films[] = $film;
                usort($tab_films, function ($a, $b) {
                    if ($b->getDateSortie() < $a->getDateSortie()) return 1;
                    else return 0;
                });
            }
            if (count($tab_films) > 0) {
                $filmsSorted[$acteur->getNomPrenom()] = $tab_films;
            }
        }
        return $this->render(
            'acteur/liste_acteur_film_chrono.html.twig',
            [
                'films' => $filmsSorted,
                'titre_liste' => "Liste des acteurs ",
                'titre_form' => ""
            ]
        );
    }
}
