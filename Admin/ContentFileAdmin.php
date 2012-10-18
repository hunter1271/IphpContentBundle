<?php
/**
 * Created by Vitiko
 * Date: 07.08.12
 * Time: 15:27
 */
namespace Iphp\ContentBundle\Admin;

use Sonata\AdminBundle\Form\FormMapper;
use Iphp\CoreBundle\Admin\Admin as IphpAdmin;

class ContentFileAdmin extends IphpAdmin
{


    /**
     * @param \Sonata\AdminBundle\Form\FormMapper $formMapper
     * @return void
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('title', 'text', array('attr' => array('style' => 'width:200px')))
            ->add('file', 'iphp_file', array('required' => false))
            ->add('pos', 'hidden')/**
         *  ->add('fileUpload', 'file', array('required' => false, 'show_uploaded' => false,  'label' => 'File'))
        ->add('file', 'iphp_file', array('required' => false, 'read_only' => true, 'label' => ''))
         */
        ;
    }
}
