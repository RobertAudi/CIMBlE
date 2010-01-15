<?php
$this->firephp->fb($comments); // DEBUG <-
?>

<?php foreach ($comments as $comment): ?>
	<div id="comment-<?php echo $comment->id; ?>" class="comment">
		<div class="comment-body comment-depth-<?php echo $comment->depth; ?>">
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
			<p><?php echo format_date($comment->created_at); ?></p>
			<?php if ($comment->depth <= $max_comment_depth): ?>
				<p><a href="<?php echo strip_trailing_slash(current_url()) . '/reply/' . $comment->id . '#comment-form'; ?>">Reply</a></p>
			<?php endif ?>
		</div> <!--END OF comment-meta-->
	</div> <!--END OF comment-->
<?php endforeach; ?>