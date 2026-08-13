<?php
/**
 * @package     BBB Bastan
 * @copyright   Copyright (C) 2026 BastanGraphic. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
?>
<form action="index.php?option=com_bbb_bastan&view=logs" method="post" name="adminForm" id="adminForm">
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th>#</th>
                <th><?php echo Text::_('COM_BBBBASTAN_LOGS_HEADING_MEETING_NAME'); ?></th>
                <th><?php echo Text::_('COM_BBBBASTAN_LOGS_HEADING_USER_NAME'); ?></th>
                <th><?php echo Text::_('COM_BBBBASTAN_LOGS_HEADING_JOIN_TIME'); ?></th>
                <th><?php echo Text::_('COM_BBBBASTAN_LOGS_HEADING_LEAVE_TIME'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($this->items)) : ?>
                <?php foreach ($this->items as $i => $item) : ?>
                    <tr>
                        <td><?php echo $i + 1; ?></td>
                        <td><?php echo $this->escape($item->meeting_title); ?></td>
                        <td>
                            <?php 
                            // If the user is not logged in (ID is 0), display as guest
                            echo $item->user_id == 0 ? '<span class="badge bg-secondary">' . Text::_('COM_BBBBASTAN_LOGS_GUEST_USER') . '</span>' : $this->escape($item->user_name); 
                            ?>
                        </td>
                        <td dir="ltr" class="text-end"><?php echo HTMLHelper::_('date', $item->join_time, 'Y-m-d H:i:s'); ?></td>
                        <td dir="ltr" class="text-end"><?php echo HTMLHelper::_('date', $item->leave_time, 'Y-m-d H:i:s'); ?></td>      
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="5" class="text-center"><?php echo Text::_('COM_BBBBASTAN_LOGS_NO_RECORDS_FOUND'); ?></td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    
    <?php echo $this->pagination->getListFooter(); ?>
    <input type="hidden" name="task" value="">
    <?php echo HTMLHelper::_('form.token'); ?>
</form>