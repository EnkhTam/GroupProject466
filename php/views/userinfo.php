<!DOCTYPE html>
<html>
<head>
<?php include('../includes/header.php'); ?>
<title>All about you</title>
<link rel= "stylesheet" type = "text/css" href = "../../css/base/custom.css">
<link rel = "icon" href="../../media/img/base/cursor.gif">
</head>
<body>
<center>
<?php
//the purpose of this page is an addition so that the user can see if their information is right

echo'<div class = "row">
<div class = "col-md-6">';

    if(isset($_POST['submitW'])){
		$usql = "SELECT user_id FROM user WHERE user_name = '$username'";
		$uresult = mysqli_query($conn,$usql);
		$uno = mysqli_fetch_assoc($uresult);
		$use = $uno['user_id'];

		$Wweight = ($_POST["Wweight"]);
		$Wdate = ($_POST["Wdate"]);
		$Munit = ($_POST["Munit"]);
		$checkdate = mysqli_query($conn, "SELECT * FROM weightlogs 
											JOIN user ON weightlogs.user_id = user.user_id
											WHERE user_name = '$username'
											AND weight_date = '$Wdate'");

		if (mysqli_num_rows($checkdate) == true){
			//if there's already a weight for that date, overwrite it
			mysqli_query($conn, "DELETE w FROM weightlogs w
								LEFT JOIN user u ON u.user_id = w.user_id
								WHERE u.user_name = '$username'
								AND w.weight_date = '$Wdate'");
		}
		mysqli_query($conn, "INSERT INTO weightlogs (user_id, weight, weight_date, unit_id)
					VALUES ('$use','$Wweight','$Wdate','$Munit')");
		header("Location:userinfo.php");
    }

	if(isset($_POST['submitS'])){
		$usql = "SELECT user_id FROM user WHERE user_name = '$username'";
		$uresult = mysqli_query($conn,$usql);
		$uno = mysqli_fetch_assoc($uresult);
		$use = $uno['user_id'];

		$WSet = ($_POST["WSet"]);
		$MSet = ($_POST["MSet"]);

		/*mysqli_query($conn, "DELETE FROM settings WHERE user_id = $use");
		mysqli_query($conn, "INSERT INTO settings (user_id, wset_id, mset_id)
					VALUES ('$use','$WSet','$MSet')");*/
        mysqli_query($conn, "UPDATE user SET wset_id = '$WSet', mset_id = '$MSet'
                    WHERE user_id = '$use'");
		echo "<h3>".$WSet."</h3>";
        header("Location:userinfo.php");
	}

$result = mysqli_query($conn, "SELECT * FROM user WHERE user_name = '$username'");
//a while loop is set up to create a display
while($row = mysqli_fetch_array($result)){
  echo "<div class= 'info'><h2>Your Information:</h2>";
  echo "<h4>First Name: ".$row['first_name']."</h4> 
		<h4>Last Name: ".$row['last_name']."</h4>
		<h4>E-mail: ".$row['email']."</h4>
		<h4>Birth Date: ". $row['birth_date']."</h4>
		<h4>User ID: ".$row['user_id']."</h4>";
		$cweight = mysqli_query($conn, "SELECT * FROM weightlogs 
										JOIN user ON weightlogs.user_id = user.user_id
										JOIN measurement_unit ON weightlogs.unit_id = measurement_unit.unit_id
										WHERE user_name = '$username'
										AND weight_date = (SELECT MAX(weight_date)
															FROM weightlogs 
															JOIN user ON weightlogs.user_id = user.user_id
															WHERE user_name = '$username') GROUP BY weight_date");
		/*$wsett = mysqli_query($conn, "SELECT * FROM settings
										JOIN user ON settings.user_id = user.user_id
										WHERE user_name = '$username'");*/
        $wsett = mysqli_query($conn, "SELECT * FROM user WHERE user_name = '$username'");
		$wsrow = mysqli_fetch_array($wsett);

        $wesett = mysqli_query($conn, "SELECT * FROM measurement_unit
										WHERE unit_id = '".$wsrow['wset_id']."'");
        $werow = mysqli_fetch_array($wesett);
		if($cwrow = mysqli_fetch_array($cweight))
		{
			if($wsrow){
				if($cwrow['unit_id'] != $wsrow['wset_id'])
				{
					$displayres = mysqli_query($conn, "SELECT * FROM measurement_conv
												WHERE from_unit_id = '".$cwrow['unit_id']."' AND to_unit_id = '".$wsrow['wset_id']."'");
					$displayrow = mysqli_fetch_array($displayres);
					$displayw = ($cwrow['weight']*$displayrow['mult'])/$displayrow['divi'];
					echo "<h4>Current Weight: ".$displayw." ".$werow['unit_name']."s</h4>";
				}
				else if($cwrow['unit_id'] == $wsrow['wset_id']){
					echo "<h4>Current Weight: ".$cwrow['weight']." ".$werow['unit_name']."s</h4>";
				}
			}
		}
		$msett = mysqli_query($conn, "SELECT * FROM measurement_unit
										WHERE unit_id = '".$wsrow['mset_id']."'");
		$msrow = mysqli_fetch_array($msett);
		echo "<h4>Weight Display: ".$werow['unit_name']."s</h4>";
		echo "<h4>Measurement Display: ".$msrow['unit_name']."s</h4>";
		echo "</div><br />";
  }
	?>
</div>
<div class = "col-md-6">
	<div class = "add">
		<form action = "" method = "post">
		<h2>Update Weight</h2>
		<label>Weight: </label><input type = "number" name = "Wweight" required> 
		<?php
		$msql = mysqli_query($conn, "SELECT unit_id, unit_sym FROM measurement_unit");
		echo "<select name ='Munit'>";
		while($mrow = mysqli_fetch_assoc($msql)){
			echo "<option value='".$mrow['unit_id']."'>".$mrow['unit_sym']."</option>";
		}
		echo "</select>";
		?>
		<br><br>
		<label>Date: </label><input type = "date" name = "Wdate" required>
		<br>
		<input type="submit" name = "submitW" value="submit">
		<input type = "reset" value = "reset">
		<br>
		</form>
	</div>
</div>
<br><br>
<div class = "row">
	<div class = 'col-md-6'>
	<div class = "add">
		<form action = "" method = "post">
		<h2>Change Display Settings</h2>
		<label>Your Weight: </label>
			<?php
			$msql = mysqli_query($conn, "SELECT unit_id, unit_sym FROM measurement_unit");
			echo "<select name ='WSet'>";
			while($mrow = mysqli_fetch_assoc($msql)){
				echo "<option value='".$mrow['unit_id']."'>".$mrow['unit_sym']."</option>";
			}
			echo "</select>";
			?>
			<br><br>
		<label>Nutrient Measurements: </label>
			<?php
			$msql = mysqli_query($conn, "SELECT unit_id, unit_sym FROM measurement_unit");
			echo "<select name ='MSet'>";
			while($mrow = mysqli_fetch_assoc($msql)){
				echo "<option value='".$mrow['unit_id']."'>".$mrow['unit_sym']."</option>";
			}
			echo "</select>";
			?>
			<br><br>
			<input type="submit" name = "submitS" value="submit">
			<input type = "reset" value = "reset">
		</form>
	</div>
	</div>
</div>
<br><br>
<div class = "row">
<div id="pong" class="pongo"><canvas style="margin: 0 auto;" id="canvas"></canvas>
<img src="cursor.gif" style="display:none;" id="pong-ball"/>
<script>
    if($(window).width() < 600){
      canvasWidth = 375;
      ratio = 1.25;
      message = "TAP TO PLAY";
    }
    else if($(window).width() < 1113) {
      canvasWidth = 600;
      ratio = 1.25;
      message = "TAP TO PLAY";
    }
    else {
      canvasWidth = 1000;
      ratio = 1.5;
      message = "CLICK TO PLAY";
    }

    function getMousePos(canvasDom, mouseEvent) {
      var rect = canvasDom.getBoundingClientRect();
      return {
        x: mouseEvent.clientX - rect.left,
        y: mouseEvent.clientY - rect.top
      };
    }

    function getTouchPos(canvasDom, touchEvent) {
      var rect = canvasDom.getBoundingClientRect();
      return {
        x: touchEvent.touches[0].clientX - rect.left,
        y: touchEvent.touches[0].clientY - rect.top
      };
    }

    // Global Variables
    var DIRECTION = {
      IDLE: 0,
      UP: 1,
      DOWN: 2,
      LEFT: 3,
      RIGHT: 4
    };

    var rounds = [5];
    var colors = ['#ffcce6'];

    // The ball object (The cube that bounces back and forth)
    var Ball = {
      new: function (incrementedSpeed) {
        return {
          width: 36,
          height: 36,
          x: (this.canvas.width / 2) - 9,
          y: (this.canvas.height / 2) - 9,
          moveX: DIRECTION.IDLE,
          moveY: DIRECTION.IDLE,
          speed: incrementedSpeed || 9
        };
      }
    };

    // The paddle object (The two lines that move up and down)
    var Paddle = {
      new: function (side) {
        return {
          width: 18,
          height: this.canvas.height * 0.15,
          x: side === 'left' ? 40 : this.canvas.width - 50,
          y: (this.canvas.height / 2) - 44,
          score: 0,
          move: DIRECTION.IDLE,
          speed: 10
        };
      }
    };

    var Game = {
      initialize: function () {
        this.canvas = document.getElementById('canvas');
        this.context = this.canvas.getContext('2d');
        this.ball_img = document.getElementById("pong-ball");

        this.canvas.width = canvasWidth * 2;
        this.canvas.height = this.canvas.width / ratio;

        this.canvas.style.width = (this.canvas.width / 2) + 'px';
        this.canvas.style.height = (this.canvas.height / 2) + 'px';

        this.player = Paddle.new.call(this, 'left');
        this.paddle = Paddle.new.call(this, 'right');
        this.ball = Ball.new.call(this);

        this.speed_divider = (600*1.6)/this.canvas.height;

        this.paddle.speed = 8;
        this.running = this.over = false;
        this.turn = this.paddle;
        this.timer = this.round = 0;
        this.color = '#ffcce6';

        Pong.menu();
        Pong.listen();
      },

      endGameMenu: function (text) {
        // Change the canvas font size and color
        Pong.context.font = '50px Courier New';
        Pong.context.fillStyle = this.color;

        // Change the canvas color;
        Pong.context.fillStyle = '#000000';

        // Draw the end game menu text ('Game Over' and 'Winner')
        Pong.context.fillText(text,
          Pong.canvas.width / 2,
          Pong.canvas.height / 2 + 15
        );

        setTimeout(function () {
          Pong = Object.assign({}, Game);
          Pong.initialize();
        }, 3000);
      },

      menu: function () {
        // Draw all the Pong objects in their current state
        Pong.draw();

        // Change the canvas font size and color
        this.context.font = '30px Courier New';
        this.context.fillStyle = this.color;

        // Draw the rectangle behind the 'Press any key to begin' text.
        this.context.fillRect(
          this.canvas.width / 2 - 300,
          this.canvas.height / 2 - 70,
          600,
          140
        );

        // Change the canvas color;
        this.context.fillStyle = '#000000';

        // Draw the 'press any key to begin' text
        this.context.fillText(message,
          this.canvas.width / 2,
          this.canvas.height / 2 + 15
        );
      },

      // Update all objects (move the player, paddle, ball, increment the score, etc.)
      update: function () {
        if (!this.over) {
          // If the ball collides with the bound limits - correct the x and y coords.
          if (this.ball.x <= 0) Pong._resetTurn.call(this, this.paddle, this.player);
          if (this.ball.x >= this.canvas.width - this.ball.width) Pong._resetTurn.call(this, this.player, this.paddle);
          if (this.ball.y <= 0) this.ball.moveY = DIRECTION.DOWN;
          if (this.ball.y >= this.canvas.height - this.ball.height) this.ball.moveY = DIRECTION.UP;

          // Move player if they player.move value was updated by a keyboard event
          if (this.player.move === DIRECTION.UP) this.player.y -= this.player.speed;
          else if (this.player.move === DIRECTION.DOWN) this.player.y += this.player.speed;

          // On new serve (start of each turn) move the ball to the correct side
          // and randomize the direction to add some challenge.
          if (Pong._turnDelayIsOver.call(this) && this.turn) {
            this.ball.moveX = this.turn === this.player ? DIRECTION.LEFT : DIRECTION.RIGHT;
            this.ball.moveY = [DIRECTION.UP, DIRECTION.DOWN][Math.round(Math.random())];
            this.ball.y = Math.floor(Math.random() * this.canvas.height - 200) + 200;
            this.turn = null;
          }

          // If the player collides with the bound limits, update the x and y coords.
          if (this.player.y <= 0) this.player.y = 0;
          else if (this.player.y >= (this.canvas.height - this.player.height)) this.player.y = (this.canvas.height - this.player.height);

          // Move ball in intended direction based on moveY and moveX values
          if (this.ball.moveY === DIRECTION.UP) this.ball.y -= (this.ball.speed / 1.5);
          else if (this.ball.moveY === DIRECTION.DOWN) this.ball.y += (this.ball.speed / 1.5);
          if (this.ball.moveX === DIRECTION.LEFT) this.ball.x -= this.ball.speed;
          else if (this.ball.moveX === DIRECTION.RIGHT) this.ball.x += this.ball.speed;

          // Handle paddle (AI) UP and DOWN movement
          if (this.paddle.y > this.ball.y - (this.paddle.height / 2)) {
            if (this.ball.moveX === DIRECTION.RIGHT) this.paddle.y -= this.paddle.speed / 1.6;
            else this.paddle.y -= this.paddle.speed / 4;
          }
          if (this.paddle.y < this.ball.y - (this.paddle.height / 2)) {
            if (this.ball.moveX === DIRECTION.RIGHT) this.paddle.y += this.paddle.speed / 1.6;
            else this.paddle.y += this.paddle.speed / 4;
          }

          // Handle paddle (AI) wall collision
          if (this.paddle.y >= this.canvas.height - this.paddle.height) this.paddle.y = this.canvas.height - this.paddle.height;
          else if (this.paddle.y <= 0) this.paddle.y = 0;

          // Handle Player-Ball collisions
          if (this.ball.x - this.player.width <= this.player.x && this.ball.x >= this.player.x - this.player.width) {
            if (this.ball.y <= this.player.y + this.player.height && this.ball.y + this.ball.height >= this.player.y) {
              this.ball.x = (this.player.x + this.ball.width);
              this.ball.moveX = DIRECTION.RIGHT;

              //beep1.play();
            }
          }

          // Handle paddle-ball collision
          if (this.ball.x - this.ball.width <= this.paddle.x && this.ball.x >= this.paddle.x - this.paddle.width) {
            if (this.ball.y <= this.paddle.y + this.paddle.height && this.ball.y + this.ball.height >= this.paddle.y) {
              this.ball.x = (this.paddle.x - this.ball.width);
              this.ball.moveX = DIRECTION.LEFT;

              //beep1.play();
            }
          }
        }

        // Handle the end of round transition
        // Check to see if the player won the round.
        if (this.player.score === rounds[this.round]) {
          // Check to see if there are any more rounds/levels left and display the victory screen if
          // there are not.
          if (!rounds[this.round + 1]) {
            this.over = true;
            setTimeout(function () { Pong.endGameMenu('Winner!'); $('#pong .win').show(); }, 1000);
          } else {
            // If there is another round, reset all the values and increment the round number.
            this.color = this._generateRoundColor();
            this.player.score = this.paddle.score = 0;
            this.player.speed += 0.5;
            this.paddle.speed += 1;
            this.ball.speed += 1;
            this.round += 1;

            //beep3.play();
          }
        }
        // Check to see if the paddle/AI has won the round.
        else if (this.paddle.score === rounds[this.round]) {
          this.over = true;
          setTimeout(function () { Pong.endGameMenu('Game Over!'); }, 1000);
        }
      },

      // Draw the objects to the canvas element
      draw: function () {
        // Clear the Canvas
        this.context.clearRect(
          0,
          0,
          this.canvas.width,
          this.canvas.height
        );

        // Set the fill style to yellow
        this.context.fillStyle = this.color;

        // Draw the background
        this.context.fillRect(
          0,
          0,
          this.canvas.width,
          this.canvas.height
        );

        // Set the fill style to black (For the paddles and the ball)
        this.context.fillStyle = '#000000';

        // Draw the top border
        this.context.beginPath();
        this.context.setLineDash([15, 22]);
        this.context.moveTo(40, 1);
        this.context.lineTo(this.canvas.width - 40, 2);
        this.context.lineWidth = 3;
        this.context.strokeStyle = '#000000';
        this.context.stroke();

        // Draw the bottom border
        this.context.beginPath();
        this.context.setLineDash([15, 22]);
        this.context.moveTo(40, this.canvas.height - 2);
        this.context.lineTo(this.canvas.width - 40, this.canvas.height - 2);
        this.context.lineWidth = 3;
        this.context.strokeStyle = '#000000';
        this.context.stroke();


        // Draw the net (Line in the middle)
        this.context.beginPath();
        this.context.setLineDash([15, 22]);
        this.context.moveTo((this.canvas.width / 2), this.canvas.height - 10);
        this.context.lineTo((this.canvas.width / 2), 10);
        this.context.lineWidth = 3;
        this.context.strokeStyle = '#000000';
        this.context.stroke();

        // Set the default canvas font and align it to the center
        this.context.font = '50px Courier New';
        this.context.textAlign = 'center';

        // Draw the players score (left)
        this.context.fillText(
          this.player.score.toString(),
          (this.canvas.width / 2) - 100,
          50
        );

        // Draw the paddles score (right)
        this.context.fillText(
          this.paddle.score.toString(),
          (this.canvas.width / 2) + 100,
          50
        );

        // Draw the Player
        this.context.fillRect(
          this.player.x,
          this.player.y,
          this.player.width,
          this.player.height
        );

        // Draw the Paddle
        this.context.fillRect(
          this.paddle.x,
          this.paddle.y,
          this.paddle.width,
          this.paddle.height
        );

        // Draw the Ball
        if (Pong._turnDelayIsOver.call(this)) {
          this.context.drawImage(this.ball_img, this.ball.x, this.ball.y);
        }

        // Change the font size for the center score text
        this.context.font = '30px Courier New';

        // Draw the winning score (center)
        

        // Change the font size for the center score value
        this.context.font = '40px Courier New';

        
      },

      loop: function () {
        Pong.update();
        Pong.draw();

        // If the game is not over, draw the next frame.
        if (!Pong.over) requestAnimationFrame(Pong.loop);
      },

      listen: function () {

        Pong.canvas.addEventListener("mousedown", function (e) {
          if (Pong.running === false) {
            Pong.running = true;
            window.requestAnimationFrame(Pong.loop);
          }
        }, false);

        Pong.canvas.addEventListener("mousemove", function (e) {
          mousePos = getMousePos(canvas, e);
          if(mousePos.y > 0) {
            Pong.player.y = (mousePos.y*2)-22;
          }
        }, false);

        Pong.canvas.addEventListener("touchstart", function (e) {
          e.preventDefault();
          e.stopPropagation();
          mousePos = getTouchPos(canvas, e);
          if (Pong.running === false) {
            Pong.running = true;
            window.requestAnimationFrame(Pong.loop);
          }
          if(mousePos.y > 0) {
            Pong.player.y = (mousePos.y*2)-22;
          }
          return false;
        }, false);

        Pong.canvas.addEventListener("touchmove", function (e) {
          e.preventDefault();
          e.stopPropagation();
          mousePos = getTouchPos(canvas, e);
          if (Pong.running === false) {
            Pong.running = true;
            window.requestAnimationFrame(Pong.loop);
          }
          if(mousePos.y > 0) {
            Pong.player.y = (mousePos.y*2)-22;
          }
          return false;
        }, false);
      },

      // Reset the ball location, the player turns and set a delay before the next round begins.
      _resetTurn: function(victor, loser) {
        this.ball = Ball.new.call(this, this.ball.speed);
        this.turn = loser;
        this.timer = (new Date()).getTime();

        victor.score++;
        //beep2.play();
      },

      // Wait for a delay to have passed after each turn.
      _turnDelayIsOver: function() {
        return ((new Date()).getTime() - this.timer >= 1000);
      },

      // Select a random color as the background of each level/round.
      _generateRoundColor: function () {
        var newColor = colors[Math.floor(Math.random() * colors.length)];
        if (newColor === this.color) return Pong._generateRoundColor();
        return newColor;
      }
    };

    var Pong = Object.assign({}, Game);
    Pong.initialize();
</script>

<script>
$(function(){
  $(document).click(function(){
    $('body').removeAttr('style');
  });

  $('#canvas').click(function(e){
    e.stopPropagation();
    $('body').css({overflow: 'hidden'});
  });

  $('')
});
</script></div>
</div>

</div>
</div>
</body>
</html>
