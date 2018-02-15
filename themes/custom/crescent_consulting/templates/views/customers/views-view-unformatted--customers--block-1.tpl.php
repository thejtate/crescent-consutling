<?php

/**
 * @file
 * Default simple view template to display a list of rows.
 *
 * @ingroup views_templates
 */
?>
<section class="section-slider-navigation full-block">
  <div class="slider-nav">
    <?php foreach ($rows as $id => $row): ?>
      <div><?php print $row; ?></div>
    <?php endforeach; ?>
  </div>
  <div class=""></div>
</section>