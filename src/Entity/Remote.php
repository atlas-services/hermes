<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Entity;

use App\Entity\Traits\ActiveTrait;
use App\Entity\Traits\IdTrait;
use App\Entity\Traits\NameTrait;
use App\Entity\Traits\PositionTrait;
use App\Entity\Traits\TemplateTrait;
use App\Entity\Traits\UpdatedTrait;
use App\Entity\Traits\UserTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity()
 * @ORM\Table(name="remote")
 *
 */
class Remote
{
    use ActiveTrait;
    use IdTrait;
    use NameTrait;
    use UpdatedTrait;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     *
     * @Assert\Url(
     *    protocols = {"http", "https", "ftp", "sftp"},
     *    message = "The url '{{ value }}' is not a valid url"
     * )
     */
    protected $url;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $directory;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $username;

    /**
     * @var string The hashed password
     * @ORM\Column(type="string", nullable=true)
     */
    private $password;

    /**
     * @var array
     * @ORM\Column(type="array", nullable=true)
     */
    protected $images;

    /**
     * @var Section[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Section", mappedBy="remote",  cascade={"persist", "remove"})
     * @ORM\JoinTable(name="section_remote")
     */
    private $sections;

    /**
     * Remote constructor.
     */
    public function __construct()
    {
        $this->sections = new ArrayCollection();
        $this->images = new ArrayCollection();
    }

    public function __toString(): ?string
    {
        if(!is_null($this->name)){
            return $this->name;
        }
        return '';
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): void
    {
        $this->url = $url;

    }

    public function getDirectory(): ?string
    {
        return $this->directory;
    }

    public function setDirectory(?string $directory): void
    {
        $this->directory = $directory;
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
        $section->setSection(null);
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

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username): void
    {
        $this->username = $username;
    }


    /**
     * @return mixed
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

    public function getImages(): ?array
    {
        return $this->images;
    }

    public function updateImages(): ?self
    {
        if($this->images != $this->getRemoteImages()){
            $this->images = $this->getRemoteImages();
            $this->setUpdatedAt(new \DateTime("now"));
        }
        return $this;

    }

    public function getRemoteImages(): ?array
    {
        $http_images = [];
        if(!is_null($this->getUrl())){
            $ftp_images = $this->getFtpImages();
            $http_images = $this->getHttpImages($ftp_images);
        }
        return $http_images;
    }

    private function getFtpImages(){
        $contents =[];
        $ftp_server = str_replace('ftp://', '',$this->getUrl());
        $ftp_user_name = $this->getUsername();
        $ftp_user_pass = $this->getPassword();
        $directory = $this->getDirectory();
        // Mise en place d'une connexion basique
        $conn_id = ftp_connect($ftp_server, '21','3');

        if(!$conn_id){
            return $contents ;
        }

        // Identification avec un nom d'utilisateur et un mot de passe
        $login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);

        if($login_result){
            $contents = ftp_nlist($conn_id, $directory);
        }

        // Récupération du contenu d'un dossier
        ftp_close($conn_id);

        return $contents;

    }

    /*
     * @todo : gestion du site http et du répertoire public
     */
    function getHttpImages($contents) {
        $img_url = [];
        $public = 'hermes/public/';
        $site = 'http://hermes.atlas-services.fr';

        if( empty($contents)){
            return $img_url ;
        }

        foreach ($contents as $img) {
            $img_path = str_replace($this->getUrl(), $site, $this->getUrl()).$this->getDirectory().$img;
            $img_url[$img] = str_replace($public, '', $img_path);
        }

        // Liste des urls des images distantes
        return $img_url ;
    }

}
