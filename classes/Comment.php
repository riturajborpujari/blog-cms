<?php

/*
 * File		: Comment.php
 * Author 	: Rituraj Borpujari
 * Desc		: PHP class for Comment structure. Comments are posted for an article by viewers.
 * 			This class provides methods for inserting, and deleting comments.
 */

class Comment
{
    private $id = null;                     // used for identification
    private $publicationDate = null;
    private $username = null;               // name of the user who posted this comment
    private $comment = null;                // the actual comment string
    private $articleId = null;              // the id of the article for which the comment is posted

    public function __construct( $data = array() )
    {
        if( isset( $data['id'] ))
            $this->id = $data['id'];
        if( isset( $data['publicationDate'] ))
            $this->publicationDate = $data['publicatioinDate'];
        if( isset( $data['username'] ))
            $this->username = preg_replace ( "/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/", "", $data['username'] );
        if( isset( $data['comment'] ))
            $this->comment = preg_replace ( "/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/", "", $data['comment'] );
        if( isset( $data['articleId'] ))
            $this->articleId = $data['artiicleID'];
    }

    public function storeFromValues( $params )
    {
        $this->__construct( $params );
    }

    // returns the comment posts for a particular article
    public static function getByArticleId( $aId )
    {
        $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
        $query = "SELECT *, UNIX_TIMESTAMP(publicationDate) AS publicationDate
         FROM comments ORDER BY publicationDate DESC WHERE articleId = :aId";
        $st = $conn->prepare($query);
        $st->bindValue( ":aId", $aId, PDO::PARAM_INT);
        $st->execute();

        $posts = array();
        while( $row = $st->fetch() )
        {
            $post = new Comment( $row );
            $posts[] = $post;
        }

        return $posts;
    }

    public function insert()
    {
        if( !is_null( $this->id ) )
			trigger_error( "Article::insert() - Attempting to insert a comment that already has its id set!" );

        $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
        $query = "INSERT INTO comments( publicationDate, username, comment, articleId)
            VALUES( FROM_UNIXTIME( :publicationDate ), :username, :comment, :articleId)";
        $st = $conn->prepare( $query );
        $st->bindValue( ":publicationDate", $this->publicationDate, PDO::PARAM_INT );
        $st->bindValue( ":username", $this->username, PDO::PARAM_STR );
        $st->bindValue( ":comment", $this->comment, PDO::PARAM_STR );
        $st->bindValue( ":articleId", $this->articleId, PDO::PARAM_INT);
        $st->execute();

        $this->id = $conn->lastInsertId();
        $conn = null;
    }

    public function delete()
    {
        if( is_null( $this->id ) )
            trigger_error( "Comment::delete() - Attempting to delete a comment that doesn't have its id set!" );

        $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
        $query = "DELETE FROM comments WHERE id = :id" ;
        $st = $conn->prepare( $query );
		$st->bindValue( ":id", $this->id, PDO::PARAM_INT );
		$st->execute();

		$conn = null;
    }
};