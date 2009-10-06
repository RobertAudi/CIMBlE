<?php foreach ($comments as $comment): ?>
	<div id="comment-<?php echo $comment->id; ?>" class="comment">
		<div class="comment-body">
			<?php echo $comment->body; ?>
		</div> <!--END OF comment-body-->
		<div class="comment-meta">
			<p>Written by
				<?php if (!empty($comment->author_website)): ?>
					<a href="<?php echo $comment->author_website; ?>"><?php echo $comment->author_name; ?></a>
				<?php else: ?>
					<?php echo $comment->author_name; ?>
				<?php endif; ?>
			</p>
			<p><?php echo $comment->created_at; ?></p>
		</div> <!--END OF comment-meta-->
	</div> <!--END OF comment-->
<?php endforeach; ?>