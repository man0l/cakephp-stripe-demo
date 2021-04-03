<!-- File: src/Template/Users/index.php -->
<div class="users form">
    <?= $this->Flash->render() ?>
    <?= $this->Form->create() ?>
    <fieldset>
        <legend><?= __('Please add payment amount') ?></legend>
        <?= $this->Form->control('amount') ?>
    </fieldset>
    <?= $this->Form->button(__('Payment Amount')); ?>
    <?= $this->Form->end() ?>
</div>
