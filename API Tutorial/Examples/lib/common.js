
	var nodeUrl = 'http://127.0.0.1:9067';
	var port ='9066';
	var imagebyte;
	$(document).ready(function() {
		$("#menu").html(
		  '<a href="http://erachain.org"><img height=100 width=92 src="era.jpg"></a>'
		+ '<br><br><b>'
		+ '<a href=createaddress.html>Create Addresses</a> | '
		+ '<a href=issueasset.html>Issue Asset</a> | '
		+ '<a href=assetsend.html>Send Asset</a> | '
		+ '<a href=personinfogenerate.html>Generate Person Info</a> | '
		+ '<a href=personparseinfo.html>Parse Person Info</a> | '
		+ '<a href=personinfowritechain.html>Insert Person Info in ERaChain</a> | '
		+ '<a href=personsertify.html>Certification Person </a> | '
		+ '<a href=vouchtransaction.html>Vouch Transaction </a> | '
		+ '<a href=statusissue.html>Issue Status</a> | '
		+ '<a href=statusset.html>Set Status</a> | '
		+ '<a href=ordercreate.html>Create Order</a> | '
		+ '<a href=orderdelete.html>Delete Order</a> | '
		+ '<a href=hashes_issue.html>Write Hashes</a> | '
		+ '<a href=documentissue.html>Issue Document</a> | '
		+ '<a href=' + nodeUrl+ '/index/blockexplorer.html>BlockExplorer</a>'
		+ '</b>');
		
		$("#bottom").html('(c) <a href="mailto:kentrt@Yandex.ru">AlexErm</a><br>');

	});


	function doLoadBalance(base58SenderAccountAddress, elementAccountbalance)
	{
		if(base58SenderAccountAddress == '') {
			elementAccountbalance.val('');
			return;
		}
		
		var nodeUrl = $("#nodeUrl").val();

		$.post( nodeUrl + "/index/api.html", { type: "get", apiurl: "/addresses/balance/" + base58SenderAccountAddress } )
			.done(function( data ) {
				
				if(data.type == 'success'){
					var balanceOfAccount = data.result;
					elementAccountbalance.html(addCommas(balanceOfAccount));
				}
				
				if(data.type == 'apicallerror'){
					$("#output").val(parseError(data.errordetail));
					elementAccountbalance.html('');
				}
				
			})
			.fail(function() {
				$("#output").val( "error" );
			});
	}

	function doLoadInfoForName(name, elementNameInfo)
	{
		if(name == '') {
			elementNameInfo.val('');
			return;
		}
		
		if( name.toLowerCase() != name ) {
			elementNameInfo.val('You must use lowercase letters.');
			return;
		}
		
		var nodeUrl = $("#nodeUrl").val();

		$.post( nodeUrl + "/index/api.html", { type: "get", apiurl: "/names/" + encodeURIComponent(name) } )
			.done(function( data ) {
				
				if(data.type == 'success'){
					var info = JSON.parse(data.result);
					elementNameInfo.val("Registered by " + info.owner);
				}
				
				if(data.type == 'apicallerror'){
					if(parseError(data.errordetail) == 'name does not exist') {
						elementNameInfo.val('Name is free. You can register it.');
					} else {
						elementNameInfo.val(parseError(data.errordetail));
					}
				}
				
			})
			.fail(function() {
				$("#output").val( "error" );
			});
	}	
	
	function doNowTime()
	{
		var date = new Date();
		$('#datetime').val(date.toLocaleDateString() + ' ' + date.toLocaleTimeString());
		$('#timestamp').val(date.getTime());
	}

	function sleep(ms) {
		ms += new Date().getTime();
		while (new Date() < ms){}
	} 

	function addCommas(str)
	{
		strbuf = str.toString();
		if( strbuf.indexOf('.') == -1)
		{
			return strbuf.replace(/(\d)(?=(\d\d\d)+([^\d]|$))/g, '$1,');
		}
		return strbuf.replace(/(\d)(?=(\d\d\d)+([^\d]))/g, '$1,');
	}

	function removeAllexceptDotAndNumbers (str)
	{
		return str.replace(/[^.0-9]/g,"");
	}
	
	function removeAllexceptNumbers(str)
	{
		return str.replace(/[^0-9]/g,"");
	}
	
	function getTextCursorPosition(ele) {   
		return ele.prop("selectionStart");
	}

	function setTextCursorPosition(ele, pos) {
		ele.prop("selectionStart", pos);
		ele.prop("selectionEnd", pos);
	}
	
	function parseError(error) 
	{
		try {
			var error = JSON.parse(error);
			message = error.message;
		} catch (e) {
			message = error;
		}
		return message;
	}

	function doProcess()
	{
		var txRaw = $("#txRaw").val();
		
		if(!txRaw) {
			return;
		}
		
		$('#output').html('Processing...');
		
		var nodeUrl = $("#nodeUrl").val();
		
		$.get( nodeUrl + "/api/broadcast/"+ txRaw , function( data ) {
				
				$("#output").val("Result: " + data.status +"       Message:" + data.message);
			})
			.fail(function() {
				$("#output").val('error!');			
			});
	}
	
	
	function doPostProcess(){
	var txRaw = $("#txRaw").val();
		
		if(!txRaw) {
			return;
		}
		
		$('#output').html('Processing...');
		
		var nodeUrl = $("#nodeUrl").val();
		
		$.post( nodeUrl + "/api/broadcast", "raw=" +txRaw , function( data) {
				
				$("#output").val("Result: " + data.status +"       Message:" + data.message);
							
			})
			.fail(function() {
				$("#output").val('error!');			
			});
	
	
	}
	
	function doLoadLastReference()
	{
		var base58SenderAccountAddress = $('#base58SenderAccountAddress').val();

		if(base58SenderAccountAddress == '') {
			$("#output").val('AccountAddress is null');
			return;
		}
		
		var nodeUrl = $("#nodeUrl").val();

		$('#base58LastReferenceOfAccount').val('...');
		$.get( nodeUrl + "/api/addresslastreference/" + base58SenderAccountAddress, function( data ) {
				
				if(data.type == 'success'){
					var base58LastReferenceOfAccount = data.result;
					$('#base58LastReferenceOfAccount').val(base58LastReferenceOfAccount);
				}
				
				if(data.type == 'apicallerror'){
					$("#output").val(data.errordetail);
					$('#base58LastReferenceOfAccount').val('');
				}
				
			})
			.fail(function() {
				$("#output").val( "error" );
			});
	}
	
	if(false) {
		var ls1 = new Uint8Array();
		var byteArray = [0, 2, -1, 3, 0, 4, 0, 0];
		
		ls1 = appendBuffer(ls1, byteArray);
		
		var byteArray2 = [1, 2, 3, -30, 4, 0, 1];
		
		ls1 = appendBuffer(ls1, byteArray2);

		console.log(ls1);
		
		publicKey = Base58.decode("9NfJZz5pLxhiFT8GfELoTw99x6JxR3mUiQ9SBsrwNbcp");
		lastReference = Base58.decode("YWv9Gyi2xxEyEe6ztrGGuAPhmUD86s7h8CANQAcmsxdeS3pU5BvQKnbeyXjnXXd8HgLaDvYBBz6im3dDYTR817F");
		recipient = Base58.decode("QTz6fSV2VNc2wjwwsw57kwQzgQhmGw5idQ");
		var amount = parseFloat("123.12001");
		var fee = parseFloat("1.0");
		
		var time1 = new Date();

		for (var i = 0; i < 100000; i++) {
			var timestamp = 1455849866776 - Math.random()*100000000;
			buf = generatePaymentTransactionBase(publicKey, lastReference, recipient, amount, fee, timestamp);
		}
		
		console.log(buf);
		
		var time2 = new Date();

		console.log(time2.getTime() - time1.getTime());
		
		time1 = new Date();

		for (var i = 0; i < 100000; i++) {
			var timestamp = 1455849866776 - Math.random()*100000000;
			buf = generatePaymentTransactionBase2(publicKey, lastReference, recipient, amount, fee, timestamp);
		}
		
		console.log(buf);
		
		time2 = new Date();

		console.log(time2.getTime() - time1.getTime());
		
	}
	
	function stringToByte(str){
	var bytes = [];
	for (var i = 0; i < str.length; ++i) {
    bytes.push(str.charCodeAt(i));
	}
	return bytes;
	}
	
	
	
	function toUTF8Array(str) {
    var utf8 = [];
    for (var i=0; i < str.length; i++) {
        var charcode = str.charCodeAt(i);
        if (charcode < 0x80) utf8.push(charcode);
        else if (charcode < 0x800) {
            utf8.push(0xc0 | (charcode >> 6), 
                      0x80 | (charcode & 0x3f));
        }
        else if (charcode < 0xd800 || charcode >= 0xe000) {
            utf8.push(0xe0 | (charcode >> 12), 
                      0x80 | ((charcode>>6) & 0x3f), 
                      0x80 | (charcode & 0x3f));
        }
        // surrogate pair
        else {
            i++;
            // UTF-16 encodes 0x10000-0x10FFFF by
            // subtracting 0x10000 and splitting the
            // 20 bits of 0x0-0xFFFFF into two halves
            charcode = 0x10000 + (((charcode & 0x3ff)<<10)
                      | (str.charCodeAt(i) & 0x3ff));
            utf8.push(0xf0 | (charcode >>18), 
                      0x80 | ((charcode>>12) & 0x3f), 
                      0x80 | ((charcode>>6) & 0x3f), 
                      0x80 | (charcode & 0x3f));
        }
    }
    return utf8;
}
function handleFileSelect(evt) {
    var files = evt.target.files; // FileList object

    // Loop through the FileList and render image files as thumbnails.
    for (var i = 0, f; f = files[i]; i++) {
     // Only process image files.
      if (!f.type.match('image.*')) {
        continue;
      }
      var reader = new FileReader();
     // Read in the image file as a data URL.
	  reader.readAsArrayBuffer(f); // readAsArrayBuffer(f);
	  reader.onload = function(e){
	  imagebyte = new Uint8Array(e.target.result);
	  var  base64String = btoa(String.fromCharCode.apply(null, imagebyte)); 
	  var ss = '<img  src="data:image/jpeg;base64,' + base64String + '" style="max-width:300px; height: AUTO;"/>';
	  $("#img").html(ss);
	}
	 
	}

   }
   
   
	