<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Form\UserEmailConfirmationType;
use App\Form\UserRegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;
use Symfony\Component\Security\Core\Security;
use App\Repository\UserRepository;

class SecurityController extends AbstractController
{
    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/register", name="app_register")
     */
    public function Register(Request $request, EntityManagerInterface $em, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = new User();
        $registerForm= $this->createForm(UserRegistrationFormType::class, $user);
        $registerForm->handleRequest($request);

        if ($registerForm->isSubmitted() && $registerForm->isValid()) {

            $user->setEmail($registerForm['email']->getData())
                 ->setPseudo($registerForm['pseudo']->getData())
                 ->setPassword($passwordEncoder->encodePassword(
                    $user,
                    $registerForm['password']->getData()
                 ))
                 ->setIsConfirmed(false)
            ;

            $em->persist($user);
            $em->flush();

            //Envoi d'un mail de confirmation
            $email = (new TemplatedEmail())
            ->from('lmarcus4280@yahoo.com')
            ->to($user->getEmail())
            ->subject(sprintf('"%s" confirmer votre inscription ',$user->getPseudo()))
            ->htmlTemplate('email/signup.html.twig')
            ->context([
                'expiration_date' => new \DateTime('+7 days'),
                'pseudo' => $user->getPseudo(),
                'url'=> 'http://127.0.0.1:8000/user-confirmation/' . $user->getId() . '/' . $user->getSecurityToken()
                
                ])
            ;   

            $this->mailer->send($email);
            $this->addFlash('success', 'Vous etes inscrits!');
        }
        return $this->render('security/register.html.twig', [
            'register_form'=> $registerForm->createView()    
        ]);       
    }

    /**
     * @Route("/user-confirmation/{id}/{token}", name="user_confirmation")
     */
    public function confirmedUser( Security $security, $token, $id, UserRepository $userRepository, EntityManagerInterface $em)
    {
        
        $user = $userRepository->findOneBy(['id'=> $id]);
        
        // Si l'utilisateur a deja confirmé son compte 
        if ($user->getIsConfirmed()===true) {

            $this->addFlash('confirm1', 'Vous avez deja confirmé votre compte!');
            return $this->redirectToRoute('app_login');
        }

        //Si le token ne correspond pas au securityToken de l'utilisateur
        if ($token != $user->getSecurityToken()) {
            
            $flash =$this->addFlash('comfirm2', 'Ce compte ne correspond pas au votre!');
            return $this->redirectToRoute('app_login', []);
        }

        //Si le token correspond au securityToken de l'utilisateur
        if ($token === $user->getSecurityToken()) {
            $user->setIsConfirmed(true)
                 ->renewToken()
            ;

            $em->persist($user);
            $em->flush();

            $this->addFlash('confirm3', 'Votre compte vient d\'etre confirmé');
            return $this->redirectToRoute('app_login');
        }

        return $this->render();   
    }

    /**
     * @Route("/email-confirmation", name="email_confirmation")
     */
    public function resendConfirmation(Request $request, EntityManagerInterface $em, UserRepository $userRepository)
    {
        $user = new User();
        $confirmationForm= $this->createForm(UserEmailConfirmationType::class, $user);
        $confirmationForm->handleRequest($request);

        if ($confirmationForm->isSubmitted() && $confirmationForm->isValid()) {

            // si l'adresse mail est inconnu
            if (!$userRepository->findOneBy(['email'=> $confirmationForm['email']->getData()])){
                
                $this->addFlash('confirm4', 'Votre email n\'est pas reconnu ');
                return $this->redirectToRoute('app_register');
            }

            //Si le compte est deja confirmé

            if ($userRepository->findBy(['email'=> $confirmationForm['email']->getData(), 'is_confirmed' => true])){
                
                $user->$userRepository->findOneBy(['email'=> $confirmationForm['email']->getData()]);
                $user->renewToken();
                $this->addFlash('confirm1', 'Vous avez deja confirmé votre compte!');
                return $this->redirectToRoute('app_login');

            }

            //Si le compte n'est pas encore confirmé
            if ($userRepository->findBy(['email'=> $confirmationForm['email']->getData(), 'is_confirmed' => false])){

            $user->$userRepository->findOneBy(['email'=> $confirmationForm['email']->getData()]);
            $user->renewToken();
            //Envoi d'un mail de confirmation
            $email = (new TemplatedEmail())
            ->from('lmarcus4280@yahoo.com')
            ->to($user->getEmail())
            ->subject(sprintf('"%s" confirmer votre inscription ',$user->getPseudo()))
            ->htmlTemplate('email/signup.html.twig')
            ->context([
                'expiration_date' => new \DateTime('+7 days'),
                'pseudo' => $user->getPseudo(),
                'url'=> 'http://127.0.0.1:8000/user-confirmation/' . $user->getId() . '/' . $user->getSecurityToken()
                
                ])
            ;  
            $this->addFlash('confirm5', 'Un mail vient de vous etre envoyé ');
            return $this->redirectToRoute('app_login');

            }
        }

        return $this->render('security/confirmation.html.twig', [
            'confirmation_form'=> $confirmationForm->createView()
            ]);   
    }


    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \Exception('This method can be blank - it will be intercepted by the logout key on your firewall');
    }


     






}
