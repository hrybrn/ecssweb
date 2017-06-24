<div id="event" title="<?php p($l->t("Create a new event"));?>">
	<form id="event_form">
<?php print_unescaped($this->inc("part.eventform")); ?>
	<div style="width: 100%;text-align: center;color: #FF1D1D;" id="errorbox"></div>
	<div id="actions">
		<input type="button" id="submitNewEvent" class="submit actionsfloatright primary"
			data-link="<?php print_unescaped(OCP\Util::linkTo('calendar', 'ajax/event/new.php')); ?>"
			value="<?php p($l->t('Create event'));?>">
	</div>
	</form>
</div>
