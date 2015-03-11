<?php if ( $num_image_generation_queue_items > 0 ): ?>
	<?php foreach ( $image_generation_queue_items as $i => $image_generation_queue_item ): ?>
		<?php
		$reference = $image_generation_queue_item->getReference();
		$title = $image_generation_queue_item->getReferenceTitle($reference);
		$type = $image_generation_queue_item->getType();
		?>

		<div data-id="<?= $image_generation_queue_item->id ?>" data-title="<?= $title ?>" data-type="<?= $type ?>" class="image-generation-queue-item">
			<strong class="image-generation-queue-item-type"><?= $type ?></strong>

			<a href="<?= $image_generation_queue_item->getReferenceURL($reference) ?>" class="image-generation-queue-item-reference"><?= $title ?></a>

			<div class="image-generation-queue-item-status-row"><strong>Status:</strong> <?= $image_generation_queue_item->getStatus() ?></div>

			<span class="image-generation-queue-item-added-container"><span class="added" data-livestamp="<?= $image_generation_queue_item->getAddedUnix() ?>">Loading...</span></span>

			<?php if ( $image_generation_queue_item->isPending() ): ?>
				<a href="javascript:" class="ui button mini cancel">Cancel</a>
			<?php else: ?>
				<a href="javascript:" class="ui button mini abort">Abort</a>
			<?php endif ?>

			<?php if ( $i < ($num_image_generation_queue_items - 1) ): ?>
				<hr>
			<?php endif ?>
		</div>
	<?php endforeach ?>
<?php else: ?>
	No images in queue.
<?php endif ?>