<?php
/**
 * @package     BBB Bastan
 * @copyright   Copyright (C) 2026 BastanGraphic. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Factory;

// Enable form validation and keep-alive for Joomla 5
/** @var Joomla\CMS\WebAsset\WebAssetManager $wa */
$wa = Factory::getApplication()->getDocument()->getWebAssetManager();
$wa->useScript('keepalive');
$wa->useScript('form.validate');

// Define the JavaScript function for toolbar buttons (save, cancel, etc.)
Factory::getApplication()->getDocument()->addScriptDeclaration("
    Joomla.submitbutton = function(task) {
        if (task === 'meeting.cancel' || document.formvalidator.isValid(document.getElementById('item-form'))) {
            Joomla.submitform(task, document.getElementById('item-form'));
        }
    };
");
?>
<form action="<?php echo Route::_('index.php?option=com_bbb_bastan&view=meeting&layout=edit&id=' . (int) $this->item->id); ?>" method="post" name="adminForm" id="item-form" class="form-validate">
    
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <!-- Render fields defined in the XML file -->
                    <?php echo $this->form->renderFieldset('details'); ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Hidden fields required for proper form functionality and security in Joomla -->
    <input type="hidden" name="task" value="">
    <?php echo HTMLHelper::_('form.token'); ?>
</form>