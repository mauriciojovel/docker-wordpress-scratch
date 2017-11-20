<?php
/**
 * Template part for displaying posts (Blog)
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package ClientTheme
 */

?>

<article class="section mb-8">
<div class="container">
  <div class="row">
    <div class="col col-6">
      <h1><a href="<?php the_permalink(); ?>" class="red"><?php the_title(); ?></a></h1>
      <p><?php the_excerpt(); ?></p>
      <a href="<?php the_permalink(); ?>" class="button button--primary button--small"><?php __('Read more', 'client-theme') ?></a>
    </div>
    <div class="col col-6">
      <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('full', array('class' => 'img-fluid')); ?></a>
    </div>
  </div>
</div>
</article>
