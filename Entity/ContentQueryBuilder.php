<?php
namespace Iphp\ContentBundle\Entity;

use Doctrine\ORM\QueryBuilder;
use Iphp\CoreBundle\Entity\BaseEntityQueryBuilder;

class ContentQueryBuilder extends BaseEntityQueryBuilder
{

    /**
     * @var \Application\Iphp\CoreBundle\Entity\Rubric
     */
    protected $fromRubric = null;


    /**
     * @var integer
     */
    protected $fromRubricId;


    public function getDefaultAlias()
    {
        return 'c';
    }

    function fromRubric($rubric)
    {
        if ($rubric instanceof \Application\Iphp\CoreBundle\Entity\Rubric) {
            $this->fromRubric = $rubric;

        } else {
            $this->fromRubricId = $rubric;
            if (!is_integer($this->fromRubricId)) {
                $this->prepareFromRubric();
                $rubric = $this->fromRubric;
            }
        }
        // else throw new \InvalidArgumentException (
        //     'Method ContentQueryBuilder::fromRubric accepts integer or \Application\Iphp\CoreBundle\Entity\Rubric object');


        $this->orWhere($this->currentAlias . '.rubric = :fromRubric')->setParameter('fromRubric', $rubric);
        /*else
          $this->join('c.rubric', 'r')->andWhere('r.fullPath = :fromRubricFullPath')->setParameter('fromRubricFullPath',$rubric);*/

        return $this;
    }


    function withSubrubrics($withSubrubrics = true)
    {

        if (!$withSubrubrics) return;

        $this->prepareFromRubric();

        if ($this->fromRubric) {
            $children = $this->getRubricRepository()->children($this->fromRubric);

            foreach ($children as $pos => $child)
                $this->orWhere($this->currentAlias . '.rubric = :fromChild' . $pos)->setParameter('fromChild' . $pos, $child);
        }

        return $this;
    }


    protected function prepareFromRubric()
    {
        if ($this->fromRubric || !$this->fromRubricId) return;


        $this->fromRubric = is_integer($this->fromRubricId) ?
            $this->getRubricRepository()->find($this->fromRubricId) :
            $this->getRubricRepository()->findOneByFullPath($this->fromRubricId);
    }


    protected function getRubricRepository()
    {
        return $this->getEntityManager()->getRepository('ApplicationIphpCoreBundle:Rubric');
    }


    public function whereEnabled($enabled = true)
    {
        if ($enabled === null) return $this;
        $enabled = (bool)$enabled;
        $this->andWhere('c.enabled = :contentEnabled')->setParameter('contentEnabled', $enabled);
        return $this;
    }


    public function whereIndex($isIndex)
    {
        $this->andWhere("c.slug " . ($isIndex ? " = ''" : "<> ''" ));
        return $this;
    }

    protected function getSearchFields($params = array())
    {
        return array($this->currentAlias . '.title',
            $this->currentAlias . '.abstract',
            $this->currentAlias . '.content');
    }

    public function search($searchStr)
    {
        if (!$searchStr) return $this;
        $searchExpr = $this->expr()->orx();

        foreach ($this->getSearchFields() as $field)
            $searchExpr->add($this->expr()->like($field, $this->expr()->literal('%' . $searchStr . '%')));

        $this->andWhere($searchExpr);
        return $this;
    }
}