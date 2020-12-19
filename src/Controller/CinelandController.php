<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use App\Entity\Acteur;
use App\Entity\Genre;
use App\Entity\Film;

use App\Form\Type\ActeurType;
use App\Form\Type\FilmType;


class CinelandController extends AbstractController
{

    public function menu()
    {
        return $this->render('menu.html.twig');
    }
    public function listeGenre(Request $request)
    {
        $genre = new Genre;
        $genres = $this->getDoctrine()
            ->getRepository(Genre::class)->findAll();
        $form = $this->createFormBuilder($genre)
            ->add('nom', TextType::class)
            ->add('Apply', SubmitType::class)
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $eM = $this->getDoctrine()->getManager();
            $eM->persist($genre);
            $eM->flush();
            return $this->redirectToRoute('liste_genre');
        }
        return $this->render(
            'liste_genre.html.twig',
            array('genres' => $genres, 'formulaire' => $form->createView())
        );
    }
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
            'liste_acteur.html.twig',
            array('acteurs' => $acteurs, 'formulaire' => $form->createView())
        );
    }
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
        return $this->render(
            'detail_acteur.html.twig',
            [
                'nomPrenom' => $acteur->getNomPrenom(),
                'date_naissance' => $acteur->getDateNaissance(),
                'nationalite' => $acteur->getNationalite(),
                'films' => $acteur->getFilms(),
                'acteur' => $acteur
            ]
        );
    }
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
                array('id' => $acteur->getId())
            )]
        );
        $form->add('submit', SubmitType::class, array('label' => 'Modifier'));
        return $this->render(
            'modifier_acteur.html.twig',
            array('formulaire' => $form->createView())
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
                array('id' => $acteur->getId())
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
                array('id' => $acteur->getId())
            );
            return $this->redirect($url);
        }
        return $this->render(
            'modifier_acteur.html.twig',
            array('formulaire' => $form->createView())
        );
    }
    public function supprimerActeur($id)
    {
        $acteur = $this->getDoctrine()->getRepository(Acteur::class)->find($id);
        if (!$acteur) {
            throw $this->createNotFoundException('L\'acteur[id=' . $id . '] n\'existe pas');
        }
        $eM = $this->getDoctrine()->getManager();
        $eM->remove($acteur);
        $eM->flush();
    }


    public function listeFilm(Request $request)
    {
        $film = new Film;
        $films = $this->getDoctrine()
            ->getRepository(Film::class)->findAll();
        $form = $this->createForm(FilmType::class, $film);
        $form->add('Apply', SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $eM = $this->getDoctrine()->getManager();
            $eM->persist($film);
            $eM->flush();
            return $this->redirectToRoute('liste_film');
        }
        return $this->render(
            'liste_film.html.twig',
            array('films' => $films, 'formulaire' => $form->createView())
        );
    }
    public function detailFilm($id)
    {
        $film = $this->getDoctrine()
            ->getRepository(Film::class)
            ->find($id);
        if (!$film) {
            throw $this->createNotFoundException(
                'Film[id=' . $id . '] doesn\'t exist'
            );
        }
        return $this->render(
            'detail_film.html.twig',
            [
                'titre' => $film->getTitre(),
                'duree' => $film->getDuree(),
                'dateSortie' => $film->getDateSortie(),
                'note' => $film->getNote(),
                'acteurs' => $film->getActeurs(),
                'film' => $film
            ]
        );
    }

}
