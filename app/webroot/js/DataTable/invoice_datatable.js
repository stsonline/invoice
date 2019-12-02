var oTable;
var giRedraw = false;

$(document).ready(
function() 
{
	$('#invoice-table').dataTable( 
			{
				"aaSorting": [ [ 0, "desc" ],],
				//"bJQueryUI": true,
				"sDom": 'T<"clear">lfrtip',

				"oTableTools": {
		            "aButtons": [ 
		                         /* Pre-defined Buttons in TableTools 
		                          * 
		                          * 
		                          * http://datatables.net/extras/tabletools/buttons   see list
		                         "copy",
		                         */
		                        // "csv",
		                         /*"print",
		                          "pdf",
		                          */
		                          /* Custom defined button */
		                          { 
			            				"sExtends":    "text",
		                    			"sButtonText": "View Invoice Pdf",
		                                 "fnClick": function ( nButton, oConfig, oFlash ) {
		                                        buttonClicked( 'view' );
		                                    }	
		                    	  }    ,		                         
		                          /* Custom defined button*/
		                          { 
			            				"sExtends":    "collection",
		                    			"sButtonText": "Send reminder",
		                    			"aButtons":[
		                    			            {"sExtends":    "text",
		                    			            	"sButtonText":	"send invoice",
		                    			            	"fnClick": 	function ( nButton, oConfig, oFlash ) 
		                    			            				{
		    		                                        			buttonClicked( 'invoice' );
		                    			            				},
		                    			            },
		                    			            {"sExtends":    "text",
		                    			            	"sButtonText":	"send reminder",
		                    			            	"fnClick": 	function ( nButton, oConfig, oFlash ) 
                			            							{
		                                        						buttonClicked( 'reminder' );
                			            							},
		                    			            },
		                    			            {"sExtends":    "text",
		                    			            	"sButtonText":	"send final",
		                    			            	"fnClick": 	function ( nButton, oConfig, oFlash ) 
                			            							{
		                                        						buttonClicked( 'final' );
                			            							},
		                    			            },
		                    			            
		                    			            ],

		                    	  }    ,
		                          /* Custom defined button*/
		                          { 
			            				"sExtends":    "text",
		                    			"sButtonText": "View Payment",
		                                 "fnClick": function ( nButton, oConfig, oFlash ) {
		                                        buttonClicked( 'payment' );
		                                    }
		                    	  }    ,
		                          /* Custom defined button*/
		                          { 
			            				"sExtends":    "text",
		                    			"sButtonText": "Payment Link To Clipboard",
		                                 "fnClick": function ( nButton, oConfig, oFlash ) {
		                                        buttonClicked( 'paymentlink' );
		                                    }
		                    	  }    ,
		                          /* Custom defined button*/
		                          { 
			            				"sExtends":    "text",
		                    			"sButtonText": "Credit balance manually",
		                                 "fnClick": function ( nButton, oConfig, oFlash ) {
		                                        buttonClicked( 'manualpayment' );
		                                    }
		                    	  }    ,
		                    	  ],
		        	"sSwfPath": "js/TableTools-2.1.5/media/swf/copy_csv_xls_pdf.swf",
		            "sRowSelect": "single",
		        },

			} );
	
			// Init the table 
			oTable = TableTools.fnGetInstance('invoice-table');//$('#invoice-table').dataTable( );

	
} ); //end of document.ready	/js/TableTools-2.1.5/


function buttonClicked( button_name )
{
	selected =  oTable.fnGetSelectedData();
	if( typeof selected[0] != 'undefined')
	{
		server_url = true;
		
		//get the selected invoice id
		text_id = selected[0][0];
		var invoice_id = text_id.match(/\d+$/);
		invoice_id = parseInt(invoice_id, 10);
		
		switch(button_name)
		{
			case 'view':
				serverpath=WEBROOT+'files/pdf_invoices/STS'+invoice_id+'.pdf';
				break;		
			case 'invoice':
				serverpath=WEBROOT+'invoices/send/'+invoice_id+'/1';				
				break;
			case 'reminder':
				serverpath=WEBROOT+'invoices/send/'+invoice_id+'/2';
				break;
			case 'final':
				serverpath=WEBROOT+'invoices/send/'+invoice_id+'/3';
				break;
			case 'reminderadmin':
				//serverpath=WEBROOT+'invoices/sendadmin/'+invoice_id;
				break;
			case 'payment':
				//get the selected invoice id
				text_id = selected[0][6];
				var paid = text_id.match(/\d+$/);
				paid = parseInt(paid, 10);
				if(paid==1)
					serverpath=WEBROOT+'payments/view_details/'+invoice_id;
				else
					alert ('Invoice '+invoice_id+' is unpaid ');
				break;
			case 'paymentlink':
				server_action='paylink';
				result = ajax_call(server_action,invoice_id);
				if(result.status==200)
				{
					copyToClipboard(result.responseText);
					 
				}
				server_url = false;
				break;	
			case 'manualpayment':
				//calls the AjaxController , functionCall action with an action and the invoice id
				server_action='manualpayment';
				result = ajax_call(server_action,invoice_id);
				if(result.status==200)
				{
					//alert(result.responseText);
					alert('Invoice balance cleared manually.');
					location.reload();
				}
				server_url = false;
				break;
			default:
				/*shouldn't ever get here*/
				alert('No Invoice selected !');
				server_url = false;
				break;
		}	
		if(server_url)
			window.location = serverpath;
	}
	else
		alert('Please select an invoice');
}
/*----------------------------ajax call function and success , error functions-----------*/
function ajax_call(server_action,invoice_id)
{
	path = WEBROOT+'ajax/functionCall';
	var jqxhr = $.ajax(	{
			type: 'POST',
			//dataType: 'text',
			url: path,
			data: { sa : server_action , inv : invoice_id },
			async: false,				
			success:	ajaxOK	,	
			error:		ajaxError	
			});	
	return jqxhr;
}
		
function ajaxOK(result) 
{ 
	//window.location = result;
	return result;
}
		
function ajaxError(result) 
{
    if (result.status == 200 && result.statusText == 'OK') 
    {
        alert("FAILED : " + result.status + ' ' + result.statusText);
    }
    else 
    {
        alert("FAILED : " + result.status + ' ' + result.statusText);
    }

    return result;
}
/*----------------------------ajax call function and success , error functions-----------*/
function copyToClipboard (text) {
    window.prompt ("Copy to clipboard: Ctrl+C, Enter",text);
}