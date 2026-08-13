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
use Joomla\CMS\Layout\LayoutHelper;

// Load behaviors for proper checkbox functionality
HTMLHelper::_('behavior.multiselect');
?>
<form action="<?php echo Route::_('index.php?option=com_bbb_bastan&view=meetings'); ?>" method="post" name="adminForm" id="adminForm">    
    
    <!-- Search bar and items per page selection -->
    <?php echo LayoutHelper::render('joomla.searchtools.default', ['view' => $this]); ?>

    <?php if (empty($this->items)) : ?>
        <div class="alert alert-info">
            <?php echo Text::_('COM_BBBBASTAN_MEETINGS_NO_RECORDS_FOUND'); ?>
        </div>
    <?php else : ?>
        <div class="table-responsive">
            <table class="table table-striped table-hover" id="meetingList">
                <thead>
                    <tr>
                        <th width="1%" class="text-center">
                            <?php echo HTMLHelper::_('grid.checkall'); ?>
                        </th>
                        <th><?php echo Text::_('COM_BBBBASTAN_MEETINGS_HEADING_TITLE'); ?></th>
                        <th width="15%"><?php echo Text::_('COM_BBBBASTAN_MEETINGS_HEADING_MEETING_ID'); ?></th>
                        <th width="10%" class="text-center"><?php echo Text::_('COM_BBBBASTAN_MEETINGS_HEADING_STATUS'); ?></th>
                        <th width="1%" class="text-nowrap">ID</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($this->items as $i => $row) : ?>
                        <tr class="row<?php echo $i % 2; ?>">
                            <td class="text-center">
                                <?php echo HTMLHelper::_('grid.id', $i, $row->id); ?>
                            </td>
                            <td>
                                <!-- Link to the class edit form -->
                                <a href="<?php echo Route::_('index.php?option=com_bbb_bastan&task=meeting.edit&id=' . (int) $row->id); ?>">
                                    <?php echo $this->escape($row->title); ?>
                                </a>
                            </td>
                            <td><?php echo $this->escape($row->meeting_id); ?></td>
                            <td class="text-center">
                                <?php echo $row->state == 1 ? '<span class="badge bg-success">' . Text::_('COM_BBBBASTAN_ACTIVE') . '</span>' : '<span class="badge bg-danger">' . Text::_('COM_BBBBASTAN_INACTIVE') . '</span>'; ?>
                            </td>
                            <td><?php echo $row->id; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <!-- Pagination -->
                        <td colspan="5">
                            <?php echo $this->pagination->getListFooter(); ?>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    <?php endif; ?>

    <!-- Hidden fields for proper toolbar button functionality -->
    <input type="hidden" name="task" value="">
    <input type="hidden" name="boxchecked" value="0">
    <?php echo HTMLHelper::_('form.token'); ?>
</form>