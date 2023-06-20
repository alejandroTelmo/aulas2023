<?php

namespace app\models;

use yii\data\Pagination;

/**
 * This is the ActiveQuery class for [[HorarioMateria]].
 *
 * @see HorarioMateria
 */
class HorarioMateriaQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/
    private $pagination;
    /**
     * {@inheritdoc}
     * @return HorarioMateria[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return HorarioMateria|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    public function getWithMateria()
    {   
        return $this->with('materia');
    }

    public function paginate($page, $perPage)
    {
        $countQuery = clone $this;
        $this->pagination = new Pagination([
            'totalCount' => $countQuery->count(),
            'pageSize' => intval($perPage),
            'page' => intval($page),
        ]);

        return $this->offset($this->pagination->getOffset())
        ->limit($perPage);
    }
    
    public function getTotalCount()
    {
        return $this->pagination->totalCount;
    }

    public function getPage()
    {
        return $this->pagination->getPage();
    }

    public function getPerPage()
    {
        return $this->pagination->getPageSize();
    }

}
