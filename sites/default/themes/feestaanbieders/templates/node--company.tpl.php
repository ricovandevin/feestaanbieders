<?php

/**
 * @file
 * Default theme implementation to display a node.
 *
 * Available variables:
 * - $title: the (sanitized) title of the node.
 * - $content: An array of node items. Use render($content) to print them all,
 *   or print a subset such as render($content['field_example']). Use
 *   hide($content['field_example']) to temporarily suppress the printing of a
 *   given element.
 * - $user_picture: The node author's picture from user-picture.tpl.php.
 * - $date: Formatted creation date. Preprocess functions can reformat it by
 *   calling format_date() with the desired parameters on the $created variable.
 * - $name: Themed username of node author output from theme_username().
 * - $node_url: Direct url of the current node.
 * - $display_submitted: Whether submission information should be displayed.
 * - $submitted: Submission information created from $name and $date during
 *   template_preprocess_node().
 * - $classes: String of classes that can be used to style contextually through
 *   CSS. It can be manipulated through the variable $classes_array from
 *   preprocess functions. The default values can be one or more of the
 *   following:
 *   - node: The current template type, i.e., "theming hook".
 *   - node-[type]: The current node type. For example, if the node is a
 *     "Blog entry" it would result in "node-blog". Note that the machine
 *     name will often be in a short form of the human readable label.
 *   - node-teaser: Nodes in teaser form.
 *   - node-preview: Nodes in preview mode.
 *   The following are controlled through the node publishing options.
 *   - node-promoted: Nodes promoted to the front page.
 *   - node-sticky: Nodes ordered above other non-sticky nodes in teaser
 *     listings.
 *   - node-unpublished: Unpublished nodes visible only to administrators.
 * - $title_prefix (array): An array containing additional output populated by
 *   modules, intended to be displayed in front of the main title tag that
 *   appears in the template.
 * - $title_suffix (array): An array containing additional output populated by
 *   modules, intended to be displayed after the main title tag that appears in
 *   the template.
 *
 * Other variables:
 * - $node: Full node object. Contains data that may not be safe.
 * - $type: Node type, i.e. story, page, blog, etc.
 * - $comment_count: Number of comments attached to the node.
 * - $uid: User ID of the node author.
 * - $created: Time the node was published formatted in Unix timestamp.
 * - $classes_array: Array of html class attribute values. It is flattened
 *   into a string within the variable $classes.
 * - $zebra: Outputs either "even" or "odd". Useful for zebra striping in
 *   teaser listings.
 * - $id: Position of the node. Increments each time it's output.
 *
 * Node status variables:
 * - $view_mode: View mode, e.g. 'full', 'teaser'...
 * - $teaser: Flag for the teaser state (shortcut for $view_mode == 'teaser').
 * - $page: Flag for the full page state.
 * - $promote: Flag for front page promotion state.
 * - $sticky: Flags for sticky post setting.
 * - $status: Flag for published status.
 * - $comment: State of comment settings for the node.
 * - $readmore: Flags true if the teaser content of the node cannot hold the
 *   main body content.
 * - $is_front: Flags true when presented in the front page.
 * - $logged_in: Flags true when the current user is a logged-in member.
 * - $is_admin: Flags true when the current user is an administrator.
 *
 * Field variables: for each field instance attached to the node a corresponding
 * variable is defined, e.g. $node->body becomes $body. When needing to access
 * a field's raw values, developers/themers are strongly encouraged to use these
 * variables. Otherwise they will have to explicitly specify the desired field
 * language, e.g. $node->body['en'], thus overriding any language negotiation
 * rule that was previously applied.
 *
 * @see template_preprocess()
 * @see template_preprocess_node()
 * @see template_process()
 */
