<?php

require_once( "Model.php");

class Style extends Model {

    public function create( $id, $sheet ) {
        $query = "INSERT INTO DBStyleSheet (i_StyleSheetId, t_StyleSheet)";
        $query .= " VALUES (?, ?)";

        $statement = $this->prepareStatementForQuery( $query );

        $statement->bind_param( 'is', $id, $sheet );

        $statement->execute();
        $statement->close();

        return $id;
    }

    public function update( $id, $sheet ) {
        $query = " UPDATE DBStyleSheet";
        $query .= " SET t_StyleSheet=?";
        $query .= " WHERE i_StyleSheetId=?";

        $statement = $this->prepareStatementForQuery( $query );
        
	$statement->bind_param( 'si', $sheet, $id );
        $statement->execute();

	$statement->close();
    }
}
