<?php

namespace Louvre\TicketBundle\Controller;


use Louvre\TicketBundle\Entity\Booking;
use Louvre\TicketBundle\Entity\Ticket;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Louvre\TicketBundle\Form\BookingType;
use Symfony\Component\HttpFoundation\Request;
use Louvre\TicketBundle\Form\TicketType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;


class TicketController extends Controller
{
    public function homeAction(Request $request)
    {
        $booking = new Booking();

        $booking->setUrl('test@test.com');
        $booking->setTotalPrice(20);
        $booking->setBookingCode('65fq4sd65f4q6');

        $ticket = new Ticket();
        $ticket->setPrice(5);


        $booking->addTicket($ticket);
        $formBooking = $this->createForm(BookingType::class, $booking);
        $formBooking->handleRequest($request);
        if ($formBooking->isSubmitted() && $formBooking->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($booking);


            return new Response(var_dump($booking));
            return new Response(var_dump($booking->getTickets()));
        }

        return $this->render('LouvreTicketBundle:Ticket:home.html.twig', array(
            'formBooking' => $formBooking->createView()
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
