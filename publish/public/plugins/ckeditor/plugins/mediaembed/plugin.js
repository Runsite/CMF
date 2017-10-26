/*
* Embed Media Dialog based on http://www.fluidbyte.net/embed-youtube-vimeo-etc-into-ckeditor
*
* Plugin name:      mediaembed
* Menu button name: MediaEmbed
*
* Youtube Editor Icon
* http://paulrobertlloyd.com/
*
* @author Fabian Vogelsteller [frozeman.de]
* @version 0.6
*/

(function() {
	'use strict';
	var fixSrc =
		// In Firefox src must exist and be different than about:blank to emit load event.
		CKEDITOR.env.gecko ? 'javascript:true' : // jshint ignore:line
		// Support for custom document.domain in IE.
		CKEDITOR.env.ie ? 'javascript:' + // jshint ignore:line
						'void((function(){' + encodeURIComponent(
							'document.open();' +
							'(' + CKEDITOR.tools.fixDomain + ')();' +
							'document.close();'
						) + '})())' :
		// In Chrome src must be undefined to emit load event.
						'javascript:void(0)'; // jshint ignore:line

	CKEDITOR.plugins.add( 'mediaembed', {
		icons: 'mediaembed', // %REMOVE_LINE_CORE%
		hidpi: true, // %REMOVE_LINE_CORE%
		lang: 'en,es',
		requires: 'widget,dialog',
		init: function( editor ) {
			var cls = editor.config.mediaEmbedClass || 'embed-content',
				lang = editor.lang.mediaembed;

			editor.widgets.add( 'mediaembed', {
				dialog: 'mediaembed',
				button: lang.button,
				mask: true,
				allowedContent: 'div(!' + cls + ')',
				pathName: lang.pathName,

				template: '<div class="' + cls + '"></div>',

				parts: {
					embed: 'div'
				},

				defaults: {
					html: ''
				},

				init: function() {
					var iframe = this.parts.embed.getChild( 0 );

					// Check if span contains iframe and create it otherwise.
					if ( !iframe || iframe.type != CKEDITOR.NODE_ELEMENT || !iframe.is( 'iframe' ) ) {
						iframe = new CKEDITOR.dom.element( 'iframe' );
						iframe.setAttributes( {
							style: 'border:0;width:0;height:0',
							scrolling: 'no',
							frameborder: 0,
							allowTransparency: true,
							src: fixSrc
						} );
						this.parts.embed.setHtml( '' );
						this.parts.embed.append( iframe );
					}

					// Wait for ready because on some browsers iFrame will not
					// have document element until it is put into document.
					// This is a problem when you crate widget using dialog.
					this.once( 'ready', function() {
						// Src attribute must be recreated to fix custom domain error after undo
						// (see iFrame.removeAttribute( 'src' ) in frameWrapper.load).
						if ( CKEDITOR.env.ie ) {
							iframe.setAttribute( 'src', fixSrc );
						}

						this.frameWrapper = new CKEDITOR.plugins.mediaembed.frameWrapper( iframe, editor );
						this.frameWrapper.setValue( this.data.html );
					} );
				},

				data: function() {
					if ( this.frameWrapper ) {
						this.frameWrapper.setValue( this.data.html );
					}
				},

				upcast: function( el, data ) {
					if ( !( el.name == 'div' && el.hasClass( cls ) ) ) {
						return;
					}

					if ( !el.children.length ) {
						return;
					}

					data.html = el.getHtml();
					data.html = editor.dataProcessor.toDataFormat( data.html );

					el.setHtml( '' );

					return el;
				},

				downcast: function( el ) {
					el.setHtml( this.data.html );
					return el;
				}
			} );

			CKEDITOR.dialog.add( 'mediaembed', function ( editor ) {
				return {
					title : lang.dialogTitle,
					minWidth : 550,
					minHeight : 200,
					contents : [{
						id: 'iframe',
						expand: true,
						elements: [{
							id: 'embedArea',
							type: 'textarea',
							label: lang.dialogLabel,
							setup: function( widget ){
								this.setValue( widget.data.html );
							},
							commit: function( widget ){
								var html = this.getValue();
								// filter html
								html = editor.dataProcessor.toHtml( html );
								html = editor.dataProcessor.toDataFormat( html );
								widget.setData( 'html', html );
							}
						}]
					}]
				};
			});
		}
	});

	CKEDITOR.plugins.mediaembed = {};
	CKEDITOR.plugins.mediaembed.loadingIcon = CKEDITOR.plugins.get( 'mediaembed' ).path + 'images/loader.gif';
	CKEDITOR.plugins.mediaembed.frameWrapper = function( iFrame, editor ) {
		var doc = iFrame.getFrameDocument();

		var loadedHandler = CKEDITOR.tools.addFunction( function() {
			editor.fire( 'lockSnapshot' );

			iFrame.setStyles({
				height: 0,
				width: 0
			});

			doc.getById( 'preview' ).remove();

			editor.fire( 'unlockSnapshot' );

			// ping for content height changing and update iframe height correspondingly
			var prevHeight, interval, timeout;

			var stop = function() {
				window.clearInterval( interval );
			};

			interval = window.setInterval( function() {
				var height = Math.max( doc.$.body.offsetHeight, doc.$.documentElement.offsetHeight );

				if ( height === prevHeight ) {
					timeout = window.setTimeout( stop, 2000 );
					return;
				}

				editor.fire( 'lockSnapshot' );

				window.clearTimeout( timeout );

				iFrame.setStyles({
					height: height + 'px',
					width: '100%'
				});

				prevHeight = height;

				editor.fire( 'unlockSnapshot' );
			}, 500 );

			editor.on( 'contentDomUnload', stop );
		} );

		function setValue( value ) {
			editor.fire( 'lockSnapshot' );

			doc = iFrame.getFrameDocument();

			// Because of IE9 bug in a src attribute can not be javascript
			// when you undo (#10930). If you have iFrame with javascript in src
			// and call insertBefore on such element then IE9 will see crash.
			if ( CKEDITOR.env.ie ) {
				iFrame.removeAttribute( 'src' );
			}

			iFrame.setStyles({
				height: '16px',
				width: '100%'
			});

			doc.write( '<!DOCTYPE html>' +
						'<html>' +
						'<head>' +
							'<meta charset="utf-8">' +
							'<script>' +
								// Get main CKEDITOR form parent.
								'function getCKE() {' +
									'if ( typeof window.parent.CKEDITOR == \'object\' ) {' +
										'return window.parent.CKEDITOR;' +
									'} else {' +
										'return window.parent.parent.CKEDITOR;' +
									'}' +
								'}' +

								'function load() {' +
									'getCKE().tools.callFunction(' + loadedHandler + ');' +
								'};' +
							'</script>' +

						'</head>' +
						'<body style="padding:0;margin:0;background:transparent;overflow:hidden" onload="load();">' +
							'<span id="preview"><img src=' + CKEDITOR.plugins.mediaembed.loadingIcon + ' alt=' + editor.lang.mediaembed.loading + '></span>' +
							value +
						'</body>' +
						'</html>' );

			editor.fire( 'unlockSnapshot' );
		}

		return {
			setValue: function( value ) {
				setValue( value );
			}
		};
	};
})();
