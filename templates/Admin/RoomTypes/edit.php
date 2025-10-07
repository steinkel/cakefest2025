<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\RoomType $roomType
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $roomType->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $roomType->id), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('List Room Types'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="roomTypes form content">
            <?= $this->Form->create($roomType) ?>
            <fieldset>
                <legend><?= __('Edit Room Type') ?></legend>
                <?php
                    echo $this->Form->control('type_name');
                    echo $this->Form->control('description');
                    echo $this->Form->control('max_occupancy');
                    echo $this->Form->control('base_price');
                    echo $this->Form->control('pets_allowed');
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
