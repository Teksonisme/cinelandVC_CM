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

class FilmController extends AbstractController
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
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($genre);
            $entityManager->flush();
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
        $form = $this->createFormBuilder($acteur)
            ->add('nomPrenom', TextType::class)
            ->add('dateNaissance', TextType::class)
            ->add('nationalite', TextType::class)
            ->add('Apply', SubmitType::class)
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($acteur);
            $entityManager->flush();
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
            'films' => $acteur->getFilms()
            ]
        );
    }
}
