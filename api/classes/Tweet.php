<?php


class Tweet
{
    /**
     * @var PDO
     */
    public $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function saveTweet($username, $inReplyToLink, $link) :bool
    {
        $query = 'INSERT INTO tweets SET 
                       user_name=:username,
                       in_reply_to_link= :in_reply_to_link,
                       tweet_link = :tweet_link';

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':in_reply_to_link', $inReplyToLink);
        $stmt->bindParam(':tweet_link', $link);

        return $stmt->execute();

    }

    public function getTweets():array
    {
        $query = 'SELECT * FROM tweets ORDER BY id DESC LIMIT 100';

        $stmt = $this->conn->query($query);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTopUsers($count = 10)
    {
        $query = 'SELECT user_name, COUNT(user_name) count 
                    FROM tweets GROUP BY user_name ORDER BY count DESC LIMIT 10 ';

        $stmt = $this->conn->query($query);

        //$stmt->bindParam(':cn', $count);

        //$stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}