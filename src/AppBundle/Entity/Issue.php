<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Repository\IssueRepository")
 * @ORM\Table(name="issue")
 * @ORM\HasLifecycleCallbacks
 */
class Issue
{
    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    protected $code;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    protected $summary;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     */
    protected $description;

    /**
     * @var string
     *
     * @ORM\Column(type="enum_type_issue")
     */
    protected $type;

    /**
     * @var string
     *
     * @ORM\Column(type="enum_priority_issue")
     */
    protected $priority;

    /**
     * @var string
     *
     * @ORM\Column(type="enum_resolution_issue", nullable=true)
     */
    protected $resolution;

    /**
     * @var string
     *
     * @ORM\Column(type="enum_status_issue")
     */
    protected $status;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="reporter_id", referencedColumnName="id")
     */
    protected $reporter;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="assignee_id", referencedColumnName="id")
     */
    protected $assignee;

    /**
     * @var ArrayCollection User[]
     *
     * @ORM\ManyToMany(targetEntity="\UserBundle\Entity\User", inversedBy="issues")
     * @ORM\JoinTable(name="issue_collaborator_user")
     */
    protected $collaborators;

    /**
     * @var Issue
     *
     * @ORM\ManyToOne(targetEntity="\AppBundle\Entity\Issue", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE")
     **/
    protected $parent;

    /**
     * @var ArrayCollection Issue[]
     *
     * @ORM\OneToMany(targetEntity="\AppBundle\Entity\Issue", mappedBy="parent")
     **/
    protected $children;

    /**
     * @var Project
     *
     * @ORM\ManyToOne(targetEntity="\AppBundle\Entity\Project")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id")
     */
    protected $project;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    protected $created;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    protected $updated;

    /**
     * @var ArrayCollection Comment[]
     *
     * @ORM\OneToMany(targetEntity="\AppBundle\Entity\Comment", mappedBy="issue")
     **/
    protected $comments;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->collaborators = new ArrayCollection();
        $this->children = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->created = new \DateTime('now', new \DateTimeZone('UTC'));
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
     * Set summary
     *
     * @param string $summary
     * @return Issue
     */
    public function setSummary($summary)
    {
        $this->summary = $summary;

        return $this;
    }

    /**
     * Get summary
     *
     * @return string 
     */
    public function getSummary()
    {
        return $this->summary;
    }

    /**
     * Set code
     *
     * @param string $code
     * @return Issue
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Issue
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set type
     *
     * @param enum_type_issue $type
     * @return Issue
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return enum_type_issue 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set priority
     *
     * @param enum_priority_issue $priority
     * @return Issue
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * Get priority
     *
     * @return enum_priority_issue 
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * Set resolution
     *
     * @param string $resolution
     * @return Issue
     */
    public function setResolution($resolution)
    {
        $this->resolution = $resolution;

        return $this;
    }

    /**
     * Get resolution
     *
     * @return string
     */
    public function getResolution()
    {
        return $this->resolution;
    }

    /**
     * Set status
     *
     * @param enum_status_issue $status
     * @return Issue
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return enum_status_issue 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Issue
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime 
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set updated
     *
     * @param \DateTime $updated
     * @return Issue
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime 
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Set reporter
     *
     * @param \UserBundle\Entity\User $reporter
     * @return Issue
     */
    public function setReporter(\UserBundle\Entity\User $reporter = null)
    {
        $this->reporter = $reporter;

        return $this;
    }

    /**
     * Get reporter
     *
     * @return \UserBundle\Entity\User 
     */
    public function getReporter()
    {
        return $this->reporter;
    }

    /**
     * Set assignee
     *
     * @param \UserBundle\Entity\User $assignee
     * @return Issue
     */
    public function setAssignee(\UserBundle\Entity\User $assignee = null)
    {
        $this->assignee = $assignee;

        return $this;
    }

    /**
     * Get assignee
     *
     * @return \UserBundle\Entity\User 
     */
    public function getAssignee()
    {
        return $this->assignee;
    }

    /**
     * Add collaborators
     *
     * @param \UserBundle\Entity\User $collaborators
     * @return Issue
     */
    public function addCollaborator(\UserBundle\Entity\User $collaborators)
    {
        $this->collaborators[] = $collaborators;

        return $this;
    }

    /**
     * Remove collaborators
     *
     * @param \UserBundle\Entity\User $collaborators
     */
    public function removeCollaborator(\UserBundle\Entity\User $collaborators)
    {
        $this->collaborators->removeElement($collaborators);
    }

    /**
     * Get collaborators
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCollaborators()
    {
        return $this->collaborators;
    }

    /**
     * Set parent
     *
     * @param \AppBundle\Entity\Issue $parent
     * @return Issue
     */
    public function setParent(\AppBundle\Entity\Issue $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \AppBundle\Entity\Issue 
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Add children
     *
     * @param \AppBundle\Entity\Issue $children
     * @return Issue
     */
    public function addChild(\AppBundle\Entity\Issue $children)
    {
        $this->children[] = $children;

        return $this;
    }

    /**
     * Remove children
     *
     * @param \AppBundle\Entity\Issue $children
     */
    public function removeChild(\AppBundle\Entity\Issue $children)
    {
        $this->children->removeElement($children);
    }

    /**
     * Get children
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Set project
     *
     * @param \AppBundle\Entity\Project $project
     * @return Issue
     */
    public function setProject(\AppBundle\Entity\Project $project = null)
    {
        $this->project = $project;

        return $this;
    }

    /**
     * Get project
     *
     * @return \AppBundle\Entity\Project 
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * Add comments
     *
     * @param \AppBundle\Entity\Comment $comments
     * @return Issue
     */
    public function addComment(\AppBundle\Entity\Comment $comments)
    {
        $this->comments[] = $comments;

        return $this;
    }

    /**
     * Remove comments
     *
     * @param \AppBundle\Entity\Comment $comments
     */
    public function removeComment(\AppBundle\Entity\Comment $comments)
    {
        $this->comments->removeElement($comments);
    }

    /**
     * Get comments
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function beforeSave()
    {
        $this->updated = new \DateTime('now', new \DateTimeZone('UTC'));
    }
}
