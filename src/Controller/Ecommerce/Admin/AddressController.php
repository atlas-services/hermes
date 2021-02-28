<?php

namespace App\Controller\Ecommerce\Admin;

use App\Entity\Hermes\Delivery;
use App\Entity\Menu;
use App\Entity\Sheet;
use App\Entity\Hermes\Address;
use App\Ecommerce\AddressClient;
use App\Repository\AddressRepository;
use App\Form\Admin\Ecommerce\AddressType;
use App\Form\Admin\Ecommerce\CountryType;
use App\Form\Admin\Ecommerce\AddressFRType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * @Route("/{_locale}/customer/addresse")
 */
class AddressController extends AbstractController
{

    /**
     * @Route("/new", name="address_new", methods={"GET","POST"})
     */
    public function new(Request $request, AddressClient $addressClient): Response
    {
        $address_options = $addressClient->getAddress('fr-FR');
    
        $address = new Address();
        
        $form = $this->createForm(CountryType::class, $address, array('optionAddress' => $address_options));
        
        $form->handleRequest($request);

        if($this->isGranted('ROLE_CUSTOMER') ){
            $address->setUser($this->getUser()); 
            $address->setFamilyName($this->getUser()->getLastname());
            $address->setGivenName($this->getUser()->getFirstname());
        }

        if($this->isGranted('ROLE_ADMIN')){
            $address->setUser($this->getUser());
            $address->setFamilyName($this->getUser()->getLastname());
            $address->setGivenName($this->getUser()->getFirstname());
            $address->setAdditionalName(Delivery::DELIVERY_CC);
        }

        if ($countryCode = $request->request->get("adressMethod")) { 
            $country = $address_options['countryList'];
            $countryName = $country[$countryCode];
           
            $form2 = $this->createForm(AddressFRType::class, $address, array('optionAddress' => array($countryCode => $countryName)));
            $form2->handleRequest($request);

            return $this->render('admin/address/new_address.html.twig', [
                'address' => $address,
                'form' => $form2->createView(),
                'country' => array($countryCode =>  $country)
            ]);
            
        } else {
            $form2 = $this->createForm(AddressFRType::class, $address, array('optionAddress' => array('')));
            $form2->handleRequest($request);
        }
       
        if ($form2->isSubmitted()) {
            $address->setCountryCode($request->request->get('address_fr')['countryCode']);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($address);
            $entityManager->flush();

            return $this->redirectToRoute('address_index');
        } 

        return $this->render('admin/address/choice_country.html.twig', [
            'address' => $address,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/", name="address_index", methods={"GET"})
     */
    public function index(AddressRepository $addressRepository): Response
    {

        $addresses = $addressRepository->findByUser($this->getUser());

        return $this->render('admin/address/index.html.twig', [
            'addresses' => $addresses,
        ]);
    }

    /**
     * @Route("/{id}", name="address_show", methods={"GET"})
     */
    public function show(Address $address): Response
    {
        return $this->render('admin/address/show.html.twig', [
            'address' => $address,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="address_edit", methods={"GET","POST"})
     * @ParamConverter("address", class="App\Entity\Hermes\Address")
     */
    public function edit(Request $request, Address $address,AddressClient $addressClient): Response
    {
        if($this->isGranted('ROLE_CUSTOMER')){
            $address->setUser($this->getUser());
        }
        if($this->isGranted('ROLE_ADMIN')){
            $address->setUser($this->getUser());
            $address->setAdditionalName(Delivery::DELIVERY_CC);
        }

        $address_options = $addressClient->getAddress('fr-FR');
        $country = $address_options['countryList'];

        $countryCode = $address->getCountryCode();
        $countryName = $country[$countryCode];
       
        $form = $this->createForm(AddressFRType::class, $address, array('optionAddress' => array($countryCode => $countryName)));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('address_index');
        }

        return $this->render('admin/address/edit.html.twig', [
            'address' => $address,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="address_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Address $address): Response
    {
        if(!$this->isGranted('ROLE_CUSTOMER')){
            $this->redirectToRoute('address_index');
        }

        if ($this->isCsrfTokenValid('delete' . $address->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($address);
            $entityManager->flush();
        }

        return $this->redirectToRoute('address_index');
    }

    public function updateForm($menus,$listform,$listform_init)
    {

        $forms_init = explode(',', $listform_init);
        $forms = explode(',', $listform);
        $forms_add = array_diff($forms,$forms_init);// add
        $forms_delete =  array_diff($forms_init,$forms);//delete

        foreach ($forms_delete as $form){
            $sheet_form = $this->getDoctrine()->getManager()->getRepository(Sheet::class)->findOneBy(['active' => true, 'name' => $form]);
            if(!is_null($sheet_form)) {
                $this->getDoctrine()->getManager()->remove($sheet_form);
            }
        }

        foreach ($forms_add as $form){
            if (!array_key_exists(strtoupper($form), $menus)) {
                $sheet_form = $this->getDoctrine()->getManager()->getRepository(Sheet::class)->findOneBy(['active' => true, 'name' => $form]);
                // Création sheet si le formulaire n'existe pas
                if(is_null($sheet_form)){
                    $newSheet = new Sheet();
                    $newSheet->setCode($form);
                    $newSheet->setName($form);
                    $newSheet->setSlug($form);
                    $this->getDoctrine()->getManager()->persist($newSheet);
                }
            }
        }
        $this->getDoctrine()->getManager()->flush();
    }
}