?>
<?php if(!$page): ?>
<div id="node-<?php print $node->nid; ?>" class="<?php print $classes; ?> clearfix"<?php print $attributes; ?>>

  <?php print $user_picture; ?>

  <?php print render($title_prefix); ?>
  <?php if (!$page): ?>
    <h2<?php print $title_attributes; ?>><a href="<?php print $node_url; ?>"><?php print $title; ?></a></h2>
  <?php endif; ?>
  <?php print render($title_suffix); ?>

  <?php if ($display_submitted): ?>
    <div class="submitted">
      <?php print $submitted; ?>
    </div>
  <?php endif; ?>

  <div class="content"<?php print $content_attributes; ?>>
    <?php
      // We hide the comments and links now so that we can render them later.
      hide($content['comments']);
      hide($content['links']);
      print render($content);
    ?>
  </div>

  <?php print render($content['links']); ?>

  <?php print render($content['comments']); ?>

</div>
<?php else: ?>

	
    <div class="search_result_overlay"><div class="section block_format">
  
        <div class="column first">
        	<div class="block_header"><span>Recensies<span><!-- (3)--></span></span></div>
        	
            <div><span class="form-button-wrapper btn_action-in"><span class="btn_icon"></span><input type="submit" value="Schrijf je eigen review" class="form-submit" onclick="jQuery('.comment-form').show();"></span></div>
            <div class="reviews">
                <?php print render($content['comments']); ?>
                <!--
                <div class="review">
                    <ul class="score"><li class="first">Beoordeling:</li>
                    <li><span></span><span></span><span></span><span></span><span></span></li></ul>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum facilisis vestibulum tincidunt. Donec aliquam accumsan nisi vitae auctor. Sed turpis nisi, fringilla id ultricies vitae, scelerisque aliquet enim.</p>
                    <span class="divider"></span>
                </div>

                <div class="review">
                    <ul class="score"><li class="first">Beoordeling:</li>
                    <li><span></span><span></span><span></span><span></span><span></span></li></ul>
                    <p>Donec convallis erat nec dui feugiat non eleifend leo molestie. Suspendisse magna lectus, ullamcorper sit amet venenatis blandit, faucibus et justo. Praesent vel lectus sem, et rutrum ligula. </p>
                    <span class="divider"></span>              
                </div>

                <div class="review">
                    <ul class="score"><li class="first">Beoordeling:</li>
                    <li><span></span><span></span><span></span><span></span><span></span></li></ul>
                    <p>Vivamus eros mi, interdum vitae fermentum molestie, laoreet eget orci. Donec accumsan, mauris sed feugiat elementum, nibh quam tristique elit, id aliquet quam tellus fringilla tellus.</p> 
                    <span class="divider"></span>                           
                </div>                
                -->
                <?php
                    unset($content['links']['statistics']);
                    $links = $content['links'];
                    unset($content['links']['flag']['#links']['flag-abusive']);
                    unset($content['links']['flag']['#links']['flag-obsolete']);
                    unset($content['links']['flag']['#links']['flag-deadlink']);
                ?>
        	<div class="block_header"><span>Mijn selectie</span></div>
                <?php print render($content['links']); ?>
                <?php
                    $content['links'] = $links;
                    unset($content['links']['flag']['#links']['flag-bookmarks'])
                ?>
            </div>
            
        </div>
    
        <div class="column">
        	<div class="block_header"><?php print render($content['field_company_name']); ?></div>
        	<div class="block_header title"><?php print $title; ?></div>
            <div class="block_header categories"><?php print render ($content['field_categories']);?></div>
                <p><?php print render($content['body']); ?></p>
                <?php print render($content['field_pictures']); ?>
        
                <?php //print_r($content['links']); ?>
        	<!--<div><span class="form-button-wrapper btn_default"><span class="btn_icon"></span><input type="submit" value="Toevoegen aan je selectie" class="form-submit"></span></div>-->
        </div>
        
        <div class="column last">
        	<div class="block_header"><span>Delen</span></div>
            <div class="social_buttons">
                <!-- Social Media -->
                <!-- AddThis Button BEGIN
                <div class="addthis_toolbox addthis_default_style">
                <a class="addthis_button_google_plusone" g:plusone:size="tall"></a>
                <a class="addthis_button_tweet" tw:count="vertical"></a>
                <a class="addthis_button_facebook_like" fb:like:layout="box_count"></a>
                <iframe src="http://www.hyves.nl/respect/button?url=http://feestaanbiedersnl.hyves.nl&counterStyle=vertical" style="border: medium none; overflow:hidden; width:70px; height:80px;" scrolling="no" frameborder="0" ></iframe>
                </div>
                <script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=ra-4e4e44901d46a0f3"></script>
                AddThis Button END -->
        
                <!-- AddThis Button BEGIN -->
                <div id="addthis-toolbox" class="addthis_toolbox addthis_default_style ">
                    <a class="addthis_button_preferred_1"></a>
                    <a class="addthis_button_preferred_2"></a>
                    <a class="addthis_button_preferred_4"></a>
                    <a class="addthis_button_hyves"></a>
                    <a class="addthis_button_google_plusone" g:plusone:size="small" g:plusone:annotation="none"></a>
                    <a class="addthis_button_compact"></a>
                    <a class="addthis_counter addthis_bubble_style"></a>
                    <a class="addthis_button_preferred_3"></a>
                </div>
                <!-- AddThis Button END -->
			</div>
        	<div class="block_header"><span>Neem contact op</span></div>
                <?php print render($content['field_address']); ?>
                <?php print render($content['field_phone']); ?>
                <?php print render($content['field_fax']); ?>
                <?php print render($content['field_email']); ?>
                <?php print render($content['field_link']); ?>
                <?php print render($content['field_location']); ?>
