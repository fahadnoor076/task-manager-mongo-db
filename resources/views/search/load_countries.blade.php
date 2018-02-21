<?php if(isset($record_ids[0])): ?>

    <div class="block-header bg-gray-lighter">
      <ul class="block-options">
        <li>
          <button type="button" data-toggle="block-option" data-action="content_toggle"><i class="si si-arrow-up"></i></button>
        </li>
      </ul>
      <h3 class="block-title">Countries</h3>
    </div>
    <div class="block-content">
      <div class="col-xs-12">
        <?php if(isset($record_ids[0])): ?>
        <div class="slimScrollDiv">
          <?php foreach($record_ids as $record_id): ?>
          <?php $record = $country_model->get($record_id->country_id); ?>
          <label class="css-input css-checkbox css-checkbox-rounded css-checkbox-sm css-checkbox-info" for="country_<?php echo $record->country_id; ?>">
            <input id="country_<?php echo $record->country_id; ?>" name="countries[]" type="checkbox" value="<?php echo $record->country_id; ?>">
            <span></span> <?php echo $record->country; ?> </label>
          <br clear="all" />
          <?php endforeach; ?>
        </div>
        <?php endif; ?>
      </div>
    </div>
    <br clear="all" />
<?php else: ?>&nbsp;<?php endif; ?>