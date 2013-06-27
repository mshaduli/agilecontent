<?php

namespace TNE\OperatorBundle\Controller;

use Faker\Provider\DateTime;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

use TNE\OperatorBundle\Entity\Customer;
use TNE\OperatorBundle\Form\CustomerType;
use TNE\OperatorBundle\Entity\Booking as Booking;

/**
 * Customer controller.
 *
 */
class CustomerController extends Controller
{
    /**
     * Lists all Customer entities.
     *
     */
    public function indexAction()
    {
//        $em = $this->getDoctrine()->getManager();
//
//        $entities = $em->getRepository('TNEOperatorBundle:Customer')->findAll();
//
//        return $this->render('TNEOperatorBundle:Customer:index.html.twig', array(
//            'entities' => $entities,
//        ));

        if( $this->getRequest()->getSession()->get('booking_data')){

            $entity = new Customer();
            $form   = $this->createForm(new CustomerType(), $entity);

            return $this->render('TNEOperatorBundle:Customer:new.html.twig', array(
                'entity' => $entity,
                'form'   => $form->createView(),
            ));
        }
    }

    /**
     * Finds and displays a Customer entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('TNEOperatorBundle:Customer')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Customer entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('TNEOperatorBundle:Customer:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to create a new Customer entity.
     *
     */
    public function newAction()
    {
        $entity = new Customer();
        $form   = $this->createForm(new CustomerType(), $entity);

        return $this->render('TNEOperatorBundle:Customer:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a new Customer entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity  = new Customer();
        $form = $this->createForm(new CustomerType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $host = $this->getRequest()->getHost();
            $em =  $this->getDoctrine()->getManager();
            $site = $em->getRepository('ApplicationSonataPageBundle:Site')->findOneBy(array('host' => $host));

            $session = $this->getRequest()->getSession();
            $booking_data = $session->get('booking_data');
            foreach($booking_data as $booked_room)
            {
                $room = $em->getRepository('TNEOperatorBundle:AccommodationRoom')->find($booked_room['room_id']);
                $booking = new Booking();
                $booking->setAdults("2");
                $booking->setChildren("2");
                $booking->setStart(new \DateTime(strtotime($booked_room['start_date'])));
                $booking->setEnd(new \DateTime(strtotime($booked_room['end_date'])));
                $booking->setRoom($room);
                $booking->setCustomer($entity);
                $booking->setSite($site);
                $booking->setStatus("Booked");
                $em->persist($booking);
            }
            $em->flush();
            $session->set('booking_id', $entity->getId());
            return $this->redirect($this->generateUrl('booking_order_confirmation'));
        }

        return $this->render('TNEOperatorBundle:Customer:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Customer entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('TNEOperatorBundle:Customer')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Customer entity.');
        }

        $editForm = $this->createForm(new CustomerType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('TNEOperatorBundle:Customer:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing Customer entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('TNEOperatorBundle:Customer')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Customer entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new CustomerType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('booking_edit', array('id' => $id)));
        }

        return $this->render('TNEOperatorBundle:Customer:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Customer entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('TNEOperatorBundle:Customer')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Customer entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('booking'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }

    public function addToCartAction()
    {
//        $this->getRequest()->getSession()->remove('booking_data');
        $booking_data = array();
        $booking_data_refined = array();
        if($this->getRequest()->isXmlHttpRequest())
        {

            $session = $this->getRequest()->getSession();

            $_data['room_id'] = $this->getRequest()->get('room_id');
            $_data['start_date'] = $this->getRequest()->get('start_date');
            $_data['end_date'] = $this->getRequest()->get('end_date');

            if(is_null($session->get('booking_data')))
            {

                $booking_data[0] = $_data;
            }
            else
            {
                $booking_data = $session->get('booking_data');
                array_push($booking_data,$_data);
            }
            foreach($booking_data as $booking)
            {
                $booking_data_refined [$booking['room_id']]= $booking;


            }
            $session->set('booking_data',$booking_data_refined);
        }
//        $session->remove('booking_data');
        return new JsonResponse(array("status"=> "Added to cart successfully","count"=>count($booking_data_refined)));
    }


    public function cartAction()
    {

       $cart = $this->getRequest()->getSession()->get('booking_data');
        $total = 0;
        $rooms = array();
        $em = $this->getDoctrine()->getManager();
        foreach ($cart as $room)
        {

            $roomObj = $em->getRepository('TNEOperatorBundle:AccommodationRoom')->find($room['room_id']);

            $qry = "SELECT sum(rate) as rate FROM  `AccommodationRoomCalendar` ARC WHERE accommodation_room_id =".$room['room_id']."
                    AND `date` BETWEEN '".date('Y-m-d', strtotime(str_replace('/','-',$room['start_date'])))."'
                    AND '".date('Y-m-d', strtotime(str_replace('/','-',$room['end_date'])))."'";
            $roomStmt = $em->getConnection()->prepare($qry);
            $roomStmt->execute();
            $rate= $roomStmt->fetchAll();
            $room_rate = $rate[0]['rate'] == ""?$roomObj->getAccommodation()->getAtdwRateFrom():$rate[0]['rate'];

            $room_cart [$room['room_id']]=  array('start_date' => $room['start_date'], 'end_date' => $room['end_date'],'rate' => $room_rate);
            $rooms[]=$roomObj;
            $total +=$room_rate;

        }

        return $this->render('TNEOperatorBundle:Customer:cart.html.twig', array('rooms' => $rooms,'cart_data'=> $room_cart,'total_rate' => $total));
    }

    public function removeFromCartAction()
    {
        $session = $this->getRequest()->getSession();
        $booking_data = $session->get('booking_data');
        unset($booking_data[$this->getRequest()->get('id')]);
        $session->set('booking_data',$booking_data);
        return $this->redirect($this->generateUrl('booking_cart'));

    }

    public function confirmationAction()
    {

        //if($this->getRequest()->getSession()->get('booking_id'))
        {


        //$this->getRequest()->getSession()->remove('booking_data');
        $order = $this->getDoctrine()->getManager()->getRepository('TNEOperatorBundle:Customer')->find($this->getRequest()->getSession()->get('booking_id'));
        //$this->getRequest()->getSession()->remove('booking_id');
        return $this->render('TNEOperatorBundle:Customer:confirmation.html.twig', array('order' => $order));
        }
        //else
            
            {
            //return $this->redirect('/');
        }

    }
}
