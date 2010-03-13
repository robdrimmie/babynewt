<?php

class Comment extends Model
{
    protected $_mostRecent;

    public function mostRecent() {
        if( null === $this->_mostRecent ) {
            $query = "SELECT MAX( Comment.i_CommentID ) AS maxCmt FROM Comment";

            $result = mysql_query ($query, $this->_db->getConnection() );
            $mostRecent = mysql_fetch_object($MaxCmtResId);

            $this->_mostRecent = $mostRecent->maxComment;
        }

        return $this->_mostRecent;
    }
}
