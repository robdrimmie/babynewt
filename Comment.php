<?php

require_once( "Model.php");

class Comment extends Model {
    protected $_mostRecent;

    public function create( $comment, $uid, $category ) {
        $AddCommentQuery = " INSERT INTO Comment";
        $AddCommentQuery .= " (t_Comment, i_UID, dt_DatePosted, i_CategoryId)";
        $AddCommentQuery .= " VALUES ( ?, ?, NOW(), ?)";

        $addCommentStatment->prepare( $this->_db->getConnection(), $AddCommentQuery );
        $addCommentStatment->bind( 'sii', $comment, $uid, $category );
        $addCommentStatment->exec();

        //$AddCommentResultId = mysql_query ($AddCommentQuery, $link);        
    }

    public function mostRecent() {
        if ( null === $this->_mostRecent ) {
            $query = "SELECT MAX( Comment.i_CommentID ) AS maxCmt FROM Comment";

            $result = mysql_query ($query, $this->_db->getConnection() );
            $mostRecent = mysql_fetch_object($MaxCmtResId);

            $this->_mostRecent = $mostRecent->maxComment;
        }

        return $this->_mostRecent;
    }
}
