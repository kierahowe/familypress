
jQuery( document ).ready( function () { 
	// Event page
	if ( document.getElementById( 'fp_event_data' ) ) { 
		function changeSelect ( e ) { 
			jQuery( '.fp_event_type_inputs' ).each( function( index, item ) {
				jQuery( item ).hide();
			} );
			jQuery( document.getElementById( 'fp_event_' + e.target.value ) ).show();
		}

		jQuery('#fp_event_type').on( 'change', changeSelect );

		changeSelect ( { target: document.getElementById( 'fp_event_type' ) } );

	}

	// Settings pages
	if ( document.getElementById( 'select2_create' ) ) { 
		jQuery('.fp_settings_page_select').select2( {
			ajax: {
				url: ajaxurl,
				allowClear: true,
				dataType: 'json', 
				delay: 100,
				data: function (params) {
					return { search: params.term, action: 'fp_select_page' };
				},
				processResults: function (data) {
					return {
						results: data
					};
				}
			}
		});

		jQuery( '#select1_create').click( function( e ) { 
			createPage('person');
		});
		jQuery( '#select2_create').click( function( e ) { 
			createPage('map');
		});

		function createPage( type ) {  
			jQuery.ajax( ajaxurl, {
				data: { 
					type: type, 
					action: 'fp_create_page'
				},
				success: function ( data, status ) { 
					if ( typeof data['error'] !== 'undefined' ) { 
						alert( 'Failed to create page: ' + data['error'] );
					} else { 
						var id = '';
						if ( type === 'person' ) { id = 'select1'; } 
						if ( type === 'map' ) { id = 'select2'; } 
						if ( document.getElementById( 'fp_page_' + id + '_href' ) !== null ) { 
							document.getElementById( 'fp_page_' + id + '_href' ).href = data['url'];
						} else { 
							jQuery( '#select2_create' ).after( ' <a id="fp_page_' + id + '_href" href="' + data['url'] + '">Edit Page</a>');
						}
						jQuery( '#select2-fp_settings_page_' + id + '-container' ).html( data['post_title'] );
					}
				}, 
				error: function( x, err, e ) { 
					alert( err );
				}
			});
		}
	}

	jQuery('.fp_post_select').each( function( i, item ) { 
		var settings = JSON.parse( item.getAttribute( 'js-settings' ) );
		jQuery( item ).select2( {
			width: '60%',
			ajax: {
				url: ajaxurl,
				dataType: 'json', 
				delay: 100,
				data: function (params) {
					return { search: params.term, action: 'fp_select_page', post_type: settings.post_type };
				},
				processResults: function (data) {
					return {
						results: data
					};
				}
			}
		});

		jQuery( document.getElementById( item.id + '_addnew_button' ) ).click( function( e ) { 
			var name = e.target.id.substring( 0, e.target.id.length - 14 );
			var val = document.getElementById( name + '_addnew' ).value;
	
			var settings = JSON.parse( document.getElementById( name ).getAttribute( 'js-settings' ) );

			if ( val === '' ) { 
				alert( 'You must enter a value' );
				return;
			}
			jQuery.ajax( ajaxurl, {
				data: { 
					action: 'fp_create_item',
					value: val,
					post_type: settings.post_type
				},
				success: function ( data, status ) { 
					if ( typeof data['error'] !== 'undefined' ) { 
						alert( 'Failed to create page: ' + data['error'] );
					} else { 
						if ( document.getElementById( name + '_href' ) !== null ) { 
							document.getElementById( name + '_href' ).href = data['url'];
						} else { 
							jQuery( document.getElementById( name ) ).after( ' <a id="' + name + '_href" href="' + data['url'] + '">Edit Page</a>');
						}
						jQuery( document.getElementById( 'select2-' + name + '-container' ) ).html( data['post_title'] );
						
						var option = document.createElement("option");
						option.text = data['post_title'];
						option.value = data['postnum'];
						document.getElementById( name ).innerHTML = '';
						document.getElementById( name ).add( option );
						document.getElementById( name ).value = data['postnum'];

						closeAddNew( { 'target': { 'id': name + '_addnew_cancel_button' } } );
					}
				}, 
				error: function( x, err, e ) { 
					alert( err );
				}
			});
		});
		jQuery( document.getElementById( item.id + '_addnew_cancel_button' ) ).click( closeAddNew );
		jQuery( document.getElementById( item.id + '_addnew_init' ) ).click( function( e ) { 
			var name = e.target.id.substring( 0, e.target.id.length - 12 );
			jQuery( document.getElementById( name + '_overall' ) ).hide();
			jQuery( document.getElementById( name + '_addnew_text' ) ).show();
		});

		function closeAddNew( e ) { 
			var name = e.target.id.substring( 0, e.target.id.length - 21 );
			jQuery( document.getElementById( name + '_overall' ) ).show();
			jQuery( document.getElementById( name + '_addnew_text' ) ).hide();
		}
	});

	jQuery('#fp_add_divorce').each( function( i, item ) { 
		jQuery( document.getElementById( 'fp_divorce_addnew_cancel_button' ) ).click( function( e ) { 
			jQuery( document.getElementById( 'fp_add_divorce' ) ).hide();
		});
		jQuery( document.getElementById( 'fp_divorce_button' ) ).click( function( e ) { 
			jQuery( document.getElementById( 'fp_add_divorce' ) ).show();
		});

		jQuery( document.getElementById( 'fp_divorce_addnew_button' ) ).click( function( e ) { 
			var val = document.getElementById( 'fp_divorce_addnew' ).value;
			var marr = document.getElementById( 'fp_divorce_addnew' ).getAttribute('js-marr');
			if( val === '' ) { 
				alert( 'You must enter a date' );
				return;
			}
			jQuery.ajax( ajaxurl, {
				data: { 
					action: 'fp_create_event',
					type: 'DIVO',
					date: val,
					marrage: marr
				},
				success: function ( data, status ) { 
					if ( typeof data['error'] !== 'undefined' ) { 
						alert( 'Failed to create page: ' + data['error'] );
					} else { 
						jQuery( document.getElementById( 'fp_add_divorce' ) ).html( ' <a id="' + name + '_href" href="' + data['url'] + '">' + data['date'] + '</a>');
						jQuery( document.getElementById( 'select2-' + name + '-container' ) ).html( data['post_title'] );
					}
				}, 
				error: function( x, err, e ) { 
					alert( err );
				}
			});
		});

	});
});