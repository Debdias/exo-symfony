<?php

namespace OC\PlatformBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Advert
 *
 * @ORM\Table(name="advert")
 * @ORM\Entity(repositoryClass="OC\PlatformBundle\Repository\AdvertRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Advert
{

  /**
   * @Gedmo\Slug(fields={"title"})
   * @ORM\Column(name="slug", type="string", length=255, unique=true)
   */
  private $slug;
  /**
  * @ORM\Column(name="nb_applications", type="integer")
  */
  private $nbApplications = 0;

  public function increaseApplication()
  {
    $this->nbApplications++;
  }
  public function decreaseApplication()
  {
  $this->nbApplications--;
  }
  /**
  * @ORM\PreUpdate
  */
    public function updateDate()
    {
        $this->setUpdateAt(new \DateTime());
    }
    public function __construct()
    {
      $this->date = new \DateTime();
      $this->categories = new ArrayCollection();
      $this->applications = new ArrayCollection();
    }

    /**
    * @ORM\Column(name="updated_at", type="datetime", nullable=true)
    */
    private $updateAt;
    /**
    * @ORM\OneToMany(targetEntity="OC\PlatformBundle\Entity\Application", mappedBy="advert")
    */
    private $applications;

    /**
    * @ORM\ManyToMany(targetEntity="OC\PlatformBundle\Entity\Category", cascade={"persist"})
    * @ORM\JoinTable(name="oc_advert_category")
    */
    private $categories;
    /**
      * @ORM\OneToOne(targetEntity="OC\PlatformBundle\Entity\Image", cascade={"persist"})
      * @ORM\JoinColumn(nullable=false)
      */
    private $image;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="author", type="string", length=255)
     */
    private $author;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
    * @ORM\Column(name="published", type="boolean")
    */
    private $published = true;

    /**
     * Get id
     *
     * @return int
     */

    public function getId()
    {
        return $this->id;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Advert
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Advert
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set author
     *
     * @param string $author
     *
     * @return Advert
     */
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return Advert
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
  }

  /**
  * Set published
  *
  * @param boolean $published
  *
  * @return Advert
  */
  public function setPublished($published)
  {
    $this->published = $published;

    return $this;
  }

  /**
 * Get published
 *
 * @return boolean
 */
  public function getPublished()
  {
      return $this->published;
  }

  public function setImage(Image $image = null)
  {
    $this->image = $image;
  }

  public function getImage()
  {
    return $this->image;
  }
  public function addCategory(Category $category)
  {
    // Ici, on utilise l'ArrayCollection vraiment comme un tableau
    $this->categories[] = $category;
  }
 public function removeCategory(Category $category)
 {
   // Ici on utilise une méthode de l'ArrayCollection, pour supprimer la catégorie en argument
   $this->categories->removeElement($category);
 }

 // Notez le pluriel, on récupère une liste de catégories ici !
 public function getCategories()
 {
   return $this->categories;
 }
/**
 * Add application
 *
 * @param \OC\PlatformBundle\Entity\Application $application
 *
 * @return Advert
 */
  public function addApplication(Application $application)
  {
      $this->applications[] = $application;

      $application->setAdvert($this);

      return $this;
  }
  /**
   * Remove application
   *
   * @param \OC\PlatformBundle\Entity\Application $application
   */
    public function removeApplication(Application $application)
    {
      $this->applications->removeElement($application);
    }

    /**
     * Get applications
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getApplications()
    {
      return $this->applications;
    }

    /**
     * Set updateAt
     *
     * @param \DateTime $updateAt
     *
     * @return Advert
     */
    public function setUpdateAt($updateAt)
    {
        $this->updateAt = $updateAt;

        return $this;
    }

    /**
     * Get updateAt
     *
     * @return \DateTime
     */
    public function getUpdateAt()
    {
        return $this->updateAt;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Advert
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set nbApplications
     *
     * @param integer $nbApplications
     *
     * @return Advert
     */
    public function setNbApplications($nbApplications)
    {
        $this->nbApplications = $nbApplications;

        return $this;
    }

    /**
     * Get nbApplications
     *
     * @return integer
     */
    public function getNbApplications()
    {
        return $this->nbApplications;
    }
}
