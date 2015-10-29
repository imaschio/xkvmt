intelli.common = function()
{
	var dialog = null;

	return {
		init: function()
		{
		},

		reportBrokenListing: function(id)
		{
			if (confirm(intelli.lang.do_you_want_report_broken))
			{
				$.post('ajax.php', {action: 'report', id: id}, function(data)
				{
					if (!data.error)
					{
						alert(data.msg);
					}
				}, "json");
			}

			return false;
		},

		actionFavorites: function(listing_id, account_id, action)
		{
			var msg = ('add' == action) ? intelli.lang.add_favorite : intelli.lang.remove_favorite;

			if (confirm(msg))
			{
				$.post("ajax.php", { action: 'favorites', account_id: account_id, listing_id: listing_id, trigger: action}, function(data)
				{
					window.location.href = window.location.href;
				});
			}

			return false;
		},

		moveListing: function(id, cat)
		{
			var html = '';
			var idLink = id;

			if (!confirm(intelli.lang.listing_move_confirmation))
			{
				return false;
			}

			$("tr[class='tree']").remove();

			html += '<tr class="tree"><td>';
			html += '<fieldset style="collapsible"><legend><span id="change_category_text">'+ intelli.lang.move_listing +'</span></legend>';	
			html += '<div id="tree"></div>';
			html += '</fieldset>';
			html += '</td></tr>';

			$('#tdlisting' + idLink).parent().after(html);
			
			var moveTree = new intelli.tree({
				id: 'tree',
				type: 'radio',
				state: cat,
				expanded: '0',
				hideRoot: false,
				callback: function()
				{
					if (!confirm(intelli.lang.listing_fin_move_confirmation))
					{
						return false;
					}
					
					var idCat = $(this).attr('id').split('_')[2];

					$.get('ajax.php', {action: 'moving', idcat: idCat, idlink: idLink}, function(data)
					{
						$('#tree').html(data);
						$('#tdlisting' + idLink).parent().remove();
					});
				}
			});

			moveTree.init();
		},

		deleteListing: function(listing)
		{
			if (confirm(intelli.lang.do_you_want_delete_listing))
			{
				$.post('edit-account.php', {listing: listing, delete_listing: 1}, function(data)
				{
					alert(data);

					window.location.href = intelli.config.esyn_url + 'account-listings.html';

					return true;
				});
			}

			return false;
		},

		toggleFieldset: function (fieldset)
		{
			var content;
			if ($(fieldset).is('.collapsed'))
			{
				content = $('> div', fieldset).hide();

				$(fieldset).removeClass('collapsed');

				content.slideDown(300, function()
				{
					// Make sure we open to height auto
					$(this).css('height', 'auto');
				});
			}
			else
			{
				$('> div', fieldset).slideUp('medium', function()
				{
					$(this.parentNode).addClass('collapsed');
				});
			}
		}
	}
}();