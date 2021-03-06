<?php
/**
 * Acoes do modulo "Rate".
 *
 * =======================================
 * ###################################
 * MagnusBilling
 *
 * @package MagnusBilling
 * @author Adilson Leffa Magnus.
 * @copyright Copyright (C) 2005 - 2016 MagnusBilling. All rights reserved.
 * ###################################
 *
 * This software is released under the terms of the GNU Lesser General Public License v2.1
 * A copy of which is available from http://www.gnu.org/copyleft/lesser.html
 *
 * Please submit bug reports, patches, etc to https://github.com/magnusbilling/mbilling/issues
 * =======================================
 * Magnusbilling.com <info@magnusbilling.com>
 * 30/07/2012
 */

class RateCallshopController extends Controller
{
    public $attributeOrder = 't.id';

    public function init()
    {
        $this->instanceModel = new RateCallshop;
        $this->abstractModel = RateCallshop::model();
        $this->titleReport   = Yii::t('yii', 'Ratecard') . ' ' . Yii::t('yii', 'CallShop');
        parent::init();
    }

    public function actionSave()
    {
        $values = $this->getAttributesRequest();
        if (Yii::app()->session['isAdmin'] && (isset($values['id']) && $values['id'] == 0)) {
            echo json_encode(array(
                $this->nameSuccess => false,
                $this->nameMsg     => $this->msgError,
            ));
            exit;
        }
        parent::actionSave();
    }
}
