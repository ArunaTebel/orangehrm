<?php
/**
 * OrangeHRM is a comprehensive Human Resource Management (HRM) System that captures
 * all the essential functionalities required for any enterprise.
 * Copyright (C) 2006 OrangeHRM Inc., http://www.orangehrm.com
 *
 * OrangeHRM is free software; you can redistribute it and/or modify it under the terms of
 * the GNU General Public License as published by the Free Software Foundation; either
 * version 2 of the License, or (at your option) any later version.
 *
 * OrangeHRM is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with this program;
 * if not, write to the Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor,
 * Boston, MA  02110-1301, USA
 */

namespace OrangeHRM\Admin\Dao;

use Exception;
use OrangeHRM\Admin\Dto\PayGradeSearchFilterParams;
use OrangeHRM\Core\Dao\BaseDao;
use OrangeHRM\Core\Exception\DaoException;
use OrangeHRM\Entity\CurrencyType;
use OrangeHRM\Entity\PayGrade;
use OrangeHRM\Entity\PayGradeCurrency;
use OrangeHRM\Entity\PayPeriod;
use OrangeHRM\ORM\ListSorter;
use OrangeHRM\ORM\Paginator;

class PayGradeDao extends BaseDao
{
    /**
     * @param int $payGradeId
     * @return PayGrade|null
     */
    public function getPayGradeById(int $payGradeId): ?PayGrade
    {
        try {
            return $this->getRepository(PayGrade::class)->find($payGradeId);
        } catch (Exception $e) {
            throw new DaoException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @return PayGrade[]
     */
    public function getPayGradeList(PayGradeSearchFilterParams $payGradeSearchFilterParams): array
    {
        try {
            return $this->getPayGradesPaginator($payGradeSearchFilterParams)->getQuery()->execute();
        } catch (Exception $e) {
            throw new DaoException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @param PayGradeSearchFilterParams $payGradeSearchFilterParams
     * @return Paginator
     */
    private function getPayGradesPaginator(PayGradeSearchFilterParams $payGradeSearchFilterParams): Paginator
    {
        $q = $this->createQueryBuilder(PayGrade::class, 'pg');
        $this->setSortingAndPaginationParams($q, $payGradeSearchFilterParams);
        return $this->getPaginator($q);
    }

    /**
     * @param int $payGradeId
     * @return PayGradeCurrency[]
     * @throws DaoException
     */
    public function getCurrencyListByPayGradeId(int $payGradeId): array
    {
        try {
            $q = $this->createQueryBuilder(PayGradeCurrency::class, 'pgc');
            $q->leftJoin('pgc.currencyType', 'ct');
            $q->andWhere('pgc.payGrade = :payGradeId')
                ->setParameter('payGradeId', $payGradeId);
            $q->addOrderBy('ct.name', ListSorter::ASCENDING);
            return $q->getQuery()->execute();
        } catch (Exception $e) {
            throw new DaoException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @param string $currencyId
     * @param int $payGradeId
     * @return PayGradeCurrency|null
     * @throws DaoException
     */
    public function getCurrencyByCurrencyIdAndPayGradeId(string $currencyId, int $payGradeId): ?PayGradeCurrency
    {
        try {
            $q = $this->createQueryBuilder(PayGradeCurrency::class, 'pgc');
            $q->andWhere('pgc.payGrade = :payGradeId')
                ->setParameter('payGradeId', $payGradeId);
            $q->andWhere('pgc.currencyType = :currencyTypeId')
                ->setParameter('currencyTypeId', $currencyId);

            return $this->fetchOne($q);
        } catch (Exception $e) {
            throw new DaoException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @return PayPeriod[]
     * @throws DaoException
     */
    public function getPayPeriods(): array
    {
        try {
            return $this->getRepository(PayPeriod::class)->findAll();
        } catch (Exception $e) {
            throw new DaoException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @return CurrencyType[]
     * @throws DaoException
     */
    public function getCurrencies(): array
    {
        try {
            $q = $this->createQueryBuilder(CurrencyType::class, 'ct');
            $q->addOrderBy('ct.name', ListSorter::ASCENDING);
            return $q->getQuery()->execute();
        } catch (Exception $e) {
            throw new DaoException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @param string $id
     * @return CurrencyType|null
     * @throws DaoException
     */
    public function getCurrencyById(string $id): ?CurrencyType
    {
        try {
            return $this->getRepository(CurrencyType::class)->find($id);
        } catch (Exception $e) {
            throw new DaoException($e->getMessage(), $e->getCode(), $e);
        }
    }
}