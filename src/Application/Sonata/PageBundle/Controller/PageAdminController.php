<?php

/*
 * This file is part of the Sonata package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Application\Sonata\PageBundle\Controller;

use Sonata\AdminBundle\Controller\CRUDController as Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Page Admin Controller
 *
 * @author Thomas Rabaix <thomas.rabaix@sonata-project.org>
 */
class PageAdminController extends Controller
{
    
    /**
     * Provide current user's site id to Admin class
     * @return type
     * @throws AccessDeniedException
     */
    public function listAction()
    {
        if (false === $this->admin->isGranted('LIST')) {
            throw new AccessDeniedException();
        }
        
        $userSite = $this->get('security.context')->getToken()->getUser()->getSite();
        if($userSite && !$userSite->getIsDefault())
        {
            $this->admin->setUserSite($userSite->getId());
        }

        $datagrid = $this->admin->getDatagrid();
        $formView = $datagrid->getForm()->createView();

        // set the theme for the current Admin Form
        $this->get('twig')->getExtension('form')->renderer->setTheme($formView, $this->admin->getFilterTheme());
      
        return $this->render($this->admin->getTemplate('list'), array(
            'action'   => 'list',
            'form'     => $formView,
            'datagrid' => $datagrid
        ));
    }

        
    
    /**
     * @param mixed $query
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     */
    public function batchActionSnapshot($query)
    {
        if (!$this->get('security.context')->isGranted('ROLE_SONATA_PAGE_ADMIN_PAGE_EDIT')) {
            throw new AccessDeniedException();
        }

        foreach ($query->execute() as $page) {
            $this->get('sonata.notification.backend')
                ->createAndPublish('sonata.page.create_snapshot', array(
                    'pageId' => $page->getId(),
                ));
        }

        return new RedirectResponse($this->admin->generateUrl('list', $this->admin->getFilterParameters()));
    }

    /**
     * Limit site selection to regional admin and take site admins directly to create screen
     * 
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     */
    public function createAction()
    {
        if (false === $this->admin->isGranted('CREATE')) {
            throw new AccessDeniedException();
        }

        if ($this->getRequest()->getMethod() == 'GET' && !$this->getRequest()->get('siteId')) {
            
            $userSite = $this->get('security.context')->getToken()->getUser()->getSite();
            
            if($userSite && !$userSite->getIsDefault())
            {
                //var_dump($this->admin->generateUrl('create', array('siteId' => $this->get('security.context')->getToken()->getUser()->getSite()->getId())));
                return $this->redirect($this->admin->generateUrl('create', array('siteId' => $this->get('security.context')->getToken()->getUser()->getSite()->getId())));
            }    
            else 
            {
                
               $sites = $this->get('sonata.page.manager.site')->findBy();

                if (count($sites) == 1) {
                    return $this->redirect($this->admin->generateUrl('create', array('siteId' => $sites[0]->getId())));
                }

                try {
                    $current = $this->get('sonata.page.site.selector')->retrieve();
                } catch (\RuntimeException $e) {
                    $current = false;
                }

                return $this->render('SonataPageBundle:PageAdmin:select_site.html.twig', array(
                    'sites'   => $sites,
                    'current' => $current,
                ));
            }
        }
        
        // the key used to lookup the template
        $templateKey = 'edit';

        if (false === $this->admin->isGranted('CREATE')) {
            throw new AccessDeniedException();
        }

        $object = $this->admin->getNewInstance();

        $this->admin->setSubject($object);

        /** @var $form \Symfony\Component\Form\Form */
        $form = $this->admin->getForm();
        $form->setData($object);

        if ($this->get('request')->getMethod() == 'POST') {
            $form->bind($this->get('request'));

            $isFormValid = $form->isValid();

            // persist if the form was valid and if in preview mode the preview was approved
            if ($isFormValid && (!$this->isInPreviewMode() || $this->isPreviewApproved())) {
                $this->admin->create($object);

                if ($this->isXmlHttpRequest()) {
                    return $this->renderJson(array(
                        'result' => 'ok',
                        'objectId' => $this->admin->getNormalizedIdentifier($object)
                    ));
                }

                $this->get('session')->setFlash('sonata_flash_success','flash_create_success');
                $this->createPageSnapshot($object);
                // redirect to edit mode
                return $this->redirectTo($object);
            }

            // show an error message if the form failed validation
            if (!$isFormValid) {
                if (!$this->isXmlHttpRequest()) {
                    $this->get('session')->setFlash('sonata_flash_error', 'flash_create_error');
                }
            } elseif ($this->isPreviewRequested()) {
                // pick the preview template if the form was valid and preview was requested
                $templateKey = 'preview';
            }
        }

        $view = $form->createView();

        // set the theme for the current Admin Form
        $this->get('twig')->getExtension('form')->renderer->setTheme($view, $this->admin->getFormTheme());

        return $this->render($this->admin->getTemplate($templateKey), array(
            'action' => 'create',
            'form'   => $view,
            'object' => $object,
        ));
    }
    
    public function createPageSnapshot($page){
        $pageManager = $this->get('sonata.page.manager.page');
        $snapshotManager = $this->get('sonata.page.manager.snapshot');
        $page->setEdited(false);
        $snapshot = $snapshotManager->create($page);
        $snapshotManager->save($snapshot);
        $pageManager->save($page);
        $snapshotManager->enableSnapshots(array($snapshot));
    }
}