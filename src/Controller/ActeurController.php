<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use App\Entity\Acteur;
use App\Form\Type\ActeurType;

class ActeurController extends AbstractController
{
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
            'acteur/liste_acteur.html.twig',
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
            'acteur/detail_acteur.html.twig',
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
            'acteur/modifier_acteur.html.twig',
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
                'acteur/modifier2_acteur',
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

        return $this->redirectToRoute('liste_acteur');
    }
}
