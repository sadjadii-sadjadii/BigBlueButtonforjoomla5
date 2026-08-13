<?php
/**
 * @package     BBB Bastan
 * @copyright   Copyright (C) 2026 BastanGraphic. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;

HTMLHelper::_('behavior.multiselect');
?>
<form action="index.php?option=com_bbb_bastan&view=logs" method="post" name="adminForm" id="adminForm">
    
    <!-- Search bar and items per page selection -->
    <?php echo LayoutHelper::render('joomla.searchtools.default', ['view' => $this]); ?>

    <?php if (empty($this->items)) : ?>
        <div class="alert alert-info"><?php echo Text::_('COM_BBBBASTAN_LOGS_NO_RECORDS_FOUND'); ?></div>
    <?php else : ?>    
        <div class="table-responsive">
            <table class="table table-striped table-hover" id="logList">
                <thead>
                    <tr>
                        <!-- Select all checkbox -->
                        <th width="1%" class="text-center">
                            <?php echo HTMLHelper::_('grid.checkall'); ?>
                        </th>
                        <th width="1%">#</th>
                        <th><?php echo Text::_('COM_BBBBASTAN_LOGS_HEADING_MEETING_NAME'); ?></th>
                        <th><?php echo Text::_('COM_BBBBASTAN_LOGS_HEADING_USER_NAME'); ?></th>
                        <th><?php echo Text::_('COM_BBBBASTAN_LOGS_HEADING_JOIN_TIME'); ?></th>
                        <th><?php echo Text::_('COM_BBBBASTAN_LOGS_HEADING_LEAVE_TIME'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($this->items as $i => $item) : ?>
                        <tr>
                            <!-- Checkbox for each row -->
                            <td class="text-center">
                                <?php echo HTMLHelper::_('grid.id', $i, $item->id); ?>
                            </td>
                            <td><?php echo $i + 1; ?></td>
                            <td><?php echo $this->escape($item->meeting_title); ?></td>
                            <td>
                                <?php 
                                // Display user or guest
                                echo $item->user_id == 0 ? '<span class="badge bg-secondary">' . Text::_('COM_BBBBASTAN_LOGS_GUEST_USER') . '</span>' : $this->escape($item->user_name); 
                                ?>
                            </td>
                            <td dir="ltr" class="text-end"><?php echo HTMLHelper::_('date', $item->join_time, 'Y-m-d H:i:s'); ?></td>
                            <td dir="ltr" class="text-end"><?php echo HTMLHelper::_('date', $item->leave_time, 'Y-m-d H:i:s'); ?></td>      
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <!-- Display pagination -->
                        <td colspan="6">
                            <?php echo $this->pagination->getListFooter(); ?>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    <?php endif; ?>
    
    <!-- Hidden fields for proper form and delete button functionality -->
    <input type="hidden" name="task" value="">
    <input type="hidden" name="boxchecked" value="0">
    <?php echo HTMLHelper::_('form.token'); ?>
</form>