<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use FOS\UserBundle\Model\User as BaseUser;


/**
 * @ORM\Entity()
 * @ORM\Table(name="users")
 */
class User extends BaseUser
{
    const ROLE_ADMIN = 'ROLE_ADMIN';
    const ROLE_MANAGER = 'ROLE_MANAGER';
    const ROLE_OPERATOR = 'ROLE_OPERATOR';

    /**
     * @var integer $id
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string $fullName
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $fullName;

    /**
     * @var ArrayCollection Project[]
     *
     * @ORM\ManyToMany(targetEntity="\AppBundle\Entity\Project", mappedBy="users")
     **/
    protected $project;

    /**
     * @var ArrayCollection Issue[]
     *
     * @ORM\ManyToMany(targetEntity="\AppBundle\Entity\Issue", mappedBy="collaborators")
     */
    protected $issues;

    /**
     * @var string
     *
     * @ORM\Column(type="enum_timezone_user")
     */
    protected $timezone;

    public function __construct()
    {
        parent::__construct();
        $this->project = new ArrayCollection();
        $this->issues = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set fullName
     *
     * @param string $fullName
     *
     * @return User
     */
    public function setFullName($fullName)
    {
        $this->fullName = $fullName;

        return $this;
    }

    /**
     * Get fullName
     *
     * @return string
     */
    public function getFullName()
    {
        return $this->fullName;
    }

    /**
     * Add project
     *
     * @param \AppBundle\Entity\Project $project
     * @return User
     */
    public function addProject(\AppBundle\Entity\Project $project)
    {
        $this->project[] = $project;

        return $this;
    }

    /**
     * Remove project
     *
     * @param \AppBundle\Entity\Project $project
     */
    public function removeProject(\AppBundle\Entity\Project $project)
    {
        $this->project->removeElement($project);
    }

    /**
     * Get project
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * Add issues
     *
     * @param \AppBundle\Entity\Issue $issues
     * @return User
     */
    public function addIssue(\AppBundle\Entity\Issue $issues)
    {
        $this->issues[] = $issues;

        return $this;
    }

    /**
     * Remove issues
     *
     * @param \AppBundle\Entity\Issue $issues
     */
    public function removeIssue(\AppBundle\Entity\Issue $issues)
    {
        $this->issues->removeElement($issues);
    }

    /**
     * Get issues
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getIssues()
    {
        return $this->issues;
    }

    /**
     * Set timezone
     *
     * @param string $timezone
     * @return User
     */
    public function setTimezone($timezone)
    {
        $this->timezone = $timezone;

        return $this;
    }

    /**
     * Get timezone
     *
     * @return string
     */
    public function getTimezone()
    {
        return $this->timezone;
    }
}
