<?php

namespace UserBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

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
     * @Template()
     */
    public function indexAction()
    {
        $this->denyAccessUnlessGranted('view', new User());
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('UserBundle:User')->findAll();

        return [
            'entities' => $entities,
            'entity' => new User()
        ];
    }

    /**
     * @Route("/new", name="user_new")
     * @Template()
     */
    public function newAction()
    {
        $this->denyAccessUnlessGranted('create', new User());

        $entity = new User();
        $form = $this->createForm('user_create', $entity, [
            'action' => $this->generateUrl('user_new'),
            'method' => 'POST'
        ]);

        if ($this->get('request')->getMethod() === 'POST') {
            $form->handleRequest($this->get('request'));

            if ($form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($entity);
                $entityManager->flush();

                return $this->redirect($this->generateUrl('user_show', ['user' => $entity->getId()]));
            }
        }
        return [
            'entity' => $entity,
            'form' => $form->createView()
        ];
    }

    /**
     * @Route("/{user}", name="user_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction(User $user = null)
    {
        $this->denyAccessUnlessGranted('view', new User());
        if ($user === null) {
            throw $this->createNotFoundException('User does not exist');
        }
        return ['entity' => $user];
    }

    /**
     * @Route("/{user}/edit", name="user_edit")
     * @Template()
     */
    public function editAction(User $user)
    {
        $this->denyAccessUnlessGranted('view', new User());
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
