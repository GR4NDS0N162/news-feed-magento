<?php

declare(strict_types=1);

namespace Oggetto\MultipleChoice\Observer;

use Magento\Config\Model\Config\Source\Yesno;
use Magento\Framework\Data\Form;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class AddFieldsToAttributeObserver implements ObserverInterface
{
    /**
     * @var Yesno
     */
    protected Yesno $optionList;

    /**
     * @param Yesno $optionList
     */
    public function __construct(
        Yesno $optionList,
    ) {
        $this->optionList = $optionList;
    }

    /**
     * @inheritDoc
     */
    public function execute(Observer $observer)
    {
        /** @var Form $form */
        $form = $observer->getForm();
        $fieldset = $form->getElement('front_fieldset');

        $fieldset->addField(
            'allow_multiple_filtering',
            'select',
            [
                'name'   => 'allow_multiple_filtering',
                'label'  => __('Allow multiple filtering'),
                'title'  => __('Allow multiple filtering'),
                'note'   => __('Can be used only with catalog input type Yes/No, Dropdown, Multiple Select and Price.'),
                'values' => $this->optionList->toOptionArray(),
            ],
            'position'
        );
    }
}
