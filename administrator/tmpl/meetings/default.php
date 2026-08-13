<?php
/**
 * @package     BBB Bastan
 * @copyright   Copyright (C) 2026 BastanGraphic. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Language\Text;

?>
<form action="<?php echo Route::_('index.php?option=com_bbb_bastan&view=meetings'); ?>" method="post" name="adminForm" id="adminForm">    
    <div class="table-responsive">
        <table class="table table-striped table-hover" id="meetingList">
            <thead>
                <tr>
                    <th width="1%" class="text-center">
                        <?php echo HTMLHelper::_('grid.checkall'); ?>
                    </th>
                    <th><?php echo Text::_('COM_BBBBASTAN_MEETINGS_HEADING_TITLE'); ?></th>
                    <th width="15%"><?php echo Text::_('COM_BBBBASTAN_MEETINGS_HEADING_MEETING_ID'); ?></th>
                    <th width="10%"><?php echo Text::_('COM_BBBBASTAN_MEETINGS_HEADING_STATUS'); ?></th>
                    <th width="1%" class="text-nowrap">ID</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($this->items)) : ?>
                    <?php foreach ($this->items as $i => $row) : ?>
                        <tr class="row<?php echo $i % 2; ?>">
                            <td class="text-center">
                                <?php echo HTMLHelper::_('grid.id', $i, $row->id); ?>
                            </td>
                            <td>
                                <!-- A link to the edit form will be added here later -->
                                <?php echo $this->escape($row->title); ?>
                            </td>
                            <td><?php echo $this->escape($row->meeting_id); ?></td>
                            <td class="text-center">
                                <?php echo $row->state == 1 ? '<span class="badge bg-success">' . Text::_('COM_BBBBASTAN_ACTIVE') . '</span>' : '<span class="badge bg-danger">' . Text::_('COM_BBBBASTAN_INACTIVE') . '</span>'; ?>
                            </td>
                            <td><?php echo $row->id; ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="5" class="text-center">
                            <?php echo Text::_('COM_BBBBASTAN_MEETINGS_NO_RECORDS_FOUND'); ?>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- These hidden fields are required for the correct functionality of the toolbar buttons -->
    <input type="hidden" name="task" value="">
    <input type="hidden" name="boxchecked" value="0">
    <?php echo HTMLHelper::_('form.token'); ?>
</form>