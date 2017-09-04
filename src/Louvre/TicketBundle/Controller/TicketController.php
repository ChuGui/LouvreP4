<?php

namespace Louvre\TicketBundle\Controller;


use Louvre\TicketBundle\Entity\TicketsOrder;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Louvre\TicketBundle\Form\TicketsOrderType;
use Symfony\Component\HttpFoundation\Request;






class TicketController extends Controller
{
    public function homeAction()
    {


        return $this->render('LouvreTicketBundle:Ticket:home.html.twig');
    }

    public function informationAction(Request $request)
    {
        $ticketsOrder = new TicketsOrder();
        $form = $this->createForm(TicketsOrderType::class, $ticketsOrder);

        if($request->isMethod('POST') && $form->handleRequest($request)->isValid())
        {
                $em = $this->getDoctrine()->getManager();
                $em->persist($ticketsOrder);
                $em->flush();
                $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');
                return $this->redirectToRoute('louvre_ticket_stripe', array ());
        }

        return $this->render('LouvreTicketBundle:Ticket:information.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function stripeAction()
    {
        return $this->render('LouvreTicketBundle:Ticket:stripe.html.twig');
    }

    public function recapAction()
    {
        return $this->render('LouvreTicketBundle:Ticket:recap.html.twig');
    }
}
