<?PHP
    include_once "db_mysql.php",
    
    class content
    {
        public static function createMenu()
        {
            //Should get these from db (dynamic)
            echo "<li id='current'><a href='index.html'>Home</a></li>";
            echo "<li><a href='style.html'>Style Demo</a></li>";
            echo "<li><a href='blog.html'>Blog</a></li>";
            echo "<li><a href='archives.html'>Archives</a></li>";
            echo "<li><a href='index.html'>Support</a></li>";
            echo "<li><a href='index.html'>About</a></li>";
        }
        
        //Create article items based on result (only accept articles)
        public static function createArticleItems($result)
        {
            $counter = 0;
            $content = "";
            
            while ($row = mysql_fetch_assoc($result))
            {
                $title = $row["title"];
                $text = $row["content"];
                $imagePath = $row["image"];
                $date = $row["posted"];
                $comments = 0;
                
                //Calculates number of comments for that article
                $res = db::executeQuery("SELECT COUNT(uid) FROM comments WHERE article_id = " . $row["uid"]);
                $comments = mysql_fetch_row($res);
                
                $counter++;
                if($counter == 1)
                {
                    $content .= "<div class='block odd'>";
                    $counter = -1;
                }
                else
                {
                    $content .= "<div class='block even'>";
                    $content .= "<div class='fix'></div>";
                }
                
                if($imagePath.length() > 0)
                    $content .= "<a title='' href='index.html'><img src='" . $imagePath . "' class='thumbnail' alt='img' width='240px' height='100px'/></a>";
                
                $content .= "<div class='blk-top'>";
                $content .= "<h4><a href='index.html'>" . $title . "</a></h4>";
                $content .= "<p><span class='datetime'>" . $date . "</span><a href='index.html' class='comment'>" . $comments . " Comments</a></p>";
                $content .= "</div>";
                
                $content .= "<div class='blk-content'>";
                $content .= "<p>" . $text . "</p>";			
                $content .= "<p><a class='more' href='index.html'>continue reading &raquo;</a></p>"; 
                //index.html need to be fixed (should be link to article)
                $content .= "</div>";
                $content .= "</div>";
            }
            if($counter != 0)
                $content .= "<div class='fix'></div>";
            return $content;
        }
        
        //Creates featured items based on result
        public static function createFeaturedItems($result)
        {
            $content = "";
            while ($row = mysql_fetch_assoc($result))
            {
                $title = "";
                $subtitle = "";
                $text = "";
                $imagePath = "";
                
                $table = mysql_tablename($result, $row); //not sure at all if this works (not tested)
                if($table == "featured")
                {
                    //Get row for featured post
                    // Why have a featured table when you can use maps/units/guides/.. ?
                    // Answer: In featured you can combine different elements if you wish (maps and units)
                    $table = $row["table"];
                    $res = db::executeQuery("SELECT * FROM " . $table . " WHERE uid = " . $row["id"]);
                    $row = mysql_fetch_array($result);
                }
                switch($table)
                {
                    //Set title, image
                    case "maps":
                        $title = $row["title"];
                        $subtitle = "posted at " . $row["posted"] . " by " . $row["user_id"];
                        $text = $row["description"];
                        $imagePath = $row["minimap"];
                        break;
                    case "units":
                        $title = $row["title"];
                        $subtitle = "posted at " . $row["posted"] . " by " . $row["user_id"];
                        $text = "";
                        $imagePath = "";
                        break;
                    case "guide":
                        $title = $row["title"];
                        $subtitle = "posted at " . $row["posted"] . " by " . $row["user_id"];
                        $text = "";
                        $imagePath = "";
                        break;
                }
                //Should get these from db
                $content .= "<div id='featured-block' class='clear'>";
                $content .= "<div id='featured-ribbon'></div>";//<< Maybe have different ribbons? ex: featured, editors choice, peoples choice,...
                $content .= "<a name='TemplateInfo'></a>";
                
                if($imagePath.length() > 0)
                {
                    $content .= "<div class='image-block'>";
                    $content .= "<a href='index.html' title=''><img src='" . $imagePath . "' alt='featured' width='350px' height='250px'/></a>";
                    $content .= "</div>";
                }
                
                $content .= "<div class='text-block'>";
                $content .= "<h2><a href='index.html'>" . $title . "</a></h2>"; //index.html? could it be something else..
                $content .= "<p class='post-info'>" . $subtitle . "</p>";
                $content .= "<p>" . $text . "</p>";
                $content .= "<p><a href='index.html' class='more-link'>Read More</a></p>"; //index.html? could it be something else..
                                                                                           //All use read more button?
                $content .= "</div>";
                $content .= "</div>";
            }
            
            return $content;
        }
    }
?>