<?php

namespace Louvre\TicketBundle\Controller;


use Louvre\TicketBundle\Entity\Booking;
use Louvre\TicketBundle\Entity\Ticket;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Louvre\TicketBundle\Form\BookingType;
use Symfony\Component\HttpFoundation\Request;
use Louvre\TicketBundle\Form\TicketType;
use Symfony\Component\HttpFoundation\Session\Session;


class TicketController extends Controller
{
    public function homeAction(Request $request)
    {
        $booking = new Booking();
        $formBooking = $this->createForm(BookingType::class, $booking);
        if ($request->isMethod('POST') && $formBooking->handleRequest($request)->isValid()) {
            $this->get('session')->set('booking', $booking);
            return $this->redirectToRoute('louvre_ticket_information'

            );
        }

        return $this->render('LouvreTicketBundle:Ticket:home.html.twig', array(
            'formBooking' => $formBooking->createView()
        ));
    }

    public function informationAction(Request $request)
    {
        $booking = $this->get('session')->get('booking');

        {
            $formTicket = $this->createForm(TicketType::class, $ticket);
        }



        return $this->render('LouvreTicketBundle:Ticket:information.html.twig', array(
            'formTicket' => $formTicket->createView(),
            'booking' => $booking
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
