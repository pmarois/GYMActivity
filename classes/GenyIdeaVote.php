<?php

include_once 'GenyWebConfig.php';

class GenyIdeaVote {

	private $updates = array();

	public function __construct( $id = -1 ) {
		$this->config = new GenyWebConfig();
		$this->handle = mysql_connect( $this->config->db_host, $this->config->db_user, $this->config->db_password );
		mysql_select_db( "GYMActivity" );
		mysql_query( "SET NAMES 'utf8'" );
		$this->id = -1;
		$this->positive_vote = -1;
		$this->negative_vote = -1;
		$this->profile_id = -1;
		$this->idea_id = -1;
		if( $id > -1 ) {
			$this->loadIdeaVoteById( $id );
		}
	}

	public function insertNewIdeaVote( $id, $idea_positive_vote, $idea_negative_vote, $profile_id, $idea_id ) {
		if( !is_numeric( $id ) && $id != 'NULL' ) {
			return -1;
		}
		if( !is_numeric( $idea_positive_vote ) ) {
			return -1;
		}
		if( !is_numeric( $idea_negative_vote ) ) {
			return -1;
		}
		if( !is_numeric( $profile_id ) ) {
			return -1;
		}
		if( !is_numeric( $idea_id ) ) {
			return -1;
		}
		$query = "INSERT INTO IdeaVotes VALUES($id,'".$idea_positive_vote."','".$idea_negative_vote."','".$profile_id."','".$idea_id."')";
		if( $this->config->debug ) {
			echo "<!-- DEBUG: GenyIdeaVote MySQL query : $query -->\n";
		}
		if( $this->config->debug ) {
			echo "<!-- DEBUG: GenyIdea MySQL query : $query -->\n";
		}
		if( mysql_query( $query, $this->handle ) ) {
			return mysql_insert_id( $this->handle );
		}
		else {
			return -1;
		}
	}

	public function removeIdeaVote( $id ) {
		if( !is_numeric( $id ) ) {
			return false;
		}
		$query = "DELETE FROM IdeaVotes WHERE idea_vote_id=$id";
		return mysql_query( $query, $this->handle );
	}

	public function getIdeaVotesListWithRestrictions( $restrictions ) {
		// $restrictions is in the form of array("idea_id=1","idea_status_id=2")
		$last_index = count( $restrictions ) - 1;
		$query = "SELECT idea_vote_id,idea_positive_vote,idea_negative_vote,profile_id,idea_id FROM IdeaVotes";
		if( count( $restrictions ) > 0 ) {
			$query .= " WHERE ";
			foreach( $restrictions as $key => $value ) {
				$query .= $value;
				if( $key != $last_index ) {
					$query .= " AND ";
				}
			}
		}
		if( $this->config->debug ) {
			echo "<!-- DEBUG: GenyIdeaVote MySQL query : $query -->\n";
		}
		$result = mysql_query( $query, $this->handle );
		$idea_vote_list = array();
		if( mysql_num_rows( $result ) != 0 ) {
			while( $row = mysql_fetch_row( $result ) ) {
				$tmp_idea_vote = new GenyIdeaVote();
				$tmp_idea_vote->id = $row[0];
				$tmp_idea_vote->idea_positive_vote = $row[1];
				$tmp_idea_vote->idea_negative_vote = $row[2];
				$tmp_idea_vote->profile_id = $row[3];
				$tmp_idea_vote->idea_id = $row[4];
				$idea_vote_list[] = $tmp_idea_vote;
			}
		}
		mysql_close();
		return $idea_vote_list;
	}

	public function getAllIdeaVotes() {
		return $this->getIdeaVotesListWithRestrictions( array() );
	}

	public function getIdeaVotesListByProfileId( $id ) {
		if( !is_numeric( $id ) ) {
			return false;
		}
		return $this->getIdeaVotesListWithRestrictions( array("profile_id=$id") );
	}

	public function getIdeaVotesListByIdeaId( $id ) {
		if( !is_numeric( $id ) ) {
			return false;
		}
		return $this->getIdeaVotesListWithRestrictions( array("idea_id=$id") );
	}

	public function getIdeaVotesListByProfileAndIdeaId( $profile_id, $idea_id ) {
		if( !is_numeric( $profile_id ) || !is_numeric( $idea_id ) ) {
			return array();
		}
		return $this->getIdeaVotesListWithRestrictions( array("profile_id=$profile_id", "idea_id=$idea_id") );
	}

	public function loadIdeaVoteById( $id ) {
		if( !is_numeric( $id ) ) {
			return false;
		}
		$idea_votes = $this->getIdeaVotesListWithRestrictions( array( "idea_vote_id=".mysql_real_escape_string( $id ) ) );
		$idea_vote = $idea_votes[0];
		if( isset( $idea_vote ) && $idea_vote->id > -1 ) {
			$this->id = $idea_vote->id;
			$this->positive_vote = $idea_vote->positive_vote;
			$this->negative_vote = $idea_vote->negative_vote;
			$this->profile_id = $idea_vote->profile_id;
			$this->idea_id = $idea_vote->idea_id;
		}
	}

	public function updateString( $key, $value ) {
		$this->updates[] = "$key='".mysql_real_escape_string( $value )."'";
	}

	public function updateInt( $key, $value ) {
		$this->updates[] = "$key=".mysql_real_escape_string( $value )."";
	}

	public function updateBool( $key, $value ) {
		$this->updates[] = "$key=".mysql_real_escape_string( $value )."";
	}

	public function commitUpdates() {
		$query = "UPDATE IdeaVotes SET ";
		foreach( $this->updates as $up ) {
			$query .= "$up,";
		}
		$query = rtrim( $query, "," );
		$query .= " WHERE idea_vote_id=".$this->id;
		if( $this->config->debug ) {
			echo "<!-- DEBUG: GenyIdeaVote MySQL query : $query -->\n";
		}
		return mysql_query( $query, $this->handle );
	}

}

?>