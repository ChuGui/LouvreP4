<?php

namespace Louvre\TicketBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * TicketsOrder
 *
 * @ORM\Table(name="tickets_order")
 * @ORM\Entity(repositoryClass="Louvre\TicketBundle\Repository\TicketsOrderRepository")
 */
class TicketsOrder
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * @var int
     *
     * @ORM\Column(name="totalPrice", type="integer")
     */
    private $totalPrice;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="bookingDate", type="datetime")
     * @Assert\DateTime()
     */
    private $bookingDate;

    /**
     * @ORM\OneToMany(targetEntity="Louvre\TicketBundle\Entity\Ticket", mappedBy="ticketsOrder")
     * @ORM\JoinColumn(nullable=false)
     */
    private $tickets;

    /**
     * @var string
     *
     * @ORM\Column(name="codeOrder", type="string", length=255)
     */
    private $codeOrder;

    /**
     * @var bool
     *
     * @ORM\Column(name="paid", type="boolean")
     */
    private $paid;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return TicketsOrder
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set totalPrice
     *
     * @param integer $totalPrice
     *
     * @return TicketsOrder
     */
    public function setTotalPrice($totalPrice)
    {
        $this->totalPrice = $totalPrice;

        return $this;
    }

    /**
     * Get totalPrice
     *
     * @return int
     */
    public function getTotalPrice()
    {
        return $this->totalPrice;
    }



    /**
     * Set bookingDate
     *
     * @param \DateTime $bookingDate
     *
     * @return TicketsOrder
     */
    public function setBookingDate(\DateTime $bookingDate)
    {
        $this->bookingDate = $bookingDate;

        return $this;
    }

    /**
     * Get bookingDate
     *
     * @return \DateTime
     */
    public function getBookingDate()
    {
        return $this->bookingDate;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->bookingDate = new \Datetime();
        $this->tickets = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add ticket
     *
     * @param \Louvre\TicketBundle\Entity\Ticket $ticket
     *
     * @return TicketsOrder
     */
    public function addTicket(\Louvre\TicketBundle\Entity\Ticket $ticket)
    {
        $this->tickets[] = $ticket;

        return $this;
    }

    /**
     * Remove ticket
     *
     * @param \Louvre\TicketBundle\Entity\Ticket $ticket
     */
    public function removeTicket(\Louvre\TicketBundle\Entity\Ticket $ticket)
    {
        $this->tickets->removeElement($ticket);
    }

    /**
     * Get tickets
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTickets()
    {
        return $this->tickets;
    }

    /**
     * Set codeOrder
     *
     * @param string $codeOrder
     *
     * @return TicketsOrder
     */
    public function setCodeOrder($codeOrder)
    {
        $this->codeOrder = $codeOrder;

        return $this;
    }

    /**
     * Get codeOrder
     *
     * @return string
     */
    public function getCodeOrder()
    {
        return $this->codeOrder;
    }
}
