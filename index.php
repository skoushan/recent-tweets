<!DOCTYPE html>
<html>
	<head>
		<title>Recent Tweets</title>
		<link rel="stylesheet" href="main.css">
		<meta name="viewport" content="width=device-width">
	</head>
	<body>
		<div id='main'>
			<div id='side'>
				<img class='profile' src='https://pbs.twimg.com/profile_images/1884069342/BGtwitter.JPG' />
			</div>
			<div id='content'>
				<header>
					<h1>Bill Gates</h1>
					<span class='twitter-handle'>@BillGates</span>
				</header>
					<h2>Recent tweets</h2>
<?php
require 'app_tokens.php';
require 'tmhOAuth/tmhOAuth.php';
$query = htmlspecialchars($_GET['query']);

$connection = new tmhOAuth(array(
    'consumer_key' => $consumer_key,
    'consumer_secret' => $consumer_secret,
    'user_token' => $user_token,
    'user_secret' => $user_secret
));
// Get the timeline with the Twitter API
$http_code = $connection->request('GET',
    $connection->url('1.1/statuses/user_timeline'),
    array('screen_name' => 'billgates', 'count' => 10));
// Request was successful
if ($http_code == 200) {
    // Extract the tweets from the API response
    $response = json_decode($connection->response['response'],true);

    // Accumulate tweets from results
    $out = "<ul class='tweets'>";
    foreach ($response as $tweet) {
        $date = DateTime::createFromFormat('D M d H:i:s O Y', $tweet['created_at'])->format('M d Y - h:ia');
        $out .= '<li><p>'. $tweet['text'] . '</p><span class="date">'.$date.'</span></li>';
    }
    $out .= '</ul>';
    // Send the tweets back to the Ajax request
    print $out;
}
// Handle errors from API request
else {
    if ($http_code == 429) {
        print 'Error: Twitter API rate limit reached';
    }
    else {
        print 'Error: Twitter was not able to process that request';
    }
} 
?>
			</div>
		</div>
	</body>
</html>