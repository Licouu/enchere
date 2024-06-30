<?php

namespace App\Controller;

use Ramsey\Uuid\Uuid;
use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Form\EditPasswordForm;
use App\Form\EditEmailForm;
use App\Form\EditNotifForm;
use App\Repository\UserRepository;
use App\Security\EmailVerifier;
use App\Security\LoginAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;

class RegistrationController extends AbstractController
{
    private EmailVerifier $emailVerifier;
    private LoginAuthenticator $authenticator;

    public function __construct(EmailVerifier $emailVerifier, LoginAuthenticator $authenticator)
    {
        $this->emailVerifier = $emailVerifier;
        $this->authenticator = $authenticator;
    }

    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, UserAuthenticatorInterface $authenticatorManager): Response
    {
        //on créer l'utilisateur
        $user = new User();
        //on créer le formulaire associé
        $form = $this->createForm(RegistrationFormType::class, $user);
        //on vérifie le formulaire
        $form->handleRequest($request);
        //on vérifie qu'il soit soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // on hash le mot de passe
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );
            //on set la date de création
            $user->setCreatedAt(new \DateTimeImmutable());
            $entityManager->persist($user);
            $entityManager->flush();
            // génère un email de confirmation
            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                (new TemplatedEmail())
                    ->from(new Address('matthew83@outlook.fr', 'Instrumenthol'))
                    ->to($user->getEmail())
                    ->subject('Please Confirm your Email')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
            );
            $authenticatorManager->authenticateUser($user, $this->authenticator, $request);
            return $this->redirectToRoute('app_instrumenthol');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
    
    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, TranslatorInterface $translator, UserRepository $userRepository): Response
    {
        //on récupère l'id de la requête
        $id = $request->get('id');
        //si il est null on redirige l'utilisateur vers la page d'inscription
        if (null === $id) {
            return $this->redirectToRoute('app_register');
        }
        //on récupère l'utilisateur en fonction de l'id
        $user = $userRepository->find($id);

        if (null === $user) {
            return $this->redirectToRoute('app_register');
        }

        // lien de confirmation de l'email, set User::isVerified=true et persist
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $user);
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));

            return $this->redirectToRoute('app_register');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Your email address has been verified.');

        return $this->redirectToRoute('app_instrumenthol');
    }

    #[Route('/edit_profil',name:'app_edit_profil')]
    public function editUserProfil(Request $request, UserRepository $userRepository, UserPasswordHasherInterface        $userPasswordHasher,EntityManagerInterface $entityManager ){//, EncoderFactory $encoderFactory
        //création des formulaires associés à la modification du mot de passe, de l'email et de la notification
        $form_edit_password = $this->createForm(EditPasswordForm::class);
        $form_edit_email = $this->createForm(EditEmailForm::class);
        $form_edit_notif = $this->createForm(EditNotifForm::class);
        //on vérifie les formulaires
        $form_edit_notif->handleRequest($request);
        $form_edit_password->handleRequest($request);
        $form_edit_email->handleRequest($request);
        //on vérifie que le formulaire soit soumis et valide
        if($form_edit_password->isSubmitted() && $form_edit_password->isValid()){
            //on récupère toutes les données associés
            $oldPassword = $form_edit_password->getData()['inputPasswordold'];
            $new1Password = $form_edit_password->getData()['inputPasswordnew1'];
            $new2Password = $form_edit_password->getData()['inputPasswordnew2'];
            $id_user = $this->getUser()->getUserIdentifier();
            $userId = Uuid::fromString($id_user);
            $user = $entityManager->find(User::class,$userId);
            $oldPassword = (string) $oldPassword;
            $new1Password = (string) $new1Password;
            $new2Password = (string) $new2Password;
            //try catch qui modifie le profile si il n'y a pas d'erreur
            try{
            if(!$userPasswordHasher->isPasswordValid($this->getUser(),$oldPassword)){
                return $this->redirectToRoute('app_edit_profil');
            }else{
                if($new1Password == $new2Password){
                    $userRepository->upgradePassword($user,$userPasswordHasher->hashPassword($user,$new1Password));//peut creer une exception
                    return $this->redirectToRoute('app_instrumenthol');
                }else{
                    dd($new1Password,$new2Password,"Les nouveaux deux mots de passes ne sont pas identiquent");
                    return $this->redirectToRoute('app_edit_profil');
                }
            }
            }catch(Exception $e){
                return $this->render('registration/edit_account.html.twig', [
                    'form_edit_password' => $form_edit_password->createView(),
                    'form_edit_email' => $form_edit_email->createView(),
                    'form_edit_notif' => $form_edit_notif->createView(),
                ]);
            }
        }
        //Modification email
        if($form_edit_email->isSubmitted() && $form_edit_email->isValid()){
            //dd($form_edit_email);
            $newEmail1 = $form_edit_email->getData()['email1'];
            $newEmail2 = $form_edit_email->getData()['email2'];

            $user = $this->getUser();
            if($newEmail1 == $newEmail2){
                $user->setEmail($newEmail1);
                $entityManager->persist($user);
                $entityManager->flush();
                return $this->redirectToRoute('app_instrumenthol');
            }else{
                dd("les deux adresses mail rentré sont différentent");
                return $this->redirectToRoute('app_edit_profil');
            }
        }

        //Modification notif
        if($form_edit_notif->isSubmitted() && $form_edit_notif->isValid()){
            $business = $form_edit_notif->getData()['business'];
            $aution = $form_edit_notif->getData()['auction'];
            return $this->redirectToRoute('app_instrumenthol');
        }

        
        return $this->render('registration/edit_account.html.twig', [
            'form_edit_password' => $form_edit_password->createView(),
            'form_edit_email' => $form_edit_email->createView(),
            'form_edit_notif' => $form_edit_notif->createView(),
        ]);
    }
}
