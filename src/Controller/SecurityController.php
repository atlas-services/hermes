<?php

namespace App\Controller;

use App\Entity\Hermes\Contact;
use App\Entity\Hermes\User;
use App\Mailer\Mailer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/{_locale}")
 */
class SecurityController extends AbstractController
{

    /**
     * @Route("/forgotten_password", name="app_forgotten_password")
     */
    public function forgottenPassword(Request $request, Mailer $mailer,TokenGeneratorInterface $tokenGenerator): Response
    {
        if ($request->isMethod('POST')) {

            $email = $request->request->get('email');

            $entityManager = $this->getDoctrine()->getManager();
            $user = $entityManager->getRepository(User::class)->findOneByEmail($email);
            /* @var $user User */

            if ($user === null) {
                $this->addFlash('danger', 'Email ou mot de passe Inconnu');
                return $this->redirectToRoute('app_login');
            }
            $token = $tokenGenerator->generateToken();

            try {
                $user->setResetToken($token);
                $entityManager->flush();
            } catch (\Exception $e) {
                $this->addFlash('warning', $e->getMessage());
                return $this->redirectToRoute('app_login');
            }

            $url = $this->generateUrl('app_reset_password', array('token' => $token), UrlGeneratorInterface::ABSOLUTE_URL);

            $contact = new Contact();
            $contact->setName($user->getFirstname());
            $contact->setEmail($user->getEmail());
            $contact->setMessage('Reset');
            $contact->setTelephone('0122334455');
            $context = [
                'url' => $url,
                'username' => $user->getUsername(),
            ];
            $return = $mailer->send($contact, $user->getEmail(), 'Reset Password', 'admin/security/sent_forgotten_password.html.twig', $context);

            $this->addFlash($return['type'], $return['message']);

            return $this->redirectToRoute('app_login');
        }

        return $this->render('admin/security/forgotten_password.html.twig');
    }

    /**
     * @Route("/reset_password/{token}", name="app_reset_password")
     */
    public function resetPassword(Request $request, string $token)
    {

        if ($request->isMethod('POST')) {
            $entityManager = $this->getDoctrine()->getManager();

            $user = $entityManager->getRepository(User::class)->findOneByResetToken($token);
            /* @var $user User */

            if ($user === null) {
                $this->addFlash('danger', 'Token Inconnu');
                return $this->redirectToRoute('app_login');
            }

            $user->setResetToken(null);

            $user->setPassword($request->request->get('password'));
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('notice', 'Mot de passe mis à jour');

            return $this->redirectToRoute('admin_index');
        }else {

            return $this->render('admin/security/reset_password.html.twig', ['token' => $token]);
        }

    }

    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //    $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('admin/security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \Exception('This method can be blank - it will be intercepted by the logout key on your firewall');
    }

    /**
     * @Route("/change_locale/{locale}", name="change_locale")
     */
    public function changeLocale($locale, Request $request, RouterInterface $router)
    {
        // On stocke la langue dans la session
        $request->getSession()->set('locale', $locale);
        $request->getSession()->set('_locale', $locale);

        $referer = $request->headers->get('referer');
        $refererPathInfo = Request::create($referer)->getPathInfo();
        $refererPathInfo = str_replace($request->getScriptName(), '', $refererPathInfo);
        $routeInfos = $router->match($refererPathInfo);

        return $this->redirectToRoute($routeInfos['_route'], ['_locale'=> $locale]);

    }

    /**
     * @Route("/new", name="app_customer_new")
     */
    public function customer_new(Request $request, TranslatorInterface $translator)
    {

        if ($request->isMethod('POST')) {
            $entityManager = $this->getDoctrine()->getManager();

            $firstname = $request->request->get('firstname');
            $lastname = $request->request->get('lastname');
            $email = $request->request->get('email_new');
            $password = $request->request->get('password_new');
            $user = $entityManager->getRepository(User::class)->findOneByEmail($email);
            /* @var $user User */

            if ($user !== null) {
                $notification = $translator->trans('user.exist');
                $this->addFlash('info', $notification);
                return $this->redirectToRoute('app_login');
            }

            $user = new User();
            $user->setRoles(['ROLE_CUSTOMER']);
            $user->setFirstname($firstname);
            $user->setLastname($lastname);
            $user->setEmail($email);
            $user->setPassword($password);

            $entityManager->persist($user);
            $entityManager->flush();

            $notification = $translator->trans('customer.created');
            $referer = $request->headers->get('referer'); // get the referer
            if(true == strpos($referer, 'compte')){
                $notification .= $translator->trans('customer.resume_purchase');
                $this->addFlash('notice', $notification);
                return $this->redirectToRoute('order_account');
            }
            $this->addFlash('notice', $notification);
            return $this->redirectToRoute('customer_index');
        }else {
            return $this->render('admin/security/reset_password.html.twig', ['token' => $token]);
        }

    }
}
