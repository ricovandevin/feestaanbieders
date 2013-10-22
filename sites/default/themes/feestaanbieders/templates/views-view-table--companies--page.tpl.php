<?php
/**
 * @file views-view-table.tpl.php
 * Template to display a view as a table.
 *
 * - $title : The title of this group of rows.  May be empty.
 * - $header: An array of header labels keyed by field id.
 * - $header_classes: An array of header classes keyed by field id.
 * - $fields: An array of CSS IDs to use for each field id.
 * - $classes: A class or classes to apply to the table, based on settings.
 * - $row_classes: An array of classes to apply to each row, indexed by row
 *   number. This matches the index in $rows.
 * - $rows: An array of row items. Each row is an array of content.
 *   $rows are keyed by row number, fields within rows are keyed by field ID.
 * - $field_classes: An array of classes to apply to each field, indexed by
 *   field id, then row number. This matches the index in $rows.
 * @ingroup views_templates
 */
?>
<ul class="search_results">
    <?php foreach ($rows as $row_count => $row): ?>
	<li class="search_result <?php print implode(' ', $row_classes[$row_count]); ?>">
        <div class="info_left fll">
            <p><a href="<?php print $row['path'];?>" rel="lightmodal"><?php print $row['field_logo'];?></a></p>
        </div>
        <div class="info_right fll">
            <ul>
              <?php if($row['field_type'] == 'pro'): ?>
            	<li class="title first"><a href="<?php print $row['path'];?>" rel="lightmodal"><?php print $row['title'];?></a><span class="pro_id"></span></li>
            	<?php endif; ?>
            	<li class="company_name"><?php print $row['field_company_name'];?></li>
            	<li class="categories"><?php print $row['field_categories'];?></li>
            	<li class="company_region last"><?php print $row['field_address'];?> (<?php print $row['field_region'];?>)</li>
            </ul>
            <span class="form-button-wrapper"><span class="btn_icon"></span><a href="<?php print $row['path'];?>" class="form-submit" rel="lightmodal">Meer Info</a></span>
        </div>
    </li>
    <?php endforeach; ?>
</ul>