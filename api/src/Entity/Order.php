<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;

use Money\Currency;
use Money\Money;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * An entity representing an order.
 *
 * This entity represents an order for sales
 *
 * @author Robert Zondervan <robert@conduction.nl>
 *
 * @category entity
 *
 * @license EUPL <https://github.com/ConductionNL/productenendienstencatalogus/blob/master/LICENSE.md>
 *
 * @ApiResource(
 *     normalizationContext={"groups"={"read"}, "enable_max_depth"=true},
 *     denormalizationContext={"groups"={"write"}, "enable_max_depth"=true},
 *     itemOperations={
 *          "get",
 *          "put",
 *          "delete",
 *          "get_change_logs"={
 *              "path"="/orders/{id}/change_log",
 *              "method"="get",
 *              "swagger_context" = {
 *                  "summary"="Changelogs",
 *                  "description"="Gets al the change logs for this resource"
 *              }
 *          },
 *          "get_audit_trail"={
 *              "path"="/orders/{id}/audit_trail",
 *              "method"="get",
 *              "swagger_context" = {
 *                  "summary"="Audittrail",
 *                  "description"="Gets the audit trail for this resource"
 *              }
 *          }
 *     }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\OrderRepository")
 * @Gedmo\Loggable(logEntryClass="App\Entity\ChangeLog")
 * @ORM\Table(name="orders")
 * @ORM\HasLifecycleCallbacks
 *
 * @ApiFilter(BooleanFilter::class)
 * @ApiFilter(OrderFilter::class)
 * @ApiFilter(DateFilter::class, strategy=DateFilter::EXCLUDE_NULL)
 * @ApiFilter(SearchFilter::class)
 */
class Order
{
    /**
     * @var UuidInterface The UUID identifier of this object
     *
     * @example e2984465-190a-4562-829e-a8cca81aa35d
     *
     *
     * @Groups({"read"})
     * @Assert\Uuid
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     */
    private $id;

    /**
     * @var string The name of the order
     *
     * @Gedmo\Versioned
     * @example my Order
     * @Groups({"read","write"})
     * @Assert\Length(
     *     max=255
     * )
     * @Assert\NotNull
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @var string The description of the order
     *
     * @Gedmo\Versioned
     * @example This is the best order ever
     * @Groups({"read","write"})
     * @Assert\Length(
     *     max=255
     * )
     * @ORM\Column(type="string", length=2550, nullable=true)
     */
    private $description;

    /**
     * @var string The human readable reference for this request, build as {gemeentecode}-{year}-{referenceId}. Where gemeentecode is a four digit number for gemeenten and a four letter abriviation for other organizations
     *
     * @example 6666-2019-0000000012
     *
     * @Gedmo\Versioned
     * @Groups({"read"})
     * @ORM\Column(type="string", length=255, nullable=true, unique=true)
     * @ApiFilter(SearchFilter::class, strategy="exact")
     * @Assert\Length(
     *     max = 255
     * )
     */
    private $reference;

    /**
     * @param string $referenceId The autoincrementing id part of the reference, unique on an organization-year-id basis
     *
     * @Gedmo\Versioned
     * @Assert\Positive
     * @Assert\Length(
     *      max = 11
     * )
     * @ORM\Column(type="integer", length=11, nullable=true)
     */
    private $referenceId;

    /**
     * @var string The RSIN of the organization that ownes this proces
     *
     * @example 002851234
     *
     * @Gedmo\Versioned
     * @Assert\NotNull
     * @Assert\Length(
     *     max = 255
     * )
     * @Groups({"read", "write"})
     * @ORM\Column(type="string", length=255)
     * @ApiFilter(SearchFilter::class, strategy="exact")
     */
    private $targetOrganization;

    /**
     * @var string The price of this product
     *
     * @example 50.00
     *
     * @Gedmo\Versioned
     * @Groups({"read", "write"})
     * @ORM\Column(type="decimal", precision=8, scale=2, nullable=true)
     */
    private $price;

    /**
     * @var string The currency of this product in an [ISO 4217](https://en.wikipedia.org/wiki/ISO_4217) format
     *
     * @example EUR
     *
     * @Gedmo\Versioned
     * @Assert\Currency
     * @Groups({"read", "write"})
     * @ORM\Column(type="string", nullable=true)
     */
    private $priceCurrency;

    /**
     * @var array A list of total taxes
     *
     * @example EUR
     *
     * @Gedmo\Versioned
     * @Groups({"read"})
     * @ORM\Column(type="array")
     */
    private $taxes = [];

    /**
     * @var ArrayCollection The items in this order
     *
     * @Groups({"read", "write"})
     * @ORM\OneToMany(targetEntity="App\Entity\OrderItem", mappedBy="order", cascade={"persist"})
     * @MaxDepth(1)
     */
    private $items;

    /**
     * @var string The customer that placed this order
     *
     * @example https://example.org/people/1
     *
     * @ORM\Column(type="string", length=255)
     *
     * @Gedmo\Versioned
     * @Groups({"read","write"})
     * @Assert\Url
     */
    private $customer;

    /**
     * @var Organization The organization that created this order
     *
     * @Groups({"write", "read"})
     * @MaxDepth(1)
     * @ORM\ManyToOne(targetEntity="App\Entity\Organization", inversedBy="orders", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     * @Assert\Valid
     */
    private $organization;

    /**
     * @var string Remarks on this order
     *
     * @Gedmo\Versioned
     * @Groups({"read","write"})
     * @ORM\Column(type="text", nullable=true)
     */
    private $remark;

    /**
     * @var DateTime The moment this request was created by the submitter
     *
     * @Groups({"read"})
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateCreated;

    /**
     * @var DateTime The moment this request was modified by the submitter
     *
     * @Groups({"read"})
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateModified;

    /**
     *
     *  @ORM\PrePersist
     *  @ORM\PreUpdate
     *
     *  */
    public function prePersist()
    {
    	$this->calculateTotals();
    }

    public function calculateTotals()
    {
    	/*@todo we should support non euro */
    	$price = new Money(0, new Currency('EUR'));
    	$taxes = [];

    	foreach ($this->items as $item){

    		// Calculate Invoice Price
    		//
    		if(is_string ($item->getPrice())){
    			//Value is a string, so presumably a float
    			$float = floatval($item->getPrice());
    			$float = $float*100;
    			$itemPrice = new Money((int) $float, new Currency($item->getPriceCurrency()));

    		}
    		else{
    			// Calculate Invoice Price
    			$itemPrice = new Money($item->getPrice(), new Currency($item->getPriceCurrency()));


    		}

    		$itemPrice = $itemPrice->multiply($item->getQuantity());
    		$price = $price->add($itemPrice);

    		// Calculate Taxes
    		/*@todo we should index index on something else do, there might be diferend taxes on the same percantage. Als not all taxes are a percentage */
    		foreach($item->getTaxes() as $tax){
    			if(!array_key_exists($tax->getPercentage(), $taxes)){
    				$tax[$tax->getPercentage()] = $itemPrice->multiply($tax->getPercentage()/100);
    			}
    			else{
    				$taxPrice = $itemPrice->multiply($tax->getPercentage()/100);
    				$taxes[$tax->getPercentage()] = $taxes[$tax->getPercentage()]->add($taxPrice);
    			}
    		}

    	}

    	$this->taxes = $taxes;
    	$this->price = number_format($price->getAmount()/100, 2, '.', "");
    	$this->priceCurrency = $price->getCurrency();
    }

    public function __construct()
    {
        $this->items = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): self
    {
        $this->reference = $reference;
        return $this;
    }

    public function getReferenceId(): ?int
    {
        return $this->reference;
    }

    public function setReferenceId(int $referenceId): self
    {
        $this->referenceId = $referenceId;

        return $this;
    }

    public function getRsin(): ?string
    {
        return $this->targetOrganization;
    }

    public function setRsin(string $rsin): self
    {
        $this->targetOrganization = $rsin;

        return $this;
    }

    public function getTargetOrganization(): ?string
    {
        return $this->targetOrganization;
    }

    public function setTargetOrganization(string $targetOrganization): self
    {
        $this->targetOrganization = $targetOrganization;

        return $this;
    }

    public function getSubmitterPerson(): ?bool
    {
        return $this->submitterPerson;
    }

    public function setSubmitterPerson(bool $submitterPerson): self
    {
        $this->submitterPerson = $submitterPerson;

        return $this;
    }

    /**
     * @return Collection|OrderItem[]
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(OrderItem $item): self
    {
        if (!$this->items->contains($item)) {
            $this->items[] = $item;
            $item->setOrder($this);
        }

        return $this;
    }

    public function removeItem(OrderItem $item): self
    {
        if ($this->items->contains($item)) {
            $this->items->removeElement($item);
            // set the owning side to null (unless already changed)
            if ($item->getOrder() === $this) {
                $item->setOrder(null);
            }
        }

        return $this;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setPrice($price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getDateCreated(): ?DateTimeInterface
    {
        return $this->dateCreated;
    }

    public function setDateCreated(DateTimeInterface $dateCreated): self
    {
        $this->dateCreated = $dateCreated;

        return $this;
    }
    public function getDateModified(): ?DateTimeInterface
    {
        return $this->dateModified;
    }

    public function setDateModified(DateTimeInterface $dateModified): self
    {
        $this->dateModified = $dateModified;

        return $this;
    }

    public function getPriceCurrency(): ?string
    {
        return $this->priceCurrency;
    }

    public function setPriceCurrency(string $priceCurrency): self
    {
        $this->priceCurrency = $priceCurrency;

        return $this;
    }

    /**
     * @return Array
     */
    public function getTaxes(): Array
    {
    	return $this->taxes;
    }

    public function getCustomer(): ?string
    {
        return $this->customer;
    }

    public function setCustomer(string $customer): self
    {
        $this->customer = $customer;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getOrganization(): ?Organization
    {
        return $this->organization;
    }

    public function setOrganization(?Organization $organization): self
    {
        $this->organization = $organization;

        return $this;
    }

    public function getRemark(): ?string
    {
        return $this->remark;
    }

    public function setRemark(?string $remark): self
    {
        $this->remark = $remark;

        return $this;
    }
}
