// ----------------------------------------------------------------------------
// markItUp!
// ----------------------------------------------------------------------------
// Copyright (C) 2011 Jay Salvat
// http://markitup.jaysalvat.com/
// ----------------------------------------------------------------------------
// Html tags
// http://en.wikipedia.org/wiki/html
// ----------------------------------------------------------------------------
// Basic set. Feel free to add more tags
// ----------------------------------------------------------------------------
var systemObject = {

    popUp : function(pageURL, title, w, h) {
                var left = ( screen.width / 2 ) - ( w/2 );
                var top  = ( screen.height / 2 ) - ( h/2 );
                window.open(pageURL, title,
                            'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left );

																												},

		markItUp : function(obj) {
																														if ( obj.length > 0 )
																														{
                                var mySettings = {
                                	onShiftEnter:  	{keepDefault:false, replaceWith:'<br />\n'},
                                	onCtrlEnter:  	{keepDefault:false, openWith:'\n<p>', closeWith:'</p>'},
                                	onTab:    		{keepDefault:false, replaceWith:'    '},
                                	markupSet:[
                                        		{name:'Bold', key:'B', openWith:'(!(<strong>|!|<b>)!)', closeWith:'(!(</strong>|!|</b>)!)' },
                                        		{name:'Italic', key:'I', openWith:'(!(<em>|!|<i>)!)', closeWith:'(!(</em>|!|</i>)!)'  },
                                        		{name:'Stroke through', key:'S', openWith:'<del>', closeWith:'</del>' },
                                        		{separator:'---------------' },
                                        		{
                                        		 name:'Bulleted List',
                                        		 openWith:'    <li>',
                                        		 closeWith:'</li>',
                                        		 multiline:true,
                                        		 openBlockWith:'<ul>\n',
                                        		 closeBlockWith:'\n</ul>'
                                                },
                                        		{
                                        		 name:'Numeric List',
                                        		 openWith:'    <li>',
                                        		 closeWith:'</li>',
                                        		 multiline:true,
                                        		 openBlockWith:'<ol>\n',
                                        		 closeBlockWith:'\n</ol>'
                                                },
                                        		{separator:'---------------' },

                                        		{
                                        		 name:'Picture',
                                        		 key:'P',
 																																										call: function()
																																											{
																																												var element_id = obj.attr('id');
																																												systemObject.popUp('/file_manager/index.php?returnID='+element_id, 'ImageManager', 900, 600 );
																																											}

																																										},

																																										{
																																												name:'Link',
																																												key:'L',
																																												openWith:'<a href="[![Link:!:http://]!]"(!( title="[![Title]!]")!)>',
																																												closeWith:'</a>',
																																												placeHolder:'Your text to link...'
																																										},

                                        		{separator:'---------------' },

                                        		{
                                        		 name:'Clean',
                                        		 className:'clean',
                                        		 replaceWith:function(markitup)
                                        		  { return markitup.selection.replace(/<(.*?)>/g, "") }
																																										},

                                        		{name:'Preview', className:'preview',  call:'preview'}
	                                       ]

                                    }

                                  obj.markItUp(mySettings);

                                }
																														}

																												};


$(function() {

    systemObject.markItUp( $('textarea') );

});

/*

var mySettings = {
	onShiftEnter:  	{keepDefault:false, replaceWith:'<br />\n'},
	onCtrlEnter:  	{keepDefault:false, openWith:'\n<p>', closeWith:'</p>'},
	onTab:    		{keepDefault:false, replaceWith:'    '},
	markupSet:  [
		{name:'Bold', key:'B', openWith:'(!(<strong>|!|<b>)!)', closeWith:'(!(</strong>|!|</b>)!)' },
		{name:'Italic', key:'I', openWith:'(!(<em>|!|<i>)!)', closeWith:'(!(</em>|!|</i>)!)'  },
		{name:'Stroke through', key:'S', openWith:'<del>', closeWith:'</del>' },
		{separator:'---------------' },
		{name:'Bulleted List', openWith:'    <li>', closeWith:'</li>', multiline:true, openBlockWith:'<ul>\n', closeBlockWith:'\n</ul>'},
		{name:'Numeric List', openWith:'    <li>', closeWith:'</li>', multiline:true, openBlockWith:'<ol>\n', closeBlockWith:'\n</ol>'},
		{separator:'---------------' },
		{
				name:'Picture',
				key:'P',
				call: function() {
						var element_id = obj.attr('id');
						systemObject.popUp('/js/pdw_file_browser/index.php?returnID='+element_id, 'ImageManager', 900, 500 );
				}

		replaceWith:'<img src="[![Source:!:http://]!]" alt="[![Alternative text]!]" />'


		},
		{name:'Link', key:'L', openWith:'<a href="[![Link:!:http://]!]"(!( title="[![Title]!]")!)>', closeWith:'</a>', placeHolder:'Your text to link...' },
		{separator:'---------------' },
		{name:'Clean', className:'clean', replaceWith:function(markitup) { return markitup.selection.replace(/<(.*?)>/g, "") } },
		{name:'Preview', className:'preview',  call:'preview'}
	]
}

*/
