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

use Doctrine\ORM\EntityRepository;

/**
 * EmplacementRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class EmplacementRepository extends EntityRepository
{
    public function getListByRucher($rucher)
    {
        return $this->createQueryBuilder('e')
                    ->leftJoin('e.rucher','rucher')
                    ->leftJoin('e.ruche','ruche')
                    ->where('rucher.id = :rucher')
                    ->setParameter('rucher',$rucher->getId())
                    ->getQuery();
    } 
    
    public function queryfindByRucherId($rucher)
    {
        return $this->createQueryBuilder('emplacement')
                    ->leftJoin('emplacement.rucher', 'rucher')
                    ->leftJoin('emplacement.ruche', 'ruche')            
                    ->where('rucher.id = :rucher') 
                    ->andWhere('ruche.emplacement is NULL')
                    ->setParameter('rucher',$rucher);
    }

    public function findByRucherId($rucher)
    {
        return $this->queryfindByRucherId($rucher)
                    ->getQuery()
                    ->getArrayResult();
    }    
}
