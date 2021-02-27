<?php

namespace App\Entity\Hermes;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface
{

    const ROLE_SUPER_ADMIN =  'ROLE_SUPER_ADMIN' ;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=25)
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=25)
     */
    private $lastname;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @var boolean
     * @ORM\Column(type="boolean", nullable=true)
     */
    public $superAdmin = false;

    /**
     * @var Post[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Hermes\Post", mappedBy="user",  cascade={"persist", "remove"})
     * @ORM\JoinTable(name="post_user")
     */
    private $posts;

    /**
     * @var Section[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Hermes\Section", mappedBy="user",  cascade={"persist", "remove"})
     * @ORM\JoinTable(name="section_user")
     */
    private $sections;

    /**
     * @var Menu[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Hermes\Menu", mappedBy="user",  cascade={"persist", "remove"})
     * @ORM\JoinTable(name="menu_user")
     */
    private $menus;

    /**
     * @var string le token qui servira lors de l'oubli de mot de passe
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $resetToken;

    public function __construct()
    {
        $this->posts = new ArrayCollection();
        $this->sections = new ArrayCollection();
        $this->menus = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getFirstname(). ' '. $this->getLastname();
    }

    public function __get($prop)
    {
        return $this->$prop;
    }

    public function __isset($prop) : bool
    {
        return isset($this->$prop);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @return mixed
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * @param mixed $firstname
     */
    public function setFirstname($firstname): void
    {
        $this->firstname = $firstname;
    }

    /**
     * @return mixed
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @param mixed $lastname
     */
    public function setLastname($lastname): void
    {
        $this->lastname = $lastname;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;
        $this->setSuperAdmin();

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {

        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return string
     */
    public function getResetToken(): string
    {
        return $this->resetToken;
    }

    /**
     * @param string $resetToken
     */
    public function setResetToken(?string $resetToken): void
    {
        $this->resetToken = $resetToken;
    }


    public function addPost(?Post ...$posts): void
    {
        foreach ($posts as $post) {
            if (!$this->posts->contains($post)) {
                if($post->isActive()){
                    $this->posts->add($post);
                }
            }
        }
    }

    public function removePost(Post $post): void
    {
        $this->posts->removeElement($post);
    }

    public function getPosts(): ?Collection
    {
        foreach ($this->posts as $post){
            if(!$post->isActive()){
                $this->removePost($post);
            }
        }
        return $this->posts;
    }

    public function addSection(?Section ...$sections): void
    {
        foreach ($sections as $section) {
            if (!$this->sections->contains($section)) {
                if($section->isActive()){
                    $this->sections->add($section);
                }
            }
        }
    }

    public function removeSection(Section $section): void
    {
        $this->sections->removeElement($section);
    }

    public function getSections(): ?Collection
    {
        foreach ($this->sections as $section){
            if(!$section->isActive()){
                $this->removeSection($section);
            }
        }
        return $this->sections;
    }

    public function addMenu(?Menu ...$menus): void
    {
        foreach ($menus as $menu) {
            if (!$this->menus->contains($menu)) {
                if($menu->isActive()){
                    $this->menus->add($menu);
                }
            }
        }
    }

    public function removeMenu(Menu $menu): void
    {
        $this->menus->removeElement($menu);
    }

    public function getMenus(): ?Collection
    {
        foreach ($this->menus as $menu){
            if(!$menu->isActive()){
                $this->removeMenu($menu);
            }
        }
        return $this->menus;
    }

    public function idSuperAdmin(){
        return $this->superAdmin;
    }


    public function setSuperAdmin(){
        $this->superAdmin =  false;
        if(in_array(self::ROLE_SUPER_ADMIN, $this->getRoles())){
            $this->superAdmin =  true;
        }
    }


}
