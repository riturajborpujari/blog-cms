<?php include "templates/include/header.php" ?>
    <div style="width:75%"> 
        <h1><?php echo htmlspecialchars( $results['article']->title )?></h1>
        <div style="font-style: italic;"><?php echo htmlspecialchars( $results['article']->summary )?></div>

        <p class="pubDate"><?php echo date('j F Y', $results['article']->publicationDate)?></p>
        <div><?php echo $results['article']->content?></div>

        <hr/>
        <div>
            <h2>Comments</h2>
            <?php foreach($results['comments'] as $comment) {?>
                <p style="padding:2px 0">
                    <span style="font-weight:bold; color: #f22;">
                        <?php echo $comment->username . " : "; ?>
                    </span>
                    <?php echo $comment->commentString;?>
                </p>
            <?php } ?>
        </div>
        <div>
            <form action="./?action=addComment" method="post">
                <input type = "hidden" name = "comment" value = ""/>
                <input type = "hidden" name = "articleId" 
                    value = <?php echo $results['article']->id;?> />
                <input type = "text" name = "username" required placeholder = "Enter your name *"/>
                <br/>
                <textarea name = "commentString" placeholder="Comment *" required 
                    maxlength = "160" style = "height:5em;"></textarea>
                <br/>
                <input type= "submit" value = "Add comment"/>
            </form>
        </div>
        <hr/>
        <p><a href="./">Return to Homepage</a></p>
    </div>
<?php include "templates/include/footer.php" ?>
