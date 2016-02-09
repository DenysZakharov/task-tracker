<?php

namespace UserBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
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
     * @Security("is_granted('ROLE_USER')")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('UserBundle:User')->findAll();

        return array(
            'entities' => $entities,
            'entity' => new User()
        );
    }

    /**
     * @Route("/new", name="user_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        if (false === $this->get('security.authorization_checker')->isGranted('create', new User())) {
            throw new AccessDeniedException('Unauthorised access!');
        }

        $entity = new User();
        $form = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * @Route("/{user}", name="user_show")
     * @ParamConverter("user", class="UserBundle:User", options={"repository_method" = "find"})
     * @Method("GET")
     * @Template()
     */
    public function showAction(User $user = null)
    {
        if (false === $this->get('security.authorization_checker')->isGranted('view', $user)) {
            throw new AccessDeniedException('Unauthorised access!');
        }

        return array(
            'entity' => $user
        );
    }

    private function createCreateForm(user $entity)
    {
        $form = $this->createForm('user_create', $entity, array(
            'action' => $this->generateUrl('user_create'),
            'method' => 'POST'
        ));

        return $form;
    }

    /**
     * @Route("/", name="user_create")
     * @Method("POST")
     * @Template("UserBundle:User:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new User();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($entity);
            $entityManager->flush();

            return $this->redirect($this->generateUrl('user_show', array('user' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }
}
