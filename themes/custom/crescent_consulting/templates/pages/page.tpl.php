<?php
$right_sidebar = render($page['right_sidebar']);
$enviroedge_button = isset($enviroedge_button) ? $enviroedge_button : '';
$top_image = isset($top_image) ? $top_image : '';
?>


<div class="outer-wrapper">

  <header id="site-header" class="site-header">
    <div class="container">
      <?php if ($logo): ?>
        <div class="logo">
          <a href="<?php print $front_page; ?>"
             title="<?php print t('Home'); ?>"
             rel="home" id="logo">
            <img src="<?php print $logo; ?>"
                 alt="<?php print t('Home'); ?>"/>
          </a>
        </div>
      <?php endif; ?>

      <div class="right-part">
        <nav class="nav">
          <?php print render($page['header']); ?>
        </nav>
        <!--        --><?php //print $enviroedge_button; ?>
      </div>
      <a href="" class="btn-mobile"><span></span></a>
    </div>
  </header>

  <div class="inner-wrapper">

    <?php if ($top_image): ?>
      <div class="top-media">
        <?php print render($title_prefix); ?>
        <?php if ($title): ?>
          <div class="container">
            <h1 id="page-title"><?php print $title;?></h1>
          </div>
        <?php endif; ?>
        <?php print render($title_suffix); ?>

        <div class='bg-img'
             style="background-image: url('<?php print render($top_image); ?>')"
             data-bottom-top="transform:translate3d(0px, -150px, 0px)"
             data-top-bottom="transform:translate3d(0px, 150px, 0px)">
        </div>
      </div>
    <?php endif; ?>
    <?php if ($top_media = render($page['top_media'])): ?>
      <?php print $top_media; ?>
    <?php endif; ?>

    <?php print render($page['content_top']); ?>
    <?php if ($messages = render($messages)): ?>
      <?php print $messages; ?>
    <?php endif; ?>
    <?php if ($tabs = render($tabs)): ?>
      <div class="tabs">
        <?php print $tabs; ?>
      </div>
    <?php endif; ?>

    <div
      class="content-wrapper container <?php print ($right_sidebar) ? 'with-sidebar' : ''; ?>">

      <?php print render($page['content']); ?>

      <?php if ($right_sidebar): ?>
        <aside class="sidebar">
          <?php print $right_sidebar; ?>
        </aside>
      <?php endif; ?>

    </div>
    <?php print render($page['customers_logo']); ?>
    <?php print render($page['content_bottom']); ?>
  </div>

  <footer id="site-footer" class="site-footer">
    <div class="container">
      <div class="logo"><a href="<?php print url('<front>'); ?>"><img
            src="<?php print base_path() . path_to_theme(); ?>/images/logo-s.png"
            alt=""></a>
      </div>

      <?php if (isset($footer_text) && $footer_text): ?>
        <span><?php print $footer_text; ?></span>
      <?php endif; ?>

      <?php if (isset($footer_menu) && $footer_menu): ?>
        <div class="menu">
          <?php print $footer_menu; ?>
        </div>
      <?php endif; ?>

      <?php print render($page['footer']); ?>
    </div>
  </footer>
</div>