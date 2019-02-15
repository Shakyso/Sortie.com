<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\NotBlank(message = "Votre pseudo ne peut pas être vide")
     * @Assert\Length(
     *      min = 2,
     *      max = 20,
     *      minMessage = "Votre pseudo doit contenir au minimum {{ limit }} caracteres",
     *      maxMessage = "Votre pseudo ne peut contenir plus de {{ limit }} caracteres"
     *)
     */
    private $username;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @Assert\NotBlank(message = "je suis la")
     * @ORM\Column(type="string")
     */
    private $password;


    /**
     * @ORM\Column(type="string", length=25, nullable=true)
     * @Assert\Length(
     *     min = 10,
     *     max = 25,
     *     minMessage = "Le numéro de téléphone doit contenir au moins {{ limit }}",
     *     maxMessage = "Le numéro ne peut pas contenir plus de {{ limit }} chiffres"
     * )
     */
    private $telephone;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     * @Assert\Email(
     * message = "l'adresse '{{ value }}' n'est pas une adresse email valide .",
     * )
     */
    private $mail;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Site", inversedBy="users")
     * @ORM\JoinColumn(nullable=true)
     */
    private $site;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Sortie", mappedBy="organisateur")
     */
    private $sorties;


    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Sortie", mappedBy="users")
     */
    private $sortiesInscrit;

    private $newPassword;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     * @Assert\File(mimeTypes={ "image/png", "image/jpeg" })
     */
    private $photo;


    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     *
     * @Assert\Length(
     *      min = 2,
     *      max = 50,
     *      minMessage = "Votre prénom doit avoir au moins {{ limit }} caracteres",
     *      maxMessage = "Votré prénom ne peut pas contenir plus de {{ limit }} caracteres"
     * )
     */
    protected $name;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     * @Assert\Length(
     *      min = 2,
     *      max = 50,
     *      minMessage = "Votre Nom de famille doit avoir au moins {{ limit }} caracteres",
     *      maxMessage = "Votré Nom de famille ne peut pas contenir plus de {{ limit }} caracteres"
     * )
     */
    private $lastname;

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
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




    public function __construct()
    {
        $this->sorties = new ArrayCollection();

        $this->sortiesInscrit = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
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
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
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

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(?string $mail): self
    {
        $this->mail = $mail;

        return $this;
    }

    public function getSite(): ?Site
    {
        return $this->site;
    }

    public function setSite(?Site $site): self
    {
        $this->site = $site;

        return $this;
    }

    public function getNewPassword(){
        return $this->newPassword;
    }


    public function setNewPassword($newPassword){
        $this->newPassword = $newPassword;
        return $this->newPassword;
    }

    public function getPhoto(){
        return $this->photo;
    }

    public function setPhoto($photo){
        $this->photo = $photo;
        return $this->photo;

    }

    /**
     * @return Collection|Sortie[]
     */
    public function getSorties(): Collection
    {
        return $this->sorties;
    }

    public function addSorty(Sortie $sorty): self
    {
        if (!$this->sorties->contains($sorty)) {
            $this->sorties[] = $sorty;
            $sorty->setOrganisateur($this);
        }

        return $this;
    }

    public function removeSorty(Sortie $sorty): self
    {
        if ($this->sorties->contains($sorty)) {
            $this->sorties->removeElement($sorty);
            // set the owning side to null (unless already changed)
            if ($sorty->getOrganisateur() === $this) {
                $sorty->setOrganisateur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Sortie[]
     */
    public function getSortiesInscrit(): Collection
    {
        return $this->sortiesInscrit;
    }

    public function addSortiesInscrit(Sortie $sortiesInscrit): self
    {
        if (!$this->sortiesInscrit->contains($sortiesInscrit)) {
            $this->sortiesInscrit[] = $sortiesInscrit;
            $sortiesInscrit->addUser($this);
        }

        return $this;
    }

    public function removeSortiesInscrit(Sortie $sortiesInscrit): self
    {
        if ($this->sortiesInscrit->contains($sortiesInscrit)) {
            $this->sortiesInscrit->removeElement($sortiesInscrit);
            $sortiesInscrit->removeUser($this);
        }

        return $this;
    }


}
