<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Form;

use UserBundle\Entity\User;
use AppBundle\Entity\Project;

/**
 * @Route("/project")
 */
class ProjectController extends Controller
{
    /**
     * @Route("/new", name="project_new")
     * @Template
     */
    public function newAction()
    {
        $this->denyAccessUnlessGranted('create', new Project());

        $entity = new Project();
        $form = $this->createForm('app_project', $entity, [
            'action' => $this->generateUrl('project_new'),
            'method' => 'POST'
        ]);

        if ($this->get('request')->getMethod() === 'POST') {
            $form->handleRequest($this->get('request'));

            if ($form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($entity);
                $entityManager->flush();

                return $this->redirect($this->generateUrl('project_show', ['user' => $entity->getId()]));
            }
        }
        return [
            'entity' => $entity,
            'form' => $form->createView()
        ];
    }
}