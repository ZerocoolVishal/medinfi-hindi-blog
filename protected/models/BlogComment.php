<?php

/**
 * This is the model class for table "wp_comments".
 *
 * The followings are the available columns in table 'wp_comments':
 * @property int $comment_ID
 * @property int $comment_post_ID
 * @property string $comment_author
 * @property string $comment_author_email
 * @property string $comment_author_url
 * @property string $comment_author_IP
 * @property string $comment_date
 * @property string $comment_date_gmt
 * @property string $comment_content
 * @property int $comment_karma
 * @property string $comment_approved
 * @property string $comment_agent
 * @property string $comment_type
 * @property int $comment_parent
 * @property int $user_id
  */ 

class BlogComment extends BlogActiveRecord {

	 public function getDbConnection()
    {
        return self::getBlogDbConnection();
    }

    /**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'wp_comments';
	}

}


?>