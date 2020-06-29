<?php

namespace App\Controller;

use App\Entity\Admin;
use App\Form\AdminRegistrationType;
use Doctrine\ORM\EntityManagerInterface;;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class AdminController extends AbstractController
{

    /**
     * @Route("/admininscription", name="admin_registration")
     */
    public function adminregistration(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder) {
        $admin = new Admin();

        $form = $this->createForm(AdminRegistrationType::class, $admin);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $hash = $encoder->encodePassword($admin, $admin->getPassword());

            $admin->setPassword($hash);

            $manager->persist($admin);
            $manager->flush();

            return $this->redirectToRoute('admin_login');
        }

        return $this->render('admin/registration.html.twig', [
         'form' => $form->createView()   
        ]);
    }
   
    /**
     * @Route("/adminconnexion", name="admin_login")
     */
    public function login(){
        return $this->render('admin/login.html.twig');
    }

      /**
     * @Route("/admindeconnexion", name="admin_logout")
     */
    public function logout(){}
}
