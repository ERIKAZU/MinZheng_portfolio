<?php
include("includes/init.php");
// declare the current location, utilized in header.php
$current_page_id="index";
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <link rel="stylesheet" type="text/css" href="style/all.css" media="all" />

  <title>Home - <?php echo $title;?></title>
</head>

<body>
  <?php include("includes/header.php");?>
  <h2>Welcome</h2>
  <h3>Neko Atsume: Kitty Collector is a cat collecting game developed by Hit-Point Co.,Ltd.
    for iOS and Android, released on October 20, 2014.</h3>

    <h3>
      Gameplay revolves around the player purchasing food, toys, and furniture to attract a variety of cats to the player's home. The player can watch cats interact with objects, take photos of them which can be saved in an album, and receive gifts of fish and mementos from them.

    </h3>
    <h3>
      Cats will leave the player either silver or gold fish, after leaving the garden. Players can also complete a daily password to receive a random amount of silver or gold fish. This feature can be located in the "news" button from the main menu.
    </h3>
    <h3>
      After purchasing the initial expansion to the garden, the player can then purchase a remodel for their garden. Players have the option to remodel their garden to one of seven different styles: Original, Zen, Rustic, Modern, Western, Sugary, or Cafe.
    </h3>
    <h3>
      There is no end game and cats will continue to come as long as the player puts out food for them.
    </h3>
    <h3>
      The game is free to play, though additional niboshi are available for purchase.
      The game can be downloaded at<a class="link" href="https://itunes.apple.com/us/app/nekoatsume/id923917775?mt=8"> Neko Atsume</a>.
    </h3>


    <address class="citation">
      (Written by <a class="link" href="https://en.wikipedia.org/wiki/Neko_Atsume"> Wikipedia</a>)
    </address>


    <?php include("includes/footer.php");?>
</body>
</html>
