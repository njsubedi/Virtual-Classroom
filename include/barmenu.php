<!-- menu for Classroom -->
<?php 
		
			
function showBarMenu($classKoId, $content){
	$online = false;
			if(!empty($_SESSION['online']))
			$online = true;
			
	if($online){
		$userIsAdmin = ($classKoId['adminid'] == $_SESSION['userid'])? true: false;
	}

	?>
		<ul class="ovMenuTopBar">
			<?php
				if($online)
				{
				/* user is online but has not enrolled in any classes yet*/
			?>		
					<li>
						<a href="classroom.php?go=start" title="Start a new classroom">Start new classroom</a>
					</li>
					<li>
						<a href="classroom.php?go=admin" title="Join a classroom">Classrooms I created
						</a>
					</li>
				<?php
					if(!empty($_SESSION['classid']))
						{
				?>
							<li>
								<a title="My Classroom" href="classroom.php?go=view&id=<?php echo $_SESSION["classid"]; ?>">
								<?php if(isset($_SESSION["classname"])) echo $_SESSION['classname']; else echo 'Favourite'; ?>
								</a>
							</li>
							
							<li>
								<a href="classroom.php?go=enroll">Classrooms I enrolled
								</a>
							</li>
				<?php	}
					else
					{
				?>
						<li>
							<a href="classroom.php?go=enroll" title="Join a classroom">Find classrooms
							</a>
						</li>
				<?php
					}
				}
				?>
		
		<li>
			<a href="classroom.php?go=about" title="Learn more about classrooms">About OVclass
			</a>
		</li>
	</ul>
<?php
} /* function yaha sakiyo */
?>