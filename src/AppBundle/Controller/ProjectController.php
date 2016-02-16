<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
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
     * @Route("/", name="project")
     * @Method("GET")
     * @Security("is_granted('view', user)")
     * @Template()
     */
    public function indexAction()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $project = $user->getProject();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $entities = $em->getRepository('AppBundle:Project')->findByUsers($user);

        return [
            'entities' => $entities,
            'entity' => $project
        ];
    }

    /**
     * @Route("/list", name="project_list")
     * @Method("GET")
     * @Security("is_granted('view', user)")
     * @Template("AppBundle:Project:index.html.twig")
     */
    public function listAction()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $project = $user->getProject();
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('AppBundle:Project')->findAll();

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $entities,
            $this->get('request')->query->getInt('page', 1),
            10
        );

        return [
            'entities' => $pagination,
            'entity' => $project,
        ];
    }

    /**
     * @Route("/{project}/edit", name="project_edit")
     * @Security("is_granted('edit', project)")
     * @Template()
     */
    public function editAction(Project $project)
    {
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
     * @Security("is_granted('view', project)")
     * @Method("GET")
     * @Template()
     */
    public function showAction(Project $project)
    {
        return ['entity' => $project];
    }
}