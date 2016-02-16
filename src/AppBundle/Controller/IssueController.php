<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Form;

use UserBundle\Entity\User;
use AppBundle\Entity\Project;
use AppBundle\Entity\Issue;
use AppBundle\Entity\Mapping\EnumStatusIssue;
use AppBundle\Entity\Mapping\EnumTypeIssue;

/**
 * @Route("/issue")
 */
class IssueController extends Controller
{
    /**
     * @Route("/", name="issue")
     * @Method("GET")
     * @Security("is_granted('view', user)")
     * @Template()
     */
    public function indexAction()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $issue = $user->getIssues();
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
     * @Security("is_granted('view', user)")
     * @Template("AppBundle:Issue:index.html.twig")
     */
    public function listAction()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $issue = $user->getIssues();
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('AppBundle:Issue')->findAll();

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $entities,
            $this->get('request')->query->getInt('page', 1),
            10
        );

        return [
            'entities' => $pagination,
            'entity' => $issue
        ];
    }

    /**
     * @Route("/{issue}/edit", name="issue_edit")
     * @Security("is_granted('edit', issue)")
     * @ParamConverter("issue", class="AppBundle:Issue", options={"repository_method" = "findOneByCode"})
     * @Template()
     */
    public function editAction(Issue $issue)
    {
        $editForm = $this->createForm('app_issue', $issue, [
            'action' => $this->generateUrl('issue_edit', ['issue' => $issue->getCode()]),
            'method' => 'POST'
        ]);
        if ($this->get('request')->getMethod() === 'POST') {
            $entityManager = $this->getDoctrine()->getManager();
            $editForm->handleRequest($this->get('request'));
            if ($editForm->isValid()) {
                if (!$issue->getCollaborators()->contains($issue->getAssignee())) {
                    $issue->addCollaborator($issue->getAssignee());
                }
                $entityManager->flush();

                return $this->redirect($this->generateUrl('issue_show', ['issue' => $issue->getCode()]));
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
                $user = $this->getUser();
                $issue->setReporter($user);
                $issue->addCollaborator($user);
                $issue->setStatus(EnumStatusIssue::OPEN);
                if (!$issue->getCollaborators()->contains($issue->getAssignee())) {
                    $issue->addCollaborator($issue->getAssignee());
                }
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($issue);
                $entityManager->flush();

                return $this->redirect($this->generateUrl('issue_show', ['issue' => $issue->getCode()]));
            }
        }
        return [
            'entity' => $issue,
            'form' => $form->createView()
        ];
    }

    /**
     * @Route("/{issue}/newsubtask", name="issue_new_subtask")
     * @Security("is_granted('add_sub_task', issue)")
     * @ParamConverter("issue", class="AppBundle:Issue", options={"repository_method" = "findOneByCode"})
     * @Template("AppBundle:Issue:new.html.twig")
     */
    public function subtaskAction(Issue $issue)
    {
        $newIssue = new Issue();
        $newIssue->setType(EnumTypeIssue::SUBTASK);
        $newIssue->setParent($issue);
        $form = $this->createForm('app_issue', $newIssue, [
            'action' => $this->generateUrl('issue_new_subtask', ['issue' => $issue->getCode()]),
            'method' => 'POST'
        ]);

        if ($this->get('request')->getMethod() === 'POST') {
            $form->handleRequest($this->get('request'));
            if ($form->isValid()) {
                $user = $this->getUser();
                $newIssue->setReporter($user);
                $newIssue->addCollaborator($user);
                $newIssue->setStatus(EnumStatusIssue::OPEN);
                if (!$newIssue->getCollaborators()->contains($newIssue->getAssignee())) {
                    $newIssue->addCollaborator($newIssue->getAssignee());
                }
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($newIssue);
                $entityManager->flush();

                return $this->redirect($this->generateUrl('issue_show', ['issue' => $newIssue->getCode()]));
            }
        }
        return [
            'entity' => $newIssue,
            'form' => $form->createView()
        ];
    }

    /**
     * @Route("/{issue}", name="issue_show")
     * @Security("is_granted('view', issue)")
     * @ParamConverter("issue", class="AppBundle:Issue", options={"repository_method" = "findOneByCode"})
     * @Method("GET")
     * @Template()
     */
    public function showAction(Issue $issue)
    {
        return ['entity' => $issue];
    }
}