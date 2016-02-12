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
use AppBundle\Entity\Issue;

/**
 * @Route("/issue")
 */
class IssueController extends Controller
{
    /**
     * @Route("/", name="issue")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $issue = new Issue();
        $this->denyAccessUnlessGranted('view', $issue);
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $entities = $em->getRepository('AppBundle:Issue')->findByUser($user);

        return [
            'entities' => $entities,
            'entity' => $issue
        ];
    }

    /**
     * @Route("/list", name="issue_list")
     * @Method("GET")
     * @Template("AppBundle:Issue:index.html.twig")
     */
    public function listAction()
    {
        $issue = new Issue();
        $this->denyAccessUnlessGranted('view', $issue);
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('AppBundle:Issue')->findAll();

        return [
            'entities' => $entities,
            'entity' => $issue
        ];
    }

    /**
     * @Route("/{issue}/edit", name="issue_edit")
     * @Template()
     */
    public function editAction(Issue $issue)
    {
        $this->denyAccessUnlessGranted('edit', $issue);
        $editForm = $this->createForm('app_issue', $issue, [
            'action' => $this->generateUrl('issue_edit', ['issue' => $issue->getId()]),
            'method' => 'POST'
        ]);
        if ($this->get('request')->getMethod() === 'POST') {
            $entityManager = $this->getDoctrine()->getManager();
            $editForm->handleRequest($this->get('request'));
            if ($editForm->isValid()) {
                $entityManager->flush();

                return $this->redirect($this->generateUrl('issue_show', ['issue' => $issue->getId()]));
            }
        }
        return [
            'entity' => $issue,
            'edit_form' => $editForm->createView()
        ];
    }

    /**
     * @Route("/new", name="issue_new")
     * @Template
     */
    public function newAction()
    {
        $issue = new Issue();
        $this->denyAccessUnlessGranted('create', $issue);
        $form = $this->createForm('app_issue', $issue, [
            'action' => $this->generateUrl('issue_new'),
            'method' => 'POST'
        ]);

        if ($this->get('request')->getMethod() === 'POST') {
            $form->handleRequest($this->get('request'));
            if ($form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($issue);
                $entityManager->flush();

                return $this->redirect($this->generateUrl('issue_show', ['issue' => $issue->getId()]));
            }
        }
        return [
            'entity' => $issue,
            'form' => $form->createView()
        ];
    }

    /**
     * @Route("/{issue}", name="issue_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction(Issue $issue)
    {
        $this->denyAccessUnlessGranted('view', $issue);
        return ['entity' => $issue];
    }
}