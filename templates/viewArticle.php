<?php include "templates/include/header.php" ?>
<div style="width:75%"> 
      <h1><?php echo htmlspecialchars( $results['article']->title )?></h1>
      <div style="font-style: italic;"><?php echo htmlspecialchars( $results['article']->summary )?></div>

      <p class="pubDate"><?php echo date('j F Y', $results['article']->publicationDate)?></p>
      <div><?php echo $results['article']->content?></div>

      <hr/>
      <div>
        <h2 style= "padding:4px;">Comments</h2>
        <?php
            foreach($results['comments'] as $comment)
            {?>
            <p>
                <?php echo $comment->comment;?>
            </p>
        <?php } ?>
      </div>
      <hr/>
      <p><a href="./">Return to Homepage</a></p>
 </div>
<?php include "templates/include/footer.php" ?>
