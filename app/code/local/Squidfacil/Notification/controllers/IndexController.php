<?php
class Squidfacil_Notification_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
        Mage::log($this->getRequest()->getParams());
    }
}