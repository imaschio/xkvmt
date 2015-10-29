<div class="fieldset" id="fieldTerms">
	<h4 class="title">{lang key='terms_of_submission'}</h4>
	<div class="content">
		<div class="well">{lang key='terms_of_submission_text'}</div>

		<label for="terms_check" class="checkbox">
			<input type="checkbox" name="terms_check" value="1" id="terms_check" {if isset($smarty.post.terms_check) && $smarty.post.terms_check eq '1'}checked="checked"{/if} />
			{lang key='terms_i_agree'} <a data-toggle="modal" href="#submission_terms">{lang key='terms_of_submission'}</a>
		</label>
	</div>

	<div class="modal hide" id="submission_terms">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h3>{lang key='terms_of_submission'}</h3>
		</div>

		<div class="modal-body">{lang key='terms_of_submission_text'}</div>
	</div>
</div>