<!--
            <form accept-charset="UTF-8" id="custom-contact-form" method="post" action="#">
                <div>
                    <div class="form-item form-type-textfield form-item-name">
                        <label for="edit-name">Voornaam <span title="Dit veld is verplicht." class="form-required">*</span></label>
                        <span class="form-input-wrapper"><input type="text" class="form-text required" maxlength="60" size="15" value="" name="name" id="edit-name"></span>
                    </div>
        
                    <div class="form-item form-type-textfield form-item-surname">
                        <label for="edit-name">Achternaam <span title="Dit veld is verplicht." class="form-required">*</span></label>
                        <span class="form-input-wrapper"><input type="text" class="form-text required" maxlength="60" size="15" value="" name="name" id="edit-name"></span>
                    </div>
        
                    <div class="form-item form-type-textfield form-item-telnr">
                        <label for="edit-name">Telefoonnummer</label>
                        <span class="form-input-wrapper"><input type="text" class="form-text required" maxlength="60" size="15" value="" name="name" id="edit-name"></span>
                    </div>
        
                    <div class="form-item form-type-textfield form-item-email">
                        <label for="edit-name">Emailadres <span title="Dit veld is verplicht." class="form-required">*</span></label>
                        <span class="form-input-wrapper"><input type="text" class="form-text required" maxlength="60" size="15" value="" name="name" id="edit-name"></span>
                    </div>

                	<p>Het formulier wordt verzonden naar de getoonde aanbieder. Reacties zullen dan ook via de desbetreffende aanbieder gaan.</p>      

                    <div id="edit-actions" class="form-actions form-wrapper">
                        <div><span class="form-button-wrapper btn_action-out"><span class="btn_icon"></span><input type="submit" value="Neem contact op" class="form-submit"></span></div>
                    </div>
                </div>
            </form>
-->
        </div>    
        
            <div class="quality">
        	<div class="block_header"><span>Kwaliteitsbewaking</span></div>
  <?php print render($content['links']); ?><a href="/kwaliteitsbewaking"><span class="icon info"></span><span class="hidden-info">Over kwaliteitsbewaking</span></a></div>
        </div></div> <!-- /.section, /.search_result_overlay -->
<?php endif; ?>