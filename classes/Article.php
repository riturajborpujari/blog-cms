<?php


/**
 * Class to handle articles
 */

class Article
{
	// properties
	public $id = null;
	public $publicationDate = null;
	public $title = null;
	public $summary = null;
	public $content = null;

	public function __construct( $data = array() )
	{
		if( isset( $data['id'] ) )
			$this->id = (int) $data['id'];
		if( isset( $data['publicationDate'] ) )
			$this->publicationDate = $data['publicationDate'];
		if( isset( $data['title'] ) )
			$this->title = preg_replace ( "/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/", "", $data['title'] );
		if( isset( $data['summary'] ) ) 
			$this->summary = preg_replace ( "/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/", "", $data['summary'] );
		if( isset( $data['content'] ) ) 
			$this->content = $data['content'];
	}

	public function storeFromValues ( $params ) 
	{
		// Store all the parameters
		$this->__construct( $params );
		
		// Parse and store the publication date
		if ( isset($params['publicationDate']) ) 
		{
			$publicationDate = explode ( '-', $params['publicationDate'] );
			if ( count($publicationDate) == 3 ) 
			{
				list ( $y, $m, $d ) = $publicationDate;
				$this->publicationDate = mktime ( 0, 0, 0, $m, $d, $y );
			}
		}
	}


	public static function getById( $id )
	{
		$conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD);
		$query = "SELECT *, UNIX_TIMESTAMP(publicationDate) AS publicationDate from articles WHERE id = :id";
		$st = $conn->prepare( $query );
		$st->bindValue( ":id", $id, PDO::PARAM_INT );
		$st->execute();
		$row = $st->fetch();
		$conn = null;

		if( $row )
			return new Article( $row );
	}

	public static function getList( $numRows = 1000, $order = "publicationDate Desc" )
	{
		$conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD);
		$query = "SELECT *, UNIX_TIMESTAMP(publicationDate) AS publicationDate FROM articles ORDER BY " 
			. $order . " LIMIT :numRows" ;
		$st = $conn->prepare($query);
		$st->bindValue(":numRows", $numRows, PDO::PARAM_INT);
		$st->execute();

		$list = array();

		while ( $row = $st->fetch() )
		{
			$article = new Article( $row );
			$list[] = $article;
		}

		// get the total number of articles
		$totalRows = count($list);

		$conn = null;

		return( array( "results" => $list, "totalRows" => $totalRows ) );
	}

	public function insert()
	{
		if( !is_null( $this->id ) )
			trigger_error( "Article::insert() - Attempting to insert an article that already has its id set!" );

		$conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
		$query = "INSERT INTO articles ( publicationDate, title, summary,content ) 
			VALUES( FROM_UNIXTIME( :publicationDate ), :title, :summary, :content )";
		$st = $conn->prepare($query);
		$st->bindValue( ":publicationDate", $this->publicationDate, PDO::PARAM_INT );
		$st->bindValue( ":title", $this->title, PDO::PARAM_STR );
		$st->bindValue( ":summary", $this->summary, PDO::PARAM_STR );

		$contConverted = nl2br( $this->content );
		$st->bindValue( ":content", $contConverted, PDO::PARAM_STR );
		$st->execute();

		$this->id = $conn->lastInsertId();
		$conn = null;
	}

	public function update()
	{
		if( is_null( $this-id ) )
                        trigger_error( "Article::update() - Attempting to update an article that doesn't have its id set!" );

		$conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
		$query = "UPDATE articles SET publicationDate = FROM_UNIXTIME(:publicationDate), 
			title = :title, summary = :summary, content = :content WHERE id = :id";

		$st = $conn->prepare( $query );
		$st->bindValue( ":publicationDate", $this->publicationDate, PDO::PARAM_INT );
                $st->bindValue( ":title", $this->title, PDO::PARAM_STR );
                $st->bindValue( ":summary", $this->summary, PDO::PARAM_STR );
		$st->bindValue( ":content", $this->content, PDO::PARAM_STR );
		$st->bindValue( ":id", $this->id, PDO::PARAM_INT);

		$st->execute();
		$conn = null;
	}

	public function delete()
	{
		if( is_null( $this-id ) )
                        trigger_error( "Article::delete() - Attempting to delete an article that doesn't have its id set!" );

                $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
		$query = "DELETE FROM articles WHERE id = :id" ;
		$st = $conn->prepare( $query );
		$st->bindValue( ":id", $this->id, PDO::PARAM_INT );
		$st->execute();

		$conn = null;
	}

};