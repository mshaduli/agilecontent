<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace TNE\OperatorBundle\Controller;


use Sonata\AdminBundle\Controller\CRUDController as Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class AccommodationAdminController extends Controller
{
     /**
     * return the Response object associated to the edit action
     *
     *
     * @param mixed $id
     *
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return Response
     */
    public function editAction($id = null)
    {
        // the key used to lookup the template
        $templateKey = 'edit';

        $id = $this->get('request')->get($this->admin->getIdParameter());

        $object = $this->admin->getObject($id);

        if (!$object) {
            throw new NotFoundHttpException(sprintf('unable to find the object with id : %s', $id));
        }

        if (false === $this->admin->isGranted('EDIT', $object)) {
            throw new AccessDeniedException();
        }

        $this->admin->setSubject($object);

        /** @var $form \Symfony\Component\Form\Form */
        $form = $this->admin->getForm();
        $form->setData($object);

        if ($this->get('request')->getMethod() == 'POST') {
            $form->bind($this->get('request'));

            $isFormValid = $form->isValid();

            // persist if the form was valid and if in preview mode the preview was approved
            if ($isFormValid && (!$this->isInPreviewMode() || $this->isPreviewApproved())) {
                $this->admin->update($object);
                
                /**
                 * Updating Accomedation Room Calendar
                 */
                $em = $this->getDoctrine()->getManager();
                $room_calendars = $this->get('request')->request->get('room_calendar');
//                d($room_calendars);exit;
                if(count($room_calendars))
                {
//                    d($room_calendars);exit;
                 foreach($room_calendars as $room_id => $calendars)
                 {
                     $month_calcu = array_keys($calendars);

                     $month = date('m',strtotime($month_calcu[0]));
                     $room = $em->createQuery('SELECT ar FROM TNEOperatorBundle:AccommodationRoom ar WHERE ar.id = :id')->setParameter('id', $room_id)->getSingleResult();


                     $q = $em->getConnection()->prepare('DELETE FROM AccommodationRoomCalendar WHERE MONTH(`date`) = '.$month.' AND accommodation_room_id='.$room_id);
                     $numDeleted = $q->execute();

                     foreach($calendars as $date => $calendar)
                     {

                         $room_calendar_obj = new \TNE\OperatorBundle\Entity\AccommodationRoomCalendar();
                         $room_calendar_obj->setDate(new \DateTime($calendar['date']));
                         $room_calendar_obj->setAvailable(isset($calendar['available']));
                         $room_calendar_obj->setRate((isset($calendar['rate'])?$calendar['rate']:0));
                         $room_calendar_obj->setRoom($room);
                         $em->persist($room_calendar_obj);
                     }


                 }
                }
                $em->flush();
                
                
                $this->get('session')->setFlash('sonata_flash_success', 'flash_edit_success');

                if ($this->isXmlHttpRequest()) {
                    return $this->renderJson(array(
                        'result'    => 'ok',
                        'objectId'  => $this->admin->getNormalizedIdentifier($object)
                    ));
                }

                // redirect to edit mode
                return $this->redirectTo($object);
            }

            // show an error message if the form failed validation
            if (!$isFormValid) {
                if (!$this->isXmlHttpRequest()) {
                    $this->get('session')->setFlash('sonata_flash_error', 'flash_edit_error');
                }
            } elseif ($this->isPreviewRequested()) {
                // enable the preview template if the form was valid and preview was requested
                $templateKey = 'preview';
            }
        }

        $view = $form->createView();

        // set the theme for the current Admin Form
        $this->get('twig')->getExtension('form')->renderer->setTheme($view, $this->admin->getFormTheme());

        return $this->render($this->admin->getTemplate($templateKey), array(
            'action' => 'edit',
            'form'   => $view,
            'object' => $object,
        ));
    }
}