<?php

namespace App\Controller;

use App\Entity\Wishlist;
use App\Form\WishlistType;
use App\Repository\UserRepository;
use App\Repository\WishlistRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
/**
 * Class WishlistController
 * @package App\Controller
 *
 * * @Route("/wishlist", name="wishlist_")
 */
class WishlistController extends AbstractController
{
    /**
     * @Route("/index", name="index")
     */
    public function index(WishlistRepository $WishlistRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        return $this->render('wishlist/index.html.twig', [
            'wishlists' => $WishlistRepository->findAll(),
            'controller_name' => 'WishlistController',
        ]);
    }

    /**
     * @Route("/create", name="create")
     */
    public function createWishlist(Request $request)
    {

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');


        $wishlist = new Wishlist();
        $form = $this->createForm(WishlistType::class, $wishlist);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $wishlist->setUser($this->getUser());
            $em = $this->getDoctrine()->getManager();
            $em->persist($wishlist);
            $em->flush();

            $this->addFlash('message', 'Wishlist créé');
            return $this->redirectToRoute('wishlist_index');
        }

        return $this->render('wishlist/create.html.twig', [
            'wishlist' => $wishlist,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/edit", name="edit", methods={"GET","POST"})
     */
    public function edit(Request $request)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $wishlist = $this->getDoctrine()->getRepository("App\Entity\Wishlist");
        $form = $this->createForm(WishlistType::class, $wishlist);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $wishlist->setUser($this->getUser());
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('wishlist_index');
        }

        return $this->render('wishlist/edit.html.twig', [
            'wishlist' => $wishlist,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/all", name="all")
     */
    public function allUser(UserRepository $userRepository): Response
    {
        return $this->render('wishlist/all.html.twig', [
            'users' => $userRepository->findAll()
        ]);
    }
}
