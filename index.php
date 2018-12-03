<?php

    require ("config.php");
    $action = isset( $_GET['action']) ? $_GET['action'] : "";

    switch( $action )
    {
    case 'archive':
        archive();
        break;
    case 'viewArticle':
        viewArticle();
        break;
    case 'addComment':
        addComment();
        break;
    default:
        homepage();
    }


    function archive()
    {
        $results = array();
        $data = Article::getList();
        $results['articles'] = $data['results'];
        $results['totalRows'] = $data['totalRows'];
        $results['pageTitle'] = "Article Archive | Blog Of Rituraj";

        require( TEMPLATE_PATH . "/archive.php");

    }

    function viewArticle()
    {
        if( !isset( $_GET['articleId']) || !$_GET['articleId'] )
        {
            homepage();
            return;
        }

        $results = array();
        $results['article'] = Article::getById( (int)$_GET['articleId'] );
        $results['pageTitle'] = $results['article']->title . " | Blog Of Rituraj";
        $results['comments'] = Comment::getByArticleId( $results['article'] ->id );

        require( TEMPLATE_PATH . "/viewArticle.php" );
    }

    function homepage()
    {
        $results = array();
        $data = Article::getList( HOMEPAGE_NUM_ARTICLES );
        $results['articles'] = $data['results'];
        $results['totalRows'] = $data['totalRows'];
        $results['pageTitle'] = "Blog Of Rituraj";

        require( TEMPLATE_PATH . "/homepage.php" );
    }

    function addComment()
    {
        if( !isset( $_POST['comment'] ))
            return;

        $comment = new Comment();
        $comment->storeFromValues($_POST);
        $comment->publicationDate = time();
        $comment->insert();

        header( "Location:./?action=viewArticle&articleId=". $comment->articleId);
    }

?>
