<?php

namespace Louvre\TicketBundle\Controller;


use Louvre\TicketBundle\Entity\TicketsOrder;
use Louvre\TicketBundle\Entity\Ticket;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Louvre\TicketBundle\Form\TicketsOrderType;
use Symfony\Component\HttpFoundation\Request;
use Louvre\TicketBundle\Form\TicketType;






class TicketController extends Controller
{
    public function homeAction()
    {


        return $this->render('LouvreTicketBundle:Ticket:home.html.twig');
    }

    public function informationAction(Request $request)
    {
        $ticketsOrder = new TicketsOrder();
        $formTicketsOrder = $this->createForm(TicketsOrderType::class, $ticketsOrder);
        $ticket = new Ticket();
        $formTicket = $this->createForm(TicketType::class, $ticket);

        if($request->isMethod('POST') && $formTicketsOrder->handleRequest($request)->isValid())
        {
                $em = $this->getDoctrine()->getManager();
                $em->persist($ticketsOrder);
                $em->flush();
                $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');
                return $this->redirectToRoute('louvre_ticket_stripe', array ());
        }

        return $this->render('LouvreTicketBundle:Ticket:information.html.twig', array(
            'formTicketsOrder' => $formTicketsOrder->createView(),
            'formTicket' => $formTicket->createView()
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
