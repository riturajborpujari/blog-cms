<?php
    require( "config.php" );
    session_start();

    $action = isset( $_GET['action'] ) ? $_GET['action']: "";
    $username = isset( $_SESSION['username'] ) ? $_SESSION['username'] : "";

    if( $action != "login" && $action != "logout" && $username == "")
    {
        login();
        exit();
    }

    switch( $action )
    {
    case 'login' :
        login();
        break;
    case 'logout':
        logout();
        break;
    case 'newArticle':
        newArticle();
        break;
    case 'editArticle':
        editArticle();
        break;
    case 'deleteArticle':
        deleteArticle();
        break;
    default:
        listArticles();
        break;
    }

    function login()
    {
        $results = array();
        $results['pageTitle'] = "Admin Login | Blog Of rituraj";

        if( isset( $_POST['login'] ) )
        {
            // user has posted the login form, attempt to login
            if( $_POST['username'] == ADMIN_USERNAME && $_POST['password'] == ADMIN_PASSWORD )
            {
                // login successful, redirect to admin homepage
                $_SESSION['username'] = ADMIN_USERNAME;
                header( "Location: admin.php" );
            }
            else
            {
                // login failed: display error message
                $results['errorMessage'] = "Incorrect Username and/or password!";
                require( TEMPLATE_PATH . "/admin/loginForm.php");
            }
        }
        else
        {
            // user has not posted the login form yet, display it
            require( TEMPLATE_PATH . "/admin/loginForm.php" );
        }
    }

    function logout()
    {
        unset( $_SESSION['username']);
        header( "Location: admin.php" );
    }

    function newArticle()
    {
        $results = array();
        $results['pageTitle'] = "New Article";
        $results['formAction'] = "newArticle";

        if( isset($_POST['saveChanges'] ) )
        {
            // user has posted the article creation form
            $article = new Article;
            $article->storeFromValues( $_POST );
            $article->insert();
            
            header( "Location: admin.php?status=changesSaved" );
        }
        elseif( isset( $_POST['cancel'] ) )
        {
            // user has cancelled new article creation
            header( "Location: admin.php" );
        }
        else
        {
            // user has not posted article creation form yet, display it
            $results['article'] = new Article;
            require( TEMPLATE_PATH . "/admin/editArticle.php" );
        }
    }

    function editArticle()
    {
        $results = array();
        $results['pageTitle'] = "Edit Article";
        $results['formAction'] = "editArticle";

        if( isset( $_POST['saveChanges'] ) )
        {
            // user has posted the edit article form: update it

            if( !$article = Article::getById( (int)$_POST['articleId'] ) )
            {
                header( "location: admin.php?status=articleNotFound");
                return;
            }

            $article->storeFromValues( $_POST );
            $article->update();
            header( "Location: admin.php?status=changesSaved" );

        }
        elseif( isset( $_POST['cancel'] ) )
        {
            // user has cancelled article update : return to admin page
            header( "Location: admin.php" );
        }
        else
        {
            // user has not posted the article edit form yet: display it
            $results['article'] = Article::getById( $_GET['articleId'] );
            require( TEMPLATE_PATH . "/admin/editArticle.php" );
        }
    }

    function deleteArticle()
    {
        if( !$article = Article::getById( (int)$_GET['articleId'] )) 
        {
            header( "Location:admin.php?error=articleNotFound" );
            return;
        }

        $article->delete();
        header( "Location: admin.php?status=articleDeleted" );

    }

    function listArticles()
    {
        $results = array();
        $data = Article::getList();
        $results['articles'] = $data['results'];
        $results['totalRows'] = $data['totalRows'];
        $results['pageTitle'] = "All Articles";

        if( isset( $_GET['error'] ) )
        {
            if( $_GET['error'] == "articleNotFound" )
                $results['errorMessage'] = "Error: Article not found in database!";
        }
    

        if( isset( $_GET['status'] ) )
        {
            if( $_GET['status'] == "changesSaved" )
                $results['statusMessage'] = "Success: Your changes has been saved.";
            if( $_GET['status'] == "articleDeleted" )
                $results['statusMessage'] = "Success: Article has been deleted.";

        }

        require( TEMPLATE_PATH . "/admin/listArticles.php" );
    }
?>