<?php

require_once( "Model.php");

class Comment extends Model {
    protected $_mostRecent;

    public function create( $comment, $uid, $category ) {
        $AddCommentQuery = " INSERT INTO Comment";
        $AddCommentQuery .= " (t_Comment, i_UID, dt_DatePosted, i_CategoryId)";
        $AddCommentQuery .= " VALUES ( ?, ?, NOW(), ?)";

	$conn = $this->_db->getConnection();
	$addCommentStatement = $conn->stmt_init();
        $addCommentStatement->prepare(
		$AddCommentQuery
	);
        $addCommentStatement->bind_param( 'sii', $comment, $uid, $category );
        $addCommentStatement->execute();

        //$AddCommentResultId = mysqli_query ($link, $AddCommentQuery);
    }

    public function mostRecent() {
        if ( null === $this->_mostRecent ) {
            $query = "SELECT MAX( Comment.i_CommentID ) AS maxCmt FROM Comment";

            $result = mysqli_query ($this->_db->getConnection(), $query);
            $mostRecent = mysqli_fetch_object($MaxCmtResId);

            $this->_mostRecent = $mostRecent->maxComment;
        }

        return $this->_mostRecent;
    }
}
