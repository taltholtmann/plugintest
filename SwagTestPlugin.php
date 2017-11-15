<?php

namespace SwagTestPlugin;

use PDO;
use Shopware\Bundle\StoreFrontBundle\Struct\ShopContext;
use Shopware\Components\Plugin;
use Shopware\Components\Plugin\Context\InstallContext;
use Shopware\Components\Plugin\Context\UninstallContext;
use Shopware\Components\Plugin\Context\UpdateContext;
use sOrder;
use Symfony\Component\HttpKernel\HttpCache\Store;
use Zend_Db_Statement_Pdo;

class SwagTestPlugin extends Plugin
{
    public function install(InstallContext $context)
    {
        $this->createAttributes('feld1');

    }

    /**
     * @param UpdateContext $context
     */
    public function update(UpdateContext $context)
    {
        parent::update($context);
        $this->createAttributes('feld2');
    }

    public static function getSubscribedEvents()
    {
        return [
        ];
    }

    public function onFilterExportResult(\Enlight_Event_EventArgs $args)
    {
        $articles = $args->getReturn();
        /** This is the id of the feed being exported */
        $feedId = $args->get('feedId');
        /** @var \sExport $sExport */
        $sExport = $args->get('subject');

        /**
         * Here is the instance of the export class which can be used to add new variables to smarty
         */
        $sExport->sSmarty->assign('newVariable', ['custom' => 'This is a custom variable available in the export template']);

        /**
         * in $sArticles are all the articles as array which we can modify here
         */
        foreach($articles as &$article) {
            $article['specialArgument'] = random_int(1, 100);
        }
        
        return $articles;
    }

    public function testDispatch(\Enlight_Event_EventArgs $args)
    {

    }

    public function onBlogPostDispatch(\Enlight_Controller_ActionEventArgs $args)
    {
        /** @var \Shopware_Controllers_Frontend_Blog $subject */
        $subject = $args->getSubject();

        if ($subject->Request()->getActionName() !== 'detail') {
            return;
        }

        $blogArticle = $subject->Request()->getParam('blogArticle');


        $view = $subject->View();

        $view->addTemplateDir($this->getPath() . '/Resources/views');

        if ($blogArticle == '3') {
            $view->loadTemplate($this->getPath() . '/Resources/views/frontend/blog/my_detail.tpl');
        }

    }

    public function onTest()
    {
        die('neu');
    }


    public function prozessDetails(\Enlight_Event_EventArgs $args)
    {
        Shopware()->Container()->get('shopware.api.order');
        $orderId = $args->get('orderId');

        $models = $this->container->get('models');

        $api = Shopware()->Container()->get('shopware.api.order');

        $api->setManager($models);

    }

    public function orderUpdate(\Enlight_Event_EventArgs $args)
    {
        $modelManager = $args->get('entityManager');
        /** @var \Shopware\Models\Order\Order $model */
        $model = $args->get('entity');
        
        $this->container->get('template')->addTemplateDir(
            $this->getPath()
        );

    }

    public function event(\Enlight_Event_EventArgs $args)
    {
        /** @var Zend_Db_Statement_Pdo $result */
        $result = $args->getReturn();

        return $result;
    }


    public function onPostDispatch(\Enlight_Controller_ActionEventArgs $args)
    {
        $view = $args->getSubject()->View();
        $session = $this->container->get('session');
        $basket = Shopware()->Modules()->Basket();
        if($session->get('sNotesQuantity')) {
            $view->assign('sNotesQuantity', $session->get('sNotesQuantity'));
        } else {
            $view->assign('sNotesQuantity',  $basket->sCountNotes());
        }
    }

    public function onBeforeSaveOrder(\Enlight_Hook_HookArgs $args)
    {
        /** @var \sOrder $subject */
        $subject = $args->getSubject();
        $subject->orderAttributes['meinattribut'] = '15. September';
    }

    private function createAttributes($field)
    {
        $crud = Shopware()->Container()->get('shopware_attribute.crud_service');

        $crud->update('s_articles_attributes', $field, 'string', [
            'translatable' => true,
            'displayInBackend' => true,
            'label' => $field
        ], null, true);
    }

}