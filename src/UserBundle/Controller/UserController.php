<?php

namespace UserBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Form;

use UserBundle\Entity\User;

/**
 * @Route("/user")
 */
class UserController extends Controller
{
    /**
     * @Route("/list", name="user_list")
     * @Method("GET")
     * @Security("is_granted('view', user)")
     * @Template()
     */
    public function indexAction()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('UserBundle:User')->findAll();

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $entities,
            $this->get('request')->query->getInt('page', 1),
            10
        );
        return [
            'entities' => $pagination,
            'entity' => $user
        ];
    }

    /**
     * @Route("/new", name="user_new")
     * @Template()
     */
    public function newAction()
    {
        $user = new User();
        $this->denyAccessUnlessGranted('create', $user);

        $form = $this->createForm('user_create', $user, [
            'action' => $this->generateUrl('user_new'),
            'method' => 'POST'
        ]);

        if ($this->get('request')->getMethod() === 'POST') {
            $form->handleRequest($this->get('request'));

            if ($form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();

                return $this->redirect($this->generateUrl('user_show', ['user' => $user->getId()]));
            }
        }
        return [
            'entity' => $user,
            'form' => $form->createView()
        ];
    }

    /**
     * @Route("/{user}", name="user_show")
     * @Method("GET")
     * @Security("is_granted('view', user)")
     * @ParamConverter("user", class="UserBundle:User")
     * @Template()
     */
    public function showAction(User $user)
    {
        return ['entity' => $user];
    }

    /**
     * @Route("/{user}/edit", name="user_edit")
     * @Security("is_granted('edit', user)")
     * @Template()
     */
    public function editAction(User $user)
    {
        $editForm = $this->createForm('user_create', $user, [
            'action' => $this->generateUrl('user_edit', ['user' => $user->getId()]),
            'method' => 'POST'
        ]);
        if ($this->get('request')->getMethod() === 'POST') {
            $entityManager = $this->getDoctrine()->getManager();
            $editForm->handleRequest($this->get('request'));
            if ($editForm->isValid()) {
                $entityManager->flush();

                return $this->redirect($this->generateUrl('user_show', ['user' => $user->getId()]));
            }
        }
        return [
            'entity' => $user,
            'edit_form' => $editForm->createView()
        ];
    }
}
