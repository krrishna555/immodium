<?php
// $Id: search-results.tpl.php,v 1.1 2007/10/31 18:06:38 dries Exp $

/**
 * @file search-results.tpl.php
 * Default theme implementation for displaying search results.
 *
 * This template collects each invocation of theme_search_result(). This and
 * the child template are dependant to one another sharing the markup for
 * definition lists.
 *
 * Note that modules may implement their own search type and theme function
 * completely bypassing this template.
 *
 * Available variables:
 * - $search_results: All results as it is rendered through
 *   search-result.tpl.php
 * - $type: The type of search, e.g., "node" or "user".
 *
 *
 * @see template_preprocess_search_results()
 */
global $pager_page_array, $pager_total, $pager_total_items;

//establish page variables
  $total_pages = $pager_total['0'];
  $total_page_count = $total_pages - 1;
  //approx number of results based on page count
  $approx_results = $total_pages * 10;
  //calculate range
  $page_number = $pager_page_array['0'];
  $start_result = $page_number * 10 + 1;
  $end_result = $page_number * 10 + 10;

?>
<div class="search-descContainer">
<div class="search-pager">
<?php print $pager; ?>
</div>
<?php //print '<pre>'. print_r($pager_total_items) .'</pre>'; ?>
<div id="searchInfoDiv"><span class="search-results-title">Results</span>(<?php print $start_result; ?>-<?php print $end_result; ?> of <?php print $pager_total_items[0]; ?>)</div>
<dl class="search-results <?php print $type; ?>-results">
  <?php print $search_results; ?>
</dl>
<div class="search-pager">
<?php print $pager; ?>
</div>
</div>