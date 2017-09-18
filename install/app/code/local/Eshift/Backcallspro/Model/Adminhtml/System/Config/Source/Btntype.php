<?php
class Eshift_Backcallspro_Model_Adminhtml_System_Config_Source_Btntype
{

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            array('value' => 'a', 'label'=>Mage::helper('adminhtml')->__('Link')),
            array('value' => 'button', 'label'=>Mage::helper('adminhtml')->__('Button')),
        );
    }

}
