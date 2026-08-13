<?php
/**
 * @package     BBB Bastan
 * @copyright   Copyright (C) 2026 BastanGraphic. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Router\Route;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

$user = Factory::getApplication()->getIdentity();
$isGuest = $user->guest;
?>
<div class="bbb-join-container my-5" style="max-width: 500px; margin: auto;">
    <?php if (!empty($this->item->image)) : ?>
        <div class="mb-4 text-center">
            <img src="<?php echo $this->escape(\Joomla\CMS\Uri\Uri::root() . $this->item->image); ?>" 
                alt="<?php echo $this->escape($this->item->title); ?>" 
                class="img-fluid rounded shadow-sm" 
                style="max-height: 250px; object-fit: cover; width: 100%;">
        </div>
    <?php endif; ?>    
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white text-center py-3">
            <h4 class="mb-0"><?php echo Text::_('COM_BBBBASTAN_JOIN_CLASS_TITLE') . ' ' . $this->escape($this->item->title); ?></h4>
        </div>
        <div class="card-body p-4">
            <form action="<?php echo Route::_('index.php?option=com_bbb_bastan&task=join.meeting'); ?>" method="post">
                <input type="hidden" name="id" value="<?php echo $this->item->id; ?>">

                <?php if ($isGuest) : ?>
                    <div class="mb-3">
                        <label class="form-label fw-bold"><?php echo Text::_('COM_BBBBASTAN_JOIN_FULL_NAME_LABEL'); ?></label>
                        <input type="text" name="join_name" class="form-control" required placeholder="<?php echo Text::_('COM_BBBBASTAN_JOIN_FULL_NAME_PLACEHOLDER'); ?>">
                    </div>
                <?php else : ?>
                    <div class="alert alert-info text-center">
                        <?php echo Text::sprintf('COM_BBBBASTAN_JOIN_LOGGED_IN_AS', $this->escape($user->name)); ?>
                    </div>
                    <input type="hidden" name="join_name" value="<?php echo $this->escape($user->name); ?>">
                <?php endif; ?>

                <div class="mb-4">
                    <label class="form-label fw-bold"><?php echo Text::_('COM_BBBBASTAN_JOIN_PASSWORD_LABEL'); ?></label>
                    <input type="password" name="join_password" class="form-control text-center" required placeholder="<?php echo Text::_('COM_BBBBASTAN_JOIN_PASSWORD_PLACEHOLDER'); ?>">
                    <div class="form-text text-muted mt-2 text-center" style="font-size: 0.85rem;">
                        <?php echo Text::_('COM_BBBBASTAN_JOIN_PASSWORD_HELP'); ?>
                    </div>
                </div>

                <button type="submit" class="btn btn-success w-100 py-2 fs-5"><?php echo Text::_('COM_BBBBASTAN_JOIN_SUBMIT_BUTTON'); ?></button>
                <?php echo HTMLHelper::_('form.token'); ?>
            </form>
        </div>
    </div>
</div>