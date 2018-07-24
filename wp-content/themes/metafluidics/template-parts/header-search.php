<?php
/**
 * The header search area.
 *
 * The header code, a snippet of the Metafluidics theme header.php, which is displayed
 * on the top of the home and search pages pages.
 *
 */

  // get all the technology and skill tags
 $tagNames = array(
   'metafluidics_device_keywords',
   'metafluidics_device_technology',
   'metafluidics_device_material',
   'metafluidics_device_hardware'
 );

 $tagArgs = array(
   'orderby' => 'count',
   'order' => 'DESC',
   'hide_empty' => true,

 );

 $tagArray = get_terms($tagNames, $tagArgs);

 $maxTagsCount = 5;
 $maxTagsToShow = 25;

 $tagMax = ( count($tagArray) > $maxTagsToShow ) ? $maxTagsToShow : count($tagArray);
 $seeHiddenClass = ( $tagMax <= $maxTagsCount ) ? ' hidden' : '';
?>

<div id="call-to-search" class="tool-area">

  <form role="search" method="get" id="searchform" class="searchform" action="<?php echo esc_url( home_url('/') ); ?>">
    <label for="s">What part do you want to make? <input type="text" placeholder="Search" name="s" /></label>
  </form>

  <ul>

    <?php
      for ( $i = 0; $i < $tagMax; $i++ ) {
        // show list item for each tag, hide all but the first 5
        $openLI = ( $i > 4 ) ? '<li class="hidden">' : '<li>';

        // get taxonomy slug
        switch ( $tagArray[$i]->taxonomy ) {
          case 'metafluidics_device_keywords' :
            $taxSlug = 'device-keywords';
            break;
          case 'metafluidics_device_technology' :
            $taxSlug = 'device-technology';
            break;
          case 'metafluidics_device_material' :
            $taxSlug = 'device-material';
            break;
          case 'metafluidics_device_hardware' :
            $taxSlug = 'device-hardware';
            break;
          default:
            $taxSlug = 'tag';
        }

        // display tag item
        echo $openLI . '<a href="' . site_url() . '/' . $taxSlug . '/' . $tagArray[$i]->slug . '">' . $tagArray[$i]->name . '</a></li>';
      }
    ?>
    <li class="show see-more-tags<?php echo $seeHiddenClass; ?>"><a href="#">Show More</a></li>
    <li class="show hide-more-tags hidden"><a href="#">Show Less</a></li>
  </ul>

</div>
