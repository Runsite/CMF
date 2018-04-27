/**
 * @license Copyright (c) 2003-2017, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
	config.skin = 'office2013';
	config.extraPlugins = 'mediaembed,simpleuploads';
	config.allowedContent = true;
	config.height = 350;

	config.toolbar_compact =
	[
		{ name: 'tools', items : [ 'Maximize', 'Source' ] },
		{ name: 'clipboard', items : [ 'Cut','Copy','Paste','PasteText','PasteFromWord','-'] },
		{ name: 'basicstyles', items : [ 'Bold','Italic','Underline','Strike','-','RemoveFormat' ] },
		{ name: 'paragraph', items : [ 'NumberedList','BulletedList','-', '-','Blockquote',
		'-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock' ] },
		{ name: 'links', items : [ 'Link','Unlink' ] },
		'/',
		{ name: 'styles', items : [ 'Styles','Format','Font','FontSize' ] },
		{ name: 'colors', items : [ 'TextColor','BGColor' ] },
		{ name: 'insert', items : [ 'Image', 'Mediaembed', 'Table','HorizontalRule' ] },
		{ name: 'upload' },
	];

	config.removeDialogTabs = 'image:Link;image:advanced;link:target;link:advanced';

	config.filebrowserUploadUrl = '/admin/api/ckeditor/images-upload';
	config.filebrowserImageUploadUrl = '/admin/api/ckeditor/images-upload';
	config.image_prefillDimensions = false;
	config.contentsCss = ['/vendor/runsite/asset/bower_components/bootstrap/dist/css/bootstrap.min.css', '/vendor/runsite/asset/plugins/ckeditor/style.css'];


	config.simpleuploads_acceptedExtensions ='7z|avi|csv|doc|docx|flv|gif|gz|gzip|jpeg|jpg|mov|mp3|mp4|mpc|mpeg|mpg|ods|odt|pdf|png|ppt|pxd|rar|rtf|tar|tgz|txt|vsd|wav|wma|wmv|xls|xml|zip';
};


CKEDITOR.on( 'dialogDefinition', function( ev )
{
  var dialogName = ev.data.name;
  var dialogDefinition = ev.data.definition;

  if (dialogName == 'table') {

     // Get the advanced tab reference
     var infoTab2 = dialogDefinition.getContents('advanced');

     //Set the default

     // Remove the 'Advanced' tab completely
     dialogDefinition.removeContents('advanced');

     // Get the properties tab reference
     var infoTab = dialogDefinition.getContents('info');

     // Remove unnecessary bits from this tab
     infoTab.remove('txtBorder');
     infoTab.remove('cmbAlign');
     infoTab.remove('txtWidth');
     infoTab.remove('txtHeight');
     infoTab.remove('txtCellSpace');
     infoTab.remove('txtCellPad');
     infoTab.remove('txtCaption');
     infoTab.remove('txtSummary');
  }
});

CKEDITOR.on('instanceReady', function(evt) {
    var editor = evt.editor;

    editor.on('focus', function(e) {
        $('#cke_'+editor.name).addClass('ckeditor-focused');
    });

    editor.on('blur', function(e) {
        $('#cke_'+editor.name).removeClass('ckeditor-focused');
    });
});
