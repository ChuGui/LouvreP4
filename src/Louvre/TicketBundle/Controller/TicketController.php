<?php

namespace Louvre\TicketBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TicketController extends Controller
{
    public function homeAction()
    {
        return $this->render('LouvreTicketBundle:Ticket:home.html.twig');
    }

    public function informationAction()
    {
        return $this->render('LouvreTicketBundle:Ticket:information.html.twig');
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
