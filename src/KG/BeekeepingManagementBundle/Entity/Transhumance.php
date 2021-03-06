<?php

/* 
 * Copyright (C) 2015 Kévin Grenèche < kevin.greneche at openhivemanager.org >
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace KG\BeekeepingManagementBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * Transhumance
 *
 * @ORM\Table()
 * @ORM\Entity()
 */
class Transhumance
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var Colonie
     * 
     * @ORM\ManyToOne(targetEntity="KG\BeekeepingManagementBundle\Entity\Colonie", inversedBy="transhumances", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $colonie;    

    /**
     * @var Emplacement
     * 
     * @ORM\ManyToOne(targetEntity="KG\BeekeepingManagementBundle\Entity\Emplacement", inversedBy="transhumancesfrom")
     * @Assert\NotBlank(message="Veuillez sélectionner l'emplacement de départ")
     */
    private $emplacementfrom;  

    /**
     * @var Rucher
     * 
     * @ORM\ManyToOne(targetEntity="KG\BeekeepingManagementBundle\Entity\Rucher")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="Veuillez sélectionner le rucher de départ")
     */
    private $rucherfrom;  
    
    /**
     * @var Emplacement
     * 
     * @ORM\ManyToOne(targetEntity="KG\BeekeepingManagementBundle\Entity\Emplacement", inversedBy="transhumancesto")
     * @Assert\NotBlank(message="Veuillez sélectionner l'emplacement sur lequel la ruche sera déplacée")
     */
    private $emplacementto;      

    /**
     * @var Rucher
     * 
     * @ORM\ManyToOne(targetEntity="KG\BeekeepingManagementBundle\Entity\Rucher")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="Veuillez sélectionner le rucher sur lequel la ruche sera déplacée")
     */
    private $rucherto;      
    
     /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     * @Assert\DateTime()
     */
    private $date;
    
    /**
     * Constructor
     */
    public function __construct(Colonie $colonie)
    {
        $this->colonie = $colonie;
        $this->emplacementfrom = $colonie->getRuche()->getEmplacement();
        $this->rucherfrom = $colonie->getRuche()->getRucher();
    }
    
   /**
   * @Assert\Callback
   */
    public function isContentValid(ExecutionContextInterface $context)
    {
        
        foreach( $this->getColonie()->getTranshumances() as $lastTranshumance ){
            if ( $this->date < $lastTranshumance->getDate()  && $lastTranshumance->getId() != $this->getId() ){                
                $context
                       ->buildViolation('La date ne peut pas être antérieur à celle d\'une ancienne transhumance') 
                       ->atPath('date')
                       ->addViolation();
            }            
        }
        
        if( $this->date < $this->getColonie()->getDateColonie() ){
            $context
                   ->buildViolation('La date ne peut pas être antérieur à celle de la naissance de la colonie') 
                   ->atPath('date')
                   ->addViolation();            
        }
        
        $today = new \DateTime();
        
        if( $this->date > $today ){
            $context
                   ->buildViolation('La date ne peut pas être située dans le futur') 
                   ->atPath('date')
                   ->addViolation();            
        }
    
        if( $this->emplacementfrom && $this->emplacementto ){
            if( $this->emplacementfrom->getRucher() == $this->emplacementto->getRucher() ){
                $context
                       ->buildViolation('La transhumance ne peut pas se faire dans le même rucher') 
                       ->atPath('rucherto')
                       ->addViolation();            
            }
        }
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
     * Set date
     *
     * @param \DateTime $date
     * @return Transhumance
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
     * Set colonie
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Colonie $colonie
     * @return Transhumance
     */
    public function setColonie(\KG\BeekeepingManagementBundle\Entity\Colonie $colonie)
    {
        $this->colonie = $colonie;
        $colonie->addTranshumance($this);
        return $this;
    }

    /**
     * Get colonie
     *
     * @return \KG\BeekeepingManagementBundle\Entity\Colonie 
     */
    public function getColonie()
    {
        return $this->colonie;
    }

    /**
     * Set emplacementfrom
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Emplacement $emplacementfrom
     * @return Transhumance
     */
    public function setEmplacementfrom(\KG\BeekeepingManagementBundle\Entity\Emplacement $emplacementfrom=null)
    {
        $this->emplacementfrom = $emplacementfrom;   
        return $this;
    }

    /**
     * Get emplacementfrom
     *
     * @return \KG\BeekeepingManagementBundle\Entity\Emplacement 
     */
    public function getEmplacementfrom()
    {
        return $this->emplacementfrom;
    }

    /**
     * Set emplacementto
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Emplacement $emplacementto
     * @return Transhumance
     */
    public function setEmplacementto(\KG\BeekeepingManagementBundle\Entity\Emplacement $emplacementto=null)
    {
        $this->emplacementto = $emplacementto;
        return $this;
    }

    /**
     * Get emplacementto
     *
     * @return \KG\BeekeepingManagementBundle\Entity\Emplacement 
     */
    public function getEmplacementto()
    {
        return $this->emplacementto;
    }    
    
    /**
     * Set rucherfrom
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Rucher $rucherfrom
     * @return Transhumance
     */
    public function setRucherfrom(\KG\BeekeepingManagementBundle\Entity\Rucher $rucherfrom)
    {
        $this->rucherfrom = $rucherfrom;
        return $this;
    }

    /**
     * Get rucherfrom
     *
     * @return \KG\BeekeepingManagementBundle\Entity\Rucher
     */
    public function getRucherfrom()
    {
        return $this->rucherfrom;
    }

    /**
     * Set rucherto
     *
     * @param \KG\BeekeepingManagementBundle\Entity\Rucher $rucherto
     * @return Transhumance
     */
    public function setRucherto(\KG\BeekeepingManagementBundle\Entity\Rucher $rucherto)
    {
        $this->rucherto = $rucherto;
        return $this;
    }

    /**
     * Get rucherto
     *
     * @return \KG\BeekeepingManagementBundle\Entity\Rucher
     */
    public function getRucherto()
    {
        return $this->rucherto;
    }    
    
}
