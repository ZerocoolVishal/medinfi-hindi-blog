<?php
/**
 * Created by IntelliJ IDEA.
 * User: Medinfi SW 2
 * Date: 18-12-2017
 * Time: 06:10 PM
 */

class addHyperlinksCommand extends CConsoleCommand{

    public function actionaddHyperlinks()
    {
        $data= array();
        $count=0;
        $error_msg=date("h:i:sa").", ".date("Y-m-d",time()).":  LOG FROM addHyperlinks command\n\n";
        $base_url="https://www.medinfi.com/blog"; //Uncomment this line and comment the next for production
        //$base_url="https://52.76.146.208/blog";
        $data=array_map('str_getcsv', file(Yii::getPathOfAlias('application').'/../HYPERLINK_DATA.csv'));
        $error_file = fopen(Yii::getPathOfAlias('application')."/../HYPERLINK_ERRORS.doc", "w") or die("Unable to open file!");

        $size=sizeof($data,0);

        for($i=1;$i<$size-1;$i++)
        {
            $postSlug=$data[$i][0];
            $k=0;
            $subdata=array();
            for($j=$i;$j<$size;$j++)
            {
                if($postSlug==$data[$j][0])
                {
                    $subdata[$k][0]=$data[$j][0];
                    $subdata[$k][1]=$data[$j][1];
                    $subdata[$k][2]=$data[$j][2];
                    $k++;
                }
                else
                {
                    $i=$j-1;
                    break;
                }
            }
            //print_r($subdata);

            $error_msg .="\nSLUG NAME:".$postSlug;

            //$postDetails = BlogUtility::getPostDetails($postSlug);
            $postDetails = $this->getPostDetails($postSlug);

            if(!empty($postDetails)) {
                $postId = $postDetails['id'];

                $error_msg.="\nPOST ID: ".$postId."\n";
                $sql = "SELECT post_content FROM wp_posts where ID=".$postId;
                $result = Yii::app()->wpDb->createCommand($sql)->queryAll();

                if (sizeof($result) > 0)
                {
                    // output data of each row
                    foreach ($result as $row)
                    {
                        $content= $row["post_content"];
                    }
                }
                else
                {
                    Yii::log("0 results");
                }

                //print_r($subdata);

                //echo "<br>*****************<br>";

                foreach ($subdata as $datares)
                {
                    $str=$datares[1];
                    $repstr=" <a href=\"".$base_url."/".$datares[2]."/\">".$str."</a>";

                    $check_tag=stripos($content,$repstr);
                    //print_r($repstr);

                    if(empty($check_tag))
                    {
                        $low_str=strtolower($str);
                        $repstr=" <a href=\"".$base_url."/".$datares[2]."/\">".$low_str."</a>";
                        $check_tag=stripos($content,$repstr);
                        if(empty($check_tag))
                        {
                            $content=preg_replace("/\b".$low_str."\b/",$repstr,$content);
                        }
                        if(strcmp($str, $low_str) != 0){
                        $repstr=" <a href=\"".$base_url."/".$datares[2]."/\">".$str."</a>";
                        $content=preg_replace("/\b".$str."\b/",$repstr,$content);
                        }

                    }
                    else
                    {
                        $error_msg .="\nWARNING!!! Word ".$str." already replaced!!";
                        $count++;
                    }
                }

                $error_msg .="\n";

                $blogPost = BlogPosts::model()->findByPk($postId);
                $blogPost->post_content=$content;
                $blogPost->update();


                //$sql = "update wp_posts set post_content='".$content."' where ID=".$postId;
                //$result = Yii::app()->wpDb->createCommand($sql)->queryAll();
            }
            else{
                $error_msg .="\nERROR!!! This post does not exist.\n";
            }

            //print_r($postDetails);
        }

        //print_r($error_msg);
        print_r($count);
        fwrite($error_file, $error_msg);
        fclose($error_file);
    }

    public function getPostDetails($postUniqueId) {
        $postDetails = array();
        //This hard coding is as Yii url parameter is not able to pass soft-hypen symbol in the manner stored in wp_posts table.
        //The value in below mentioned 'if condition' contains the hidden soft hyphen character afer 'scoliosis'
        /*
        Nitish Jha
        19-05-2017
        We have removed hidden soft hyphen and for redirecting we are doing the if loop
        */
        //$urlPublishedOnDate = $postYear."/".$postMonth."/".$postDay;
        $query = "select distinct wp.ID,wp.post_title,DATE_FORMAT(wp.post_date, '%D %b %Y') as post_date, wp.post_content,wpm1.meta_value as imgData,wp.post_name as slug,DATE_FORMAT(wp.post_date, '%Y/%m/%d') as post_url_date,
			case
			when wt2.term_id is NOT NULL THEN wt2.slug
			ELSE wt.slug
			END AS category_slug 
			from wp_posts as wp 
			inner join wp_term_relationships as wtr on wp.ID = wtr.object_id
			inner join wp_term_taxonomy as wtt on wtr.term_taxonomy_id = wtt.term_taxonomy_id
			inner join wp_terms as wt on wt.term_id = wtt.term_id
			inner join wp_postmeta as wpm on wpm.post_id = wp.ID
			inner join wp_posts as wp1 on wp1.ID = wpm.meta_value
			inner join wp_postmeta as wpm1 on wpm1.post_id = wpm.meta_value
            inner join wp_postmeta as wpm2 on wp.ID = wpm2.post_id
			left join wp_terms as wt2 on wpm2.meta_value = wt2.term_id 
			where 
			wp.post_status ='publish' and 
            wtt.taxonomy = 'category' and
			wpm.meta_key = '_thumbnail_id' and 
            wpm1.meta_key ='_wp_attachment_metadata' and
            wp1.post_type = 'attachment' and
            wpm2.meta_key like '_yoast_wpseo_primary_category' and ";


        $query .= "wp.post_name ='".$postUniqueId."'";
        //Yii::log(" Query to the get the post details is >>> ".$query);
        $postUnprocessedDetails = Yii::app()->wpDb->createCommand($query)->queryAll();
        if(!empty($postUnprocessedDetails)) {
            $postDetails['id'] = $postUnprocessedDetails[0]['ID'];
        }
        return $postDetails;
    }
}