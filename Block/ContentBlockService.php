<?php


namespace Iphp\ContentBundle\Block;

use Sonata\BlockBundle\Block\BaseBlockService;


abstract class ContentBlockService extends BaseBlockService
{

    protected $em;

    /**
     * @return \Iphp\ContentBundle\Entity\BaseContentRepository
     */
    protected function getRepository()
    {
        return $this->em->getRepository('ApplicationIphpContentBundle:Content');
    }


    public function setEntityManager($em)
    {
        $this->em = $em;
    }

    /**
     * @param callable $prepareQueryBuilder
     *
     * @return array
     */
    protected function getContents(\Closure $prepareQueryBuilder)
    {
        return $this->getRepository()->createQuery('c', $prepareQueryBuilder)->getResult();
    }
}
