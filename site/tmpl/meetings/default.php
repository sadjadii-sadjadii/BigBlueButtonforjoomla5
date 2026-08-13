<?php
/**
 * @package     BBB Bastan
 * @copyright   Copyright (C) 2026 BastanGraphic. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Router\Route;
use Joomla\CMS\Language\Text;
?>
<div class="bbb-meetings-container my-4">
    <h2 class="mb-4"><?php echo Text::_('COM_BBBBASTAN_MEETINGS_LIST_TITLE'); ?></h2>
    
    <?php if (empty($this->items)) : ?>
        <div class="alert alert-info"><?php echo Text::_('COM_BBBBASTAN_MEETINGS_NO_ACTIVE_CLASSES'); ?></div>
    <?php else : ?>
        <div class="table-responsive">
            <table class="table table-striped table-hover border">
                <thead class="table-light">
                    <tr>
                        <th><?php echo Text::_('COM_BBBBASTAN_MEETINGS_HEADING_CLASS_NAME'); ?></th>
                        <th width="15%" class="text-center"><?php echo Text::_('COM_BBBBASTAN_MEETINGS_HEADING_ACTIONS'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($this->items as $item) : ?>
                        <tr>
                            <td class="align-middle">
                                <strong><?php echo $this->escape($item->title); ?></strong>
                            </td>
                            <td class="text-center">
                                <!-- Join button: The link redirects to the join view -->
                                <a href="<?php echo Route::_('index.php?option=com_bbb_bastan&view=join&id=' . (int) $item->id); ?>" class="btn btn-success btn-sm">
                                    <?php echo Text::_('COM_BBBBASTAN_MEETINGS_ACTION_JOIN'); ?>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>