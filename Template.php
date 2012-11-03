<?php

require_once( "Model.php");

class Template extends Model {

    public function create( $uid, $isPublic, $header, $comment, $footer, $name ) {
	$query = " INSERT INTO Template";
        $query .= " (i_UID, b_Public, t_TemplateHdr, t_TemplateCmt, t_TemplateFtr, vc_TemplateName )";
        $query .= " VALUES ( ?, ?, ?, ?, ?, ?)";

	$statement = $this->prepareStatementForQuery( $query );

        $statement->bind_param( 'iissss', $uid, $isPublic, $header, $comment, $footer, $name );
        $statement->execute();
	
	return $statement->insert_id;
    }

    public function update( $templateId, $uid, $isPublic, $header, $comment, $footer, $name ) {
        $query = " UPDATE Template";
        $query .= " SET i_UID=?, b_Public=?, t_TemplateHdr=?";
        $query .= ", t_TemplateCmt=?, t_TemplateFtr=?, vc_TemplateName=?";
        $query .= " WHERE i_TemplateID=?";

	$statement = $this->prepareStatementForQuery( $query );

        $statement->bind_param( 'iissssi', 
		$uid, $isPublic, $header, 
		$comment, $footer, $name,
		$templateId
	);
        $statement->execute();

	$statement->close();






    }
}
