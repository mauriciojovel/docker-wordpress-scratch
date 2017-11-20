<?php
/**
 * Template part for displaying posts (Blog)
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package ClientThemeBootstrap
 */

?>
<main role="main" class="container">
    <div class="starter-template">
    <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('full', array('class' => 'img-fluid')); ?></a>
    <h1><a href="<?php the_permalink(); ?>" class="red"><?php the_title(); ?></a></h1>
      <p><?php the_excerpt(); ?></p>
      <a href="<?php the_permalink(); ?>" class="btn btn-primary"><?php echo __('Read more', 'client-theme-bootstrap') ?></a>
    </div>
</main><!-- /.container -->
