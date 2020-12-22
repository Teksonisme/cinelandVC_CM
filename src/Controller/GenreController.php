<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use App\Entity\Genre;


class GenreController extends AbstractController
{
    public function listeGenre(Request $request)
    {
        $genre = new Genre;
        $genres = $this->getDoctrine()
            ->getRepository(Genre::class)->findAll();
        $form = $this->createFormBuilder($genre)
            ->add('nom', TextType::class)
            ->add('Ajouter', SubmitType::class)
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $eM = $this->getDoctrine()->getManager();
            $eM->persist($genre);
            $eM->flush();
            return $this->redirectToRoute('liste_genre');
        }
        return $this->render(
            'genre/liste_genre.html.twig',
            array(
                'genres' => $genres,
                'formulaire' => $form->createView(),
                'titre_liste' => "Liste des genres", 
                'titre_form' => "Ajouter votre genre"
            )
        );
    }
}
