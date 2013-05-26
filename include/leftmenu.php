<!-- menu for Classroom -->
<?php 
		
			
function showLeftMenu($classKoId, $content){
	$online = false;
			if(!empty($_SESSION['online']))
			$online = true;
			
	if($online){
		$userIsAdmin = ($classKoId['adminid'] == $_SESSION['userid'])? true: false;
	}

	?>
	<div class="ovMenuLeft">
		<?php if($online)
		{	
			if($userIsAdmin)
			{ ?>
				<div class="ovMenuLeftList">
					<a href="classroom.php?go=view&id=<?php echo $_SESSION['currentClass']; ?>" title="create a quiz">Discussion Board
					&nbsp;<img src="imgs/class_about.gif" align="absmiddle" />
					</a>
				</div>
				<div class="ovMenuLeftList">
					<a href="classroom.php?go=acts&view=notice" title="create a quiz">Manage Notices
					&nbsp;<img src="imgs/class_fav.gif" align="absmiddle" />
					</a>
				</div>
					
				<div class="ovMenuLeftList">
					<a href="classroom.php?go=acts&view=event" title="Update classroom schedules">Manage Events
					&nbsp;<img src="imgs/class_review.gif" align="absmiddle" />
					</a>
				</div>
				
				<div class="ovMenuLeftList">
					<a href="classroom.php?go=acts&view=assignment" title="Add assignments">Assignments
					&nbsp;<img src="imgs/class_auto.gif" align="absmiddle" />
					</a>
				</div>
				
				<div class="ovMenuLeftList">
					<a href="classroom.php?go=acts&view=poll" title="Conduct a poll">Manage Polls
					&nbsp;<img src="imgs/class_poll.gif" align="absmiddle" />
					</a>
				</div>
				
				<div class="ovMenuLeftList">
					<a href="classroom.php?go=acts&view=members" title="Manage the students">My Students
					&nbsp;<img src="imgs/class_mates.gif" align="absmiddle" />
					</a>
				</div>
				
				<div class="ovMenuLeftList">
					<a href="classroom.php?go=acts&view=information" title="Update information">Update Info.
					&nbsp;<img src="imgs/class_edit.gif" align="absmiddle" />
					</a>
				</div>
				
				<!--div class="ovMenuLeftList">
					&nbsp;<img src="imgs/class_act.gif" align="absmiddle" />&nbsp;
					<a href="classroom.php?go=acts&view=stats" title="View statistical info">Classroom statistics
					</a>
				</div-->
		<?php }
			else /* user is not admin */
			{ ?>
				<div class="ovMenuLeftList">
					<a href="classroom.php?go=view&id=<?php echo $_SESSION['currentClass']; ?>" title="View Notice Board">Discussion Board
					&nbsp;<img src="imgs/class_about.gif" align="absmiddle" />
					</a>
				</div>
				
				<div class="ovMenuLeftList">
					<a href="classroom.php?go=acts&view=notice" title="View Notice Board">Notice Board
					&nbsp;<img src="imgs/class_fav.gif" align="absmiddle" />
					</a>
				</div>
					
				<div class="ovMenuLeftList">
					<a href="classroom.php?go=acts&view=event" title="Update classroom schedules">New Events
					&nbsp;<img src="imgs/class_review.gif" align="absmiddle" />
					</a>
				</div>
				
				<div class="ovMenuLeftList">
					<a href="classroom.php?go=acts&view=assignment" title="View and submit assignments">Assignments
					&nbsp;<img src="imgs/class_quiz.gif" align="absmiddle" />
					</a>
				</div>
				
				<div class="ovMenuLeftList">
					<a href="classroom.php?go=acts&view=poll" title="Participate in a poll">Active Polls
					&nbsp;<img src="imgs/class_poll.gif" align="absmiddle" />
					</a>
				</div>
				
				<div class="ovMenuLeftList">
					<a href="classroom.php?go=acts&view=members" title="View your classmates">Classmates
					&nbsp;<img src="imgs/class_mates.gif" align="absmiddle" />
					</a>
				</div>
					
				<div class="ovMenuLeftList">
					<a href="classroom.php?go=acts&view=blog" title="See teacher's blog">Teacher's blog
					&nbsp;<img src="imgs/class_review.gif" align="absmiddle" />
					</a>
				</div>
				
				<div class="ovMenuLeftList">
					<a href="classroom.php?go=acts&view=information" title="Learn more about your classroom">Information
					&nbsp;<img src="imgs/class_update.gif" align="absmiddle" />
					</a>
				</div>
		<?php }
			?>
			
	<?php } ?>
	</div><!-- ovMenuLeft ends -->
<?php
} /* function yaha sakiyo */
?>