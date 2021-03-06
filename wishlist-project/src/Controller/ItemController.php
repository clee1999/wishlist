<?php

namespace App\Controller;

use App\Entity\Item;
use App\Form\ItemType;
use App\Repository\ItemRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ItemController extends AbstractController
    /**
     * Class ItemController
     * @package App\Controller
     *
     * * @Route("/item", name="item_")
     */
{
    /**
     * @Route("/index", name="index")
     */
    public function index(ItemRepository $itemRepository): Response
    {
        return $this->render('item/index.html.twig', [
            'items' => $itemRepository->findAll(),
            'controller_name' => 'ItemController',
        ]);
    }

    /**
     * @Route("/add", name="add")
     */
    public function addItem(Request $request)
    {

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');


        $item = new Item();
        $form = $this->createForm(ItemType::class, $item);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $item = $form->getData();
//            $item->setWishlist($this->getDoctrine()->getRepository()->findAll());
            $em = $this->getDoctrine()->getManager();
            $em->persist($item);
            $em->flush();

            $this->addFlash('message', 'item créé');
            return $this->redirectToRoute('wishlist_index');
        }

        return $this->render('item/add.html.twig', [
            'item' => $item,
            'form' => $form->createView(),
        ]);
    }
}
