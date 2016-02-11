<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

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
     * @Route("/list", name="project_list")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $project = new Project();
        $this->denyAccessUnlessGranted('view', $project);
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $entities = $em->getRepository('AppBundle:Project')->findByUsers($user);

        return [
            'entities' => $entities,
            'entity' => $project
        ];
    }

    /**
     * @Route("/{project}/edit", name="project_edit")
     * @Template()
     */
    public function editAction(Project $project)
    {
        $this->denyAccessUnlessGranted('edit', $project);
        $editForm = $this->createForm('app_project', $project, [
            'action' => $this->generateUrl('project_edit', ['project' => $project->getId()]),
            'method' => 'POST'
        ]);
        if ($this->get('request')->getMethod() === 'POST') {
            $entityManager = $this->getDoctrine()->getManager();
            $editForm->handleRequest($this->get('request'));
            if ($editForm->isValid()) {
                $entityManager->flush();

                return $this->redirect($this->generateUrl('project_show', ['project' => $project->getId()]));
            }
        }
        return [
            'entity' => $project,
            'edit_form' => $editForm->createView()
        ];
    }

    /**
     * @Route("/new", name="project_new")
     * @Template
     */
    public function newAction()
    {
        $project = new Project();
        $this->denyAccessUnlessGranted('create', $project);
        $form = $this->createForm('app_project', $project, [
            'action' => $this->generateUrl('project_new'),
            'method' => 'POST'
        ]);

        if ($this->get('request')->getMethod() === 'POST') {
            $form->handleRequest($this->get('request'));

            if ($form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($project);
                $entityManager->flush();

                return $this->redirect($this->generateUrl('project_show', ['project' => $project->getId()]));
            }
        }
        return [
            'entity' => $project,
            'form' => $form->createView()
        ];
    }

    /**
     * @Route("/{project}", name="project_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction(Project $project)
    {
        $this->denyAccessUnlessGranted('view', $project);
        return ['entity' => $project];
    }
